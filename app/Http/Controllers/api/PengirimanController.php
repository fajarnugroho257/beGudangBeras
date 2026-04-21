<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Karyawan;
use App\Models\api\Pengiriman;
use App\Models\api\PengirimanBebanKaryawan;
use App\Models\api\PengirimanBebanLain;
use App\Models\api\PengirimanData;
use App\Models\StokBarang;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Pengiriman::where('pengiriman_tgl', '>=', $request->dateFrom)
            ->where('pengiriman_tgl', '<=', $request->dateTo)
            ->orderBy('pengiriman_tgl', 'DESC')
            ->get();
        
        $data_kosong = [
            [
                'created_at' => '',
                'barang_id' => '',
                'data_harga' => '0',
                'data_tonase' => '0',
                'data_total' => '0',
                'pembayaran_st' => '',
                'pengiriman_id' => '0',
            ],
        ];
        
        foreach ($data as $key => $value) {
            $listPengiriman = PengirimanData::where('pengiriman_id', '=', $value['id'])->get();
            $jlh = count($listPengiriman);
            $data[$key]['listPengiriman'] = $jlh > 0 ? $listPengiriman : $data_kosong;
            $bebanKaryawan = PengirimanBebanKaryawan::where('pengiriman_id', $value['id'])->sum('beban_value');
            $bebanLain = PengirimanBebanLain::where('pengiriman_id', $value['id'])->sum('beban_value');
            $data[$key]['totalBeban'] = $bebanKaryawan + $bebanLain;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'data_kosong' => $data_kosong,
        ], 200);
    }

    public function get_karyawan()
    {
        $dataKaryawan = Karyawan::all();

        return response()->json([
            'success' => true,
            'dataKaryawan' => $dataKaryawan,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $formData = $data['formData'];
        $pengirimanData = $data['pengirimanData'];
        
        $stPengiriman = DB::transaction(function () use ($pengirimanData, $formData) {

            // 1. Create pengiriman
            $stPengiriman = Pengiriman::create([
                'pengiriman_tgl' => $pengirimanData['pengiriman_tgl'],
                'nama_pembeli'   => $pengirimanData['nama_pembeli'] ?? null,
                'uang_muka'      => $pengirimanData['uang_muka'] ?? null,
                'status'         => $pengirimanData['status'] ?? null,
            ]);

            // 2. Create pengiriman_data + update stock
            foreach ($formData as $value) {

                PengirimanData::create([
                    'pengiriman_id' => $stPengiriman->id,
                    'barang_id'     => $value['barang_id'] ?? null,
                    'data_tonase'   => $value['data_tonase'],
                    'data_harga'    => $value['data_harga'],
                    'data_total'    => $value['data_total'],
                    'pembayaran_st' => $value['pembayaran_st'] ?? 'cash',
                    'supplier_id'   => $value['supplier_id'] ?? null,
                ]);

                if (!empty($value['barang_id']) && !empty($value['supplier_id'])) {

                    $stokBarang = StokBarang::where('barang_id', $value['barang_id'])
                        ->where('suplier_id', $value['supplier_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$stokBarang) {
                        throw new \Exception('Stok barang tidak ditemukan');
                    }

                    if ($stokBarang->stok < $value['data_tonase']) {
                        throw new \Exception('Stok tidak cukup');
                    }

                    $stokBarang->decrement('stok', $value['data_tonase']);
                }
            }

            return $stPengiriman;
        });
        
        // response
        if ($data['type'] == 'simcetak') {
            // Ambil data dari database
            $data = Pengiriman::with('pengirimanData.barang')->find($stPengiriman->id);
            
            if ($data) {
                // Generate image/print
                $ttlData = count($data->pengirimanData);
                $ttlData = $ttlData == 1 ? 2 : $ttlData;
                $width = 500;
                $height = (120 * $ttlData);
                $rowHeight = 40;
                $padding = 10;
                $titleHeight = 60;

                $img = Image::canvas($width, $height, '#ffffff');
                $img->text('Tanggal', 10, 25, function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(16);
                    $font->color('#000000');
                    $font->align('left');
                    $font->valign('middle');
                });
                $img->text(':', 90, 25, function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(16);
                    $font->color('#000000');
                    $font->align('left');
                    $font->valign('middle');
                });
                $img->text($data->pengiriman_tgl->format('d F Y'), 120, 25, function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(16);
                    $font->color('#000000');
                    $font->align('left');
                    $font->valign('middle');
                });
                
                $yPosition = $titleHeight;
                $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
                    $draw->border(1, '#000000');
                });
                
                $img->text('No', $padding, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(14);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text('Barang', 50, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(14);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text('Tonase', 200, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(14);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text('Harga', 300, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(14);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text('Total', 400, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(14);
                    $font->color('#000000');
                    $font->valign('middle');
                });

                $no = 1;
                $grandTotal = 0;
                $yPosition += $rowHeight;
                foreach ($data->pengirimanData as $row) {
                    $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
                        $draw->border(1, '#000000');
                    });
                    $grandTotal += $row->data_total;
                    
                    $img->text($no++, $padding, $yPosition + ($rowHeight / 2), function ($font) {
                        $font->file(public_path('fonts/arial.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                        $font->valign('middle');
                    });
                    
                    $barangNama = $row->barang ? $row->barang->nama : 'N/A';
                    $img->text($barangNama, 50, $yPosition + ($rowHeight / 2), function ($font) {
                        $font->file(public_path('fonts/arial.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                        $font->valign('middle');
                    });
                    $img->text($row->data_tonase, 200, $yPosition + ($rowHeight / 2), function ($font) {
                        $font->file(public_path('fonts/arial.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                        $font->valign('middle');
                    });
                    $img->text('Rp '.number_format($row->data_harga, 0, ',', '.'), 300, $yPosition + ($rowHeight / 2), function ($font) {
                        $font->file(public_path('fonts/arial.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                        $font->valign('middle');
                    });
                    $img->text('Rp '.number_format($row->data_total, 0, ',', '.'), 400, $yPosition + ($rowHeight / 2), function ($font) {
                        $font->file(public_path('fonts/arial.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                        $font->valign('middle');
                    });

                    $yPosition += $rowHeight;
                }

                $img->text('Grand Total', 300, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text('Rp '.number_format($grandTotal, 0, ',', '.'), 400, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });

                if ($img->save(public_path('tabel_pengiriman.png'))) {
                    return $img->response('png');
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat cetakan',
            ], 500);
        } else {
            return response()->json([
                'success' => true,
                'data' => $stPengiriman,
            ], 200);
        }

    }

    public function last_pengiriman_id()
    {
        // get last pengiriman id
        $last_user = Pengiriman::select('pengiriman_id')->orderBy('pengiriman_id', 'DESC')->first();
        if (empty($last_user)) {
            return 'P0001';
        }
        $last_number = substr($last_user->pengiriman_id, 1, 5) + 1;
        $zero = '';
        for ($i = strlen($last_number); $i <= 3; $i++) {
            $zero .= '0';
        }
        $new_id = 'P'.$zero.$last_number;

        //
        return $new_id;
    }

    public function last_data_id($pengiriman_id)
    {
        // This method is no longer needed as data doesn't use custom IDs
        return null;
    }

    /**
     * Display the specified resource.
     */
    public function show($pengiriman_id)
    {
        $data = Pengiriman::find($pengiriman_id);
        
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Pengiriman tidak ditemukan',
            ], 404);
        }
        
        $data['listPengiriman'] = PengirimanData::where('pengiriman_id', $pengiriman_id)->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'pengiriman_id' => $pengiriman_id,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengiriman $pengiriman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengiriman $pengiriman)
    {
        $params = $request->all();
        $formData = $params['formData'];
        $pengirimanData = $params['pengirimanData'];
        
        // Find pengiriman
        $data = Pengiriman::where('id', '=', $pengirimanData['pengiriman_id'])->first();
        
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Pengiriman tidak ditemukan',
            ], 404);
        }
        
        // Update pengiriman data
        $data->pengiriman_tgl = $pengirimanData['pengiriman_tgl'];
        $data->update();
        
        // Delete old pengiriman_data
        PengirimanData::where('pengiriman_id', $pengirimanData['pengiriman_id'])->delete();
        
        // Insert new pengiriman_data
        foreach ($formData as $key => $value) {
            $dataPengirimanDetail = [
                'pengiriman_id' => $pengirimanData['pengiriman_id'],
                'barang_id' => $value['barang_id'] ?? null,
                'data_tonase' => $value['data_tonase'],
                'data_harga' => $value['data_harga'],
                'data_total' => $value['data_total'],
                'pembayaran_st' => $value['pembayaran_st'] ?? null,
            ];
            PengirimanData::create($dataPengirimanDetail);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pengiriman berhasil diupdate',
            'data' => $params,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function store_beban(Request $request)
    {
        $data = $request->all();
        $formDataKaryawan = $data['formDataKaryawan'];
        $formDataLain = $data['formDataLain'];
        $pengirimanData = $data['pengirimanData'];
        //
        $pengiriman_id = $pengirimanData['pengiriman_id'];
        $pengiriman_tgl = $pengirimanData['pengiriman_tgl'];

        //
        if (! empty($formDataKaryawan)) {
            // hapus semua dulu
            PengirimanBebanKaryawan::where('pengiriman_id', $pengiriman_id)->where('beban_tgl', $pengiriman_tgl)->delete();
            foreach ($formDataKaryawan as $key => $value) {
                $dataBebanKaryawan = [
                    'pengiriman_id' => $pengiriman_id,
                    'karyawan_id' => $value['karyawan_id'],
                    'beban_value' => $value['beban_value'],
                    'beban_tgl' => $pengiriman_tgl,
                ];
                PengirimanBebanKaryawan::create($dataBebanKaryawan);
            }
        } else {
            $dataBebanKaryawan = [];
        }

        if (! empty($formDataLain)) {
            PengirimanBebanLain::where('pengiriman_id', $pengiriman_id)->where('beban_tgl', $pengiriman_tgl)->delete();
            foreach ($formDataLain as $key => $value) {
                $dataBebanLain = [
                    'pengiriman_id' => $pengiriman_id,
                    'beban_nama' => $value['beban_nama'],
                    'beban_value' => $value['beban_value'],
                    'beban_tgl' => $pengiriman_tgl,
                ];
                //
                PengirimanBebanLain::create($dataBebanLain);
            }
        } else {
            $dataBebanLain = [];
        }

        // response
        return response()->json([
            'success' => true,
            'data' => $data,
            'dataBebanKaryawan' => $dataBebanKaryawan,
            'dataBebanLain' => $dataBebanLain,
        ], 200);
    }

    public function list_beban_karyawan(Request $request)
    {
        $bebanKaryawan = PengirimanBebanKaryawan::where('pengiriman_id', $request->pengiriman_id)->get();
        $dataBebanLain = PengirimanBebanLain::where('pengiriman_id', $request->pengiriman_id)->get();

        return response()->json([
            'success' => true,
            'dataBebanKaryawan' => $bebanKaryawan,
            'dataBebanLain' => $dataBebanLain,
        ], 200);
    }

    public function destroy(Request $request)
    {
        if (Pengiriman::where('id', $request->pengiriman_id)->delete()) {
            return response()->json([
                'success' => true,
            ], 200);
        }
    }

    public function update_Status(Request $request)
    {
        $detail = PengirimanData::where('id', $request->data_id)->first();
        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
        
        // Update pembayaran_st if provided
        if (!empty($request->value)) {
            $detail->pembayaran_st = $request->value;
        }
        
        if ($detail->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses update data',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update data',
            ], 200);
        }
    }

    public function cetak_image(string $pengiriman_id)
    {
        // Ambil data dari database
        $data = Pengiriman::with('pengirimanData.barang')->where('id', $pengiriman_id)->first();
        
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Pengiriman tidak ditemukan',
            ], 404);
        }
        
        // Konfigurasi ukuran tabel
        $ttlData = count($data->pengirimanData);
        $ttlData = $ttlData == 1 ? 2 : $ttlData;
        $width = 500;    // Lebar tabel
        $height = (120 * $ttlData);   // Tinggi tabel
        $rowHeight = 40; // Tinggi setiap baris
        $padding = 10;   // Jarak teks ke border sel
        $titleHeight = 60; // Ruang untuk keterangan di atas tabel

        // Membuat canvas
        $img = Image::canvas($width, $height, '#ffffff');
        // Tambahkan keterangan di atas tabel
        $img->text('Tanggal', 10, 25, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $img->text(':', 90, 25, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $img->text($data->pengiriman_tgl->format('d F Y'), 120, 25, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $yPosition = $titleHeight;
        // Header tabel
        $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
            $draw->border(1, '#000000');
        });
        $img->text('No', $padding, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Barang', 50, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Tonase', 200, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Harga', 300, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Total', 400, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });

        // Gambar data dan border
        $y = $rowHeight;
        $no = 1;
        $grandTotal = 0;
        $yPosition += $rowHeight;
        foreach ($data->pengirimanData as $row) {
            // Gambar border baris
            $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
                $draw->border(1, '#000000');
            });
            // grand total
            $grandTotal += $row->data_total;
            // Isi teks
            $img->text($no++, $padding, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $barangNama = $row->barang ? $row->barang->nama : 'N/A';
            $img->text($barangNama, 50, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text($row->data_tonase, 200, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Rp '.number_format($row->data_harga, 0, ',', '.'), 300, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Rp '.number_format($row->data_total, 0, ',', '.'), 400, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });

            $yPosition += $rowHeight;
        }

        $img->text('Grand Total', 300, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Rp '.number_format($grandTotal, 0, ',', '.'), 400, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });

        // Simpan atau kirim ke browser
        if ($img->save(public_path('tabel_pengiriman.png'))) {
            // Tentukan path file, misalnya di folder 'public/uploads'
            $filePath = public_path('tabel_pengiriman.png');

            // Cek apakah file ada
            if (File::exists($filePath)) {
                // Mengembalikan response download
                // return response()->download($filePath);
                return $img->response('png');
            } else {
                // Jika file tidak ditemukan, mengembalikan error 404
                return response()->json(['error' => 'File not found.'], 404);
            }
        }
        // return $img->response('png');
    }

    public function store_harga_real(Request $request)
    {
        $detail = PengirimanData::find($request->dataId);
        if (empty($detail)) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 200);
        }
        $detail->data_harga = $request->valHarga;
        $detail->data_total = $request->valReal;
        if ($detail->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses update data',
                'data' => $detail,
            ], 200);
        }
    }
}
