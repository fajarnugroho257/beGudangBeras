<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Data_kardus;
use App\Models\api\Karyawan;
use App\Models\api\Pengiriman;
use App\Models\api\PengirimanBebanKaryawan;
use App\Models\api\PengirimanBebanLain;
use App\Models\api\PengirimanData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Pengiriman::select('pengiriman.*', 'beban_pengiriman.jumlah')
            ->where('pengiriman_tgl', '>=', $request->dateFrom)
            ->where('pengiriman_tgl', '<=', $request->dateTo)
            ->leftJoin('beban_pengiriman', 'pengiriman.id', '=', 'beban_pengiriman.pengiriman_id')
            ->orderBy('pengiriman_tgl', 'DESC')
            ->get();
        $data_kosong = [
            [
                'created_at' => '',
                'data_barang' => '',
                'data_box' => 0,
                'data_box_rupiah' => '0',
                'data_harga' => '0',
                'data_id' => '',
                'data_merek' => '',
                'data_st' => '',
                'data_tonase' => '0',
                'data_total' => '0',
                'data_estimasi' => '0',
                'data_datas' => '0',
                'pengiriman_id' => '0',
            ],
        ];
        foreach ($data as $key => $value) {
            $listPengiriman = PengirimanData::where('pengiriman_id', '=', $value['id'])
                ->where(DB::raw("CONCAT(data_merek, ' ', data_barang)"), 'LIKE', '%'.$request->params.'%')
                ->get();
            $jlh = count($listPengiriman);
            $data[$key]['listPengiriman'] = $jlh > 0 ? $listPengiriman : $data_kosong;
            $bebanKaryawan = PengirimanBebanKaryawan::where('pengiriman_id', $value['id'])->sum('beban_value');
            $bebanLain = PengirimanBebanLain::where('pengiriman_id', $value['id'])->sum('beban_value');
            // kardus
            $bebanKardus = Pengiriman::withSum('pengirimanData as totalRupiahKardus', 'data_box_rupiah')->where('id', $value['id'])->first();
            $data[$key]['bebanKaryawan'] = $bebanKaryawan + $bebanLain + $bebanKardus->totalRupiahKardus;
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
        // last pengiriman ID
        $dataPengiriman = [
            'pengiriman_tgl' => $pengirimanData['pengiriman_tgl'],
        ];
        $stPengiriman = Pengiriman::create($dataPengiriman);
        // data pengiriman
        if ($stPengiriman) {
            $jlhKardusTerpakai = 0;
            foreach ($formData as $key => $value) {
                $dataPengiriman = [
                    'pengiriman_id' => $stPengiriman->id,
                    'data_merek' => $value['data_merek'],
                    'data_barang' => $value['data_barang'],
                    'data_box' => (int) $value['data_box'],
                    'data_box_rupiah' => (int) $value['data_box_rupiah'],
                    'data_tonase' => $value['data_tonase'],
                    'data_datas' => $value['data_datas'],
                    'data_estimasi' => $value['data_estimasi'],
                ];
                //
                PengirimanData::create($dataPengiriman);
                // print_r($dataPembelian);
                $jlhKardusTerpakai += $value['data_box'];
            }
            // update kardus
            $id = '1';
            $dtKardus = Data_kardus::find($id);
            $sisa = $dtKardus->jumlah - $jlhKardusTerpakai;
            $dtKardus->jumlah = $sisa;
            $dtKardus->update();
        }
        // response
        if ($data['type'] == 'simcetak') {
            // Ambil data dari database
            $data = Pengiriman::with('pengirimanData')->where('pengiriman_id', 'LIKE', $stPengiriman->id)->first();
            // Konfigurasi ukuran tabel
            $ttlData = count($data->pengirimanData);
            $ttlData = $ttlData == 1 ? 2 : $ttlData;
            $width = 450;    // Lebar tabel
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
                $font->align('left'); // Center secara horizontal
                $font->valign('middle'); // Center secara vertikal
            });
            $img->text(':', 90, 25, function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(16);
                $font->color('#000000');
                $font->align('left'); // Center secara horizontal
                $font->valign('middle'); // Center secara vertikal
            });
            $img->text(date('d F Y', strtotime($data->pengiriman_tgl)), 120, 25, function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(16);
                $font->color('#000000');
                $font->align('left'); // Center secara horizontal
                $font->valign('middle'); // Center secara vertikal
            });
            $yPosition = $titleHeight;
            // Header tabel
            $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
                $draw->border(1, '#000000'); // Border header
            });
            $img->text('No', $padding, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(14);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Merek', 50, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(14);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Nama Barang', 150, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(14);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Tonase', 280, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(14);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Total', 360, $yPosition + ($rowHeight / 2), function ($font) {
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
                $img->text($row->data_merek, 50, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text($row->data_barang, 150, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text($row->data_tonase, 280, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text('Rp '.number_format($row->data_total, 0, ',', '.'), 360, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/arial.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });

                $yPosition += $rowHeight; // Pindah ke baris berikutnya
            }

            $img->text('Grand Total', 280, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Rp '.number_format($grandTotal, 0, ',', '.'), 360, $yPosition + ($rowHeight / 2), function ($font) {
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
        } else {
            return response()->json([
                'success' => true,
                'data' => $data,
                'dataPengiriman' => $dataPengiriman,
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
        // get last  id
        $last_id = PengirimanData::select('data_id')->where('pengiriman_id', '=', $pengiriman_id)->orderBy('data_id', 'DESC')->first();
        if (empty($last_id)) {
            return $pengiriman_id.'-D01';
        }
        // echo $last_id->data_id . "<br>";
        $last_number = substr($last_id->data_id, 7, 9) + 1;
        // echo $last_number . "<br>";
        // die;
        $zero = '';
        for ($i = strlen($last_number); $i <= 1; $i++) {
            $zero .= '0';
        }
        $new_id = $pengiriman_id.'-P'.$zero.$last_number;

        //
        return $new_id;
    }

    /**
     * Display the specified resource.
     */
    public function show($pengiriman_id)
    {
        $data = Pengiriman::find($pengiriman_id);
        $kardus = Data_kardus::select('harga')->first();
        $data['listPembelian'] = PengirimanData::select('*', DB::raw("$kardus->harga AS data_box_harga"))->where('pengiriman_id', $pengiriman_id)->get();

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
        $data = Pengiriman::where('id', '=', $pengirimanData['pengiriman_id'])->first();
        // update pengiriman data
        $data->pengiriman_tgl = $pengirimanData['pengiriman_tgl'];
        $data->update();
        // delete pengiriman
        PengirimanData::where('id', $pengirimanData['pengiriman_id'])->delete();
        // insert lagi
        foreach ($formData as $key => $value) {
            $dataPengiriman = [
                'pengiriman_id' => $pengirimanData['pengiriman_id'],
                'data_merek' => $value['data_merek'],
                'data_barang' => $value['data_barang'],
                'data_box' => $value['data_box'],
                'data_tonase' => $value['data_tonase'],
                'data_harga' => $value['data_harga'],
                'data_total' => $value['data_total'],
                'data_box_rupiah' => $value['data_box_rupiah'],
                'data_datas' => $value['data_datas'],
                'data_estimasi' => $value['data_estimasi'],
                'data_st' => $value['data_st'],
            ];
            //
            PengirimanData::create($dataPengiriman);
        }

        return response()->json([
            'success' => true,
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
        $bebanKardus = Pengiriman::withSum('pengirimanData as totalRupiahKardus', 'data_box_rupiah')->where('pengiriman_tgl', $request->pengiriman_tgl)->first();

        $bebanKardus = empty($bebanKardus) ? 0 : $bebanKardus->totalRupiahKardus;

        //
        return response()->json([
            'success' => true,
            'dataBebanKaryawan' => $bebanKaryawan,
            'dataBebanLain' => $dataBebanLain,
            'bebanKardus' => $bebanKardus,
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
        // print_r($request->all());
        $detail = PengirimanData::where('id', $request->data_id)->first();
        if (empty($detail)) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 200);
        }
        $detail->data_st = $request->value;
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
        $data = Pengiriman::with('pengirimanData')->where('id', $pengiriman_id)->first();
        // Konfigurasi ukuran tabel
        $ttlData = count($data->pengirimanData);
        $ttlData = $ttlData == 1 ? 2 : $ttlData;
        $width = 450;    // Lebar tabel
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
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text(':', 90, 25, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text(date('d F Y', strtotime($data->pengiriman_tgl)), 120, 25, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $yPosition = $titleHeight;
        // Header tabel
        $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
            $draw->border(1, '#000000'); // Border header
        });
        $img->text('No', $padding, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Merek', 50, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Nama Barang', 150, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Tonase', 280, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Total', 360, $yPosition + ($rowHeight / 2), function ($font) {
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
            $img->text($row->data_merek, 50, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text($row->data_barang, 150, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text($row->data_tonase, 280, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Rp '.number_format($row->data_total, 0, ',', '.'), 360, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });

            $yPosition += $rowHeight; // Pindah ke baris berikutnya
        }

        $img->text('Grand Total', 280, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Rp '.number_format($grandTotal, 0, ',', '.'), 360, $yPosition + ($rowHeight / 2), function ($font) {
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
