<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Barang;
use App\Models\api\Pembelian;
use App\Models\api\PembelianData;
use App\Models\api\StokBarang;
use App\Models\api\Suplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
//
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all pembelian with pembelian_data, filtered by date range and supplier name
        $pembayaran = empty($request->pembayaran) ? '%' : $request->pembayaran;

        $pembelianList = Pembelian::with(['suplier', 'pembelianData.barang'])
            ->whereBetween('pembelian_tgl', [$request->dateFrom, $request->dateTo])
            ->whereHas('suplier', function ($query) use ($request) {
                $query->where('suplier_nama', 'like', '%'.$request->supName.'%');
            })
            ->orderBy('pembelian_tgl', 'DESC')
            ->get();

        // Group by pembelian_id for display
        $data = [];

        foreach ($pembelianList as $pembelian) {
            // Filter by pembayaran
            $pembelianData = $pembelian->pembelianData->filter(function ($item) use ($pembayaran) {
                return $item->pembayaran === $pembayaran || $pembayaran === '%';
            })->values();

            if ($pembelianData->count() > 0) {
                $totalPembelian = $pembelianData->sum('pembelian_total');

                $data[] = [
                    'id' => $pembelian->id,
                    'pembelian_nota_st' => $pembelian->pembelian_nota_st,
                    'suplier_id' => $pembelian->suplier->id,
                    'suplier_nama' => $pembelian->suplier->suplier_nama,
                    'alamat' => $pembelian->suplier->alamat,
                    'no_hp' => $pembelian->suplier->no_hp,
                    'pembelian_tgl' => $pembelian->pembelian_tgl,
                    'pembelian_data' => $pembelianData->toArray(),
                    'ttlPembelian' => $totalPembelian,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
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
        $suplierData = $data['suplierData'];

        // return response()->json([
        //     'success' => false,
        //     'message' => 'Data pembelian berhasil disimpan',
        //     'suplier' => $data,
        // ], 201);

        // Handle Suplier - either use existing or create new
        if (! empty($suplierData['suplier_id'])) {
            // Use existing suplier
            $stSuplier = Suplier::find($suplierData['suplier_id']);
            if (! $stSuplier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Suplier tidak ditemukan',
                ], 404);
            }
        } else {
            // Create new suplier
            $dataSuplier = [
                'suplier_nama' => $suplierData['suplier_nama'],
                'alamat' => $suplierData['alamat'] ?? null,
                'no_hp' => $suplierData['no_hp'] ?? null,
            ];
            $stSuplier = Suplier::create($dataSuplier);
        }

        // Create Pembelian with pembelian_tgl
        if ($stSuplier) {
            $pembelian = Pembelian::create([
                'suplier_id' => $stSuplier->id,
                'pembelian_tgl' => $suplierData['pembelian_tgl'] ?? now(),
            ]);

            // Create PembelianData for each item
            if ($pembelian) {
                foreach ($formData as $key => $value) {
                    // Handle Barang - either use existing or create new
                    $barangId = null;
                    if (! empty($value['barang_id'])) {
                        // Use existing barang
                        $barangId = $value['barang_id'];
                    } elseif (! empty($value['barang_nama'])) {
                        // Create new barang
                        $barang = Barang::create([
                            'nama' => $value['barang_nama'],
                            'tipe' => $value['barang_tipe'] ?? null,
                        ]);
                        $barangId = $barang->id;
                    }

                    $dataPembelianDetail = [
                        'pembelian_id' => $pembelian->id,
                        'pembayaran' => $value['pembayaran'],
                        'barang_id' => $barangId,
                        'pembelian_kotor' => $value['pembelian_kotor'],
                        'pembelian_potongan' => $value['pembelian_potongan'],
                        'pembelian_bersih' => $value['pembelian_bersih'],
                        'pembelian_harga' => $value['pembelian_harga'],
                        'pembelian_total' => $value['pembelian_total'],
                        'pembelian_nota_st' => $value['pembelian_nota_st'] ?? 'no',
                    ];
                    PembelianData::create($dataPembelianDetail);

                    // Update or create stock (stok_barang) for each item
                    if ($barangId) {
                        $stokBarang = StokBarang::where('barang_id', $barangId)
                            ->where('suplier_id', $stSuplier->id)
                            ->first();

                        if ($stokBarang) {
                            // Add to existing stock
                            $stokBarang->stok += $value['pembelian_bersih'];
                            $stokBarang->save();
                        } else {
                            // Create new stock entry
                            StokBarang::create([
                                'barang_id' => $barangId,
                                'suplier_id' => $stSuplier->id,
                                'stok' => $value['pembelian_bersih'],
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pembelian berhasil disimpan',
            'suplier' => $stSuplier,
        ], 200);
        // if ($data['type'] == 'simcetak') {
        //     // Ambil data dari database
        //     $data = Suplier::with('pembelian')->where('suplier_id', 'LIKE', $suplier_id)->first();
        //     // Konfigurasi ukuran tabel
        //     $ttlData = count($data->pembelian);
        //     $ttlData = $ttlData == 1 ? 2 : $ttlData;
        //     $width = 500;    // Lebar tabel
        //     $height = (120 * $ttlData);   // Tinggi tabel
        //     $rowHeight = 40; // Tinggi setiap baris
        //     $padding = 10;   // Jarak teks ke border sel
        //     $titleHeight = 75; // Ruang untuk keterangan di atas tabel

        //     // Membuat canvas
        //     $img = Image::canvas($width, $height, '#ffffff');
        //     // Tambahkan keterangan di atas tabel
        //     $img->text('Nama', 10, 20, function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(16);
        //         $font->color('#000000');
        //         $font->align('left'); // Center secara horizontal
        //         $font->valign('middle'); // Center secara vertikal
        //     });
        //     $img->text(':', 90, 20, function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(16);
        //         $font->color('#000000');
        //         $font->align('left'); // Center secara horizontal
        //         $font->valign('middle'); // Center secara vertikal
        //     });
        //     $img->text($data->suplier_nama, 120, 20, function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(16);
        //         $font->color('#000000');
        //         $font->align('left'); // Center secara horizontal
        //         $font->valign('middle'); // Center secara vertikal
        //     });
        //     $img->text('Tanggal', 10, 45, function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(16);
        //         $font->color('#000000');
        //         $font->align('left'); // Center secara horizontal
        //         $font->valign('middle'); // Center secara vertikal
        //     });
        //     $img->text(':', 90, 45, function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(16);
        //         $font->color('#000000');
        //         $font->align('left'); // Center secara horizontal
        //         $font->valign('middle'); // Center secara vertikal
        //     });
        //     $img->text(date("d F Y", strtotime($data->suplier_tgl)), 120, 45, function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(16);
        //         $font->color('#000000');
        //         $font->align('left'); // Center secara horizontal
        //         $font->valign('middle'); // Center secara vertikal
        //     });
        //     $yPosition = $titleHeight;
        //     // Header tabel
        //     $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
        //         $draw->border(1, '#000000'); // Border header
        //     });
        //     $img->text('No', $padding, $yPosition + ($rowHeight / 2), function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(14);
        //         $font->color('#000000');
        //         $font->valign('middle');
        //     });
        //     $img->text('Barang', 50, $yPosition + ($rowHeight / 2), function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(14);
        //         $font->color('#000000');
        //         $font->valign('middle');
        //     });
        //     $img->text('Tonase', 150, $yPosition + ($rowHeight / 2), function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(14);
        //         $font->color('#000000');
        //         $font->valign('middle');
        //     });
        //     $img->text('Harga', 250, $yPosition + ($rowHeight / 2), function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(14);
        //         $font->color('#000000');
        //         $font->valign('middle');
        //     });
        //     $img->text('Total', 350, $yPosition + ($rowHeight / 2), function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(14);
        //         $font->color('#000000');
        //         $font->valign('middle');
        //     });

        //     // Gambar data dan border
        //     $y = $rowHeight;
        //     $no = 1;
        //     $grandTotal = 0;
        //     $yPosition += $rowHeight;
        //     foreach ($data->pembelian as $row) {
        //         // Gambar border baris
        //         $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
        //             $draw->border(1, '#000000');
        //         });
        //         // grand total
        //         $grandTotal += $row->pembelian_total;
        //         // Isi teks
        //         $img->text($no++, $padding, $yPosition + ($rowHeight / 2), function ($font) {
        //             $font->file(public_path('fonts/arial.ttf'));
        //             $font->size(12);
        //             $font->color('#000000');
        //             $font->valign('middle');
        //         });
        //         $img->text($row->pembelian_nama, 50, $yPosition + ($rowHeight / 2), function ($font) {
        //             $font->file(public_path('fonts/arial.ttf'));
        //             $font->size(12);
        //             $font->color('#000000');
        //             $font->valign('middle');
        //         });
        //         $img->text($row->pembelian_kotor . ' | ' . $row->pembelian_bersih, 150, $yPosition + ($rowHeight / 2), function ($font) {
        //             $font->file(public_path('fonts/arial.ttf'));
        //             $font->size(12);
        //             $font->color('#000000');
        //             $font->valign('middle');
        //         });
        //         $img->text("Rp " . number_format($row->pembelian_harga, 0, ',', '.'), 250, $yPosition + ($rowHeight / 2), function ($font) {
        //             $font->file(public_path('fonts/arial.ttf'));
        //             $font->size(12);
        //             $font->color('#000000');
        //             $font->valign('middle');
        //         });
        //         $img->text("Rp " . number_format($row->pembelian_total, 0, ',', '.'), 350, $yPosition + ($rowHeight / 2), function ($font) {
        //             $font->file(public_path('fonts/arial.ttf'));
        //             $font->size(12);
        //             $font->color('#000000');
        //             $font->valign('middle');
        //         });

        //         $yPosition += $rowHeight; // Pindah ke baris berikutnya
        //     }

        //     $img->text('Grand Total', 250, $yPosition + ($rowHeight / 2), function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(12);
        //         $font->color('#000000');
        //         $font->valign('middle');
        //     });
        //     $img->text("Rp " . number_format($grandTotal, 0, ',', '.'), 350, $yPosition + ($rowHeight / 2), function ($font) {
        //         $font->file(public_path('fonts/arial.ttf'));
        //         $font->size(12);
        //         $font->color('#000000');
        //         $font->valign('middle');
        //     });

        //     // Simpan atau kirim ke browser
        //     if ($img->save(public_path('tabel_pembelian.png'))) {
        //         // Tentukan path file, misalnya di folder 'public/uploads'
        //         $filePath = public_path('tabel_pembelian.png');

        //         // Cek apakah file ada
        //         if (File::exists($filePath)) {
        //             // Mengembalikan response download
        //             return response()->download($filePath);
        //         } else {
        //             // Jika file tidak ditemukan, mengembalikan error 404
        //             return response()->json(['error' => 'File not found.'], 404);
        //         }
        //     }
        // } else {
        //     // if ($this->cetak_image($suplier_id)) {
        //     //if auth success
        //     return response()->json([
        //         'success' => true,
        //         'data' => $data,
        //         'suplierData' => $suplierData
        //     ], 200);
        //     // }
        // }

    }

    public function last_suplier_id()
    {
        // get last user id
        $last_user = Suplier::select('suplier_id')->orderBy('suplier_id', 'DESC')->first();
        if (empty($last_user)) {
            return '00001';
        }
        $last_number = substr($last_user->suplier_id, 0, 5) + 1;
        $zero = '';
        for ($i = strlen($last_number); $i <= 4; $i++) {
            $zero .= '0';
        }
        $new_id = $zero.$last_number;

        //
        return $new_id;
    }

    public function last_pembelian_id($suplier_id)
    {
        // get last user id
        $last_user = Pembelian::select('pembelian_id')->where('suplier_id', '=', $suplier_id)->orderBy('pembelian_id', 'DESC')->first();
        if (empty($last_user)) {
            return $suplier_id.'-P01';
        }
        // echo $last_user->pembelian_id . "<br>";
        $last_number = substr($last_user->pembelian_id, 7, 9) + 1;
        // echo $last_number . "<br>";
        $zero = '';
        for ($i = strlen($last_number); $i <= 1; $i++) {
            $zero .= '0';
        }
        $new_id = $suplier_id.'-P'.$zero.$last_number;

        //
        return $new_id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pembelian = Pembelian::with(['suplier', 'pembelianData.barang'])->find($id);

        if (! $pembelian) {
            return response()->json([
                'success' => false,
                'message' => 'Pembelian tidak ditemukan',
            ], 404);
        }

        $totalPembelian = $pembelian->pembelianData->sum('pembelian_total');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pembelian->id,
                'suplier_id' => $pembelian->suplier->id,
                'suplier_nama' => $pembelian->suplier->suplier_nama,
                'alamat' => $pembelian->suplier->alamat,
                'no_hp' => $pembelian->suplier->no_hp,
                'pembelian_tgl' => Carbon::parse($pembelian->pembelian_tgl)->format('Y-m-d'),
                'pembelian_data' => $pembelian->pembelianData->toArray(),
                'ttlPembelian' => $totalPembelian,
            ],
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $params = $request->all();
        $pembelianId = $params['pembelian_id'];
        $formData = $params['formData'];
        $suplierData = $params['suplierData'];

        // Find the pembelian by pembelian_id
        $pembelian = Pembelian::find($pembelianId);

        if (! $pembelian) {
            return response()->json([
                'success' => false,
                'message' => 'Pembelian tidak ditemukan',
            ], 404);
        }

        // Get old items before deletion
        $oldItems = PembelianData::where('pembelian_id', $pembelianId)->pluck('barang_id')->unique();

        // Delete old pembelian_data for this pembelian only
        PembelianData::where('pembelian_id', $pembelianId)->delete();

        // Delete old stock entries for this pembelian's items
        foreach ($oldItems as $barangId) {
            $stokBarang = StokBarang::where('barang_id', $barangId)
                ->where('suplier_id', $pembelian->suplier_id)
                ->first();
            if ($stokBarang) {
                $stokBarang->stok -= $item->pembelian_bersih;
                $stokBarang->save();
            }
        }
        // Update pembelian data
        $pembelian->pembelian_tgl = $suplierData['pembelian_tgl'] ?? $pembelian->pembelian_tgl;
        $pembelian->save();

        // Update suplier data if provided
        if (! empty($suplierData['id'])) {
            $suplier = Suplier::find($suplierData['id']);
            if ($suplier) {
                $suplier->suplier_nama = $suplierData['suplier_nama'] ?? $suplier->suplier_nama;
                $suplier->alamat = $suplierData['alamat'] ?? $suplier->alamat;
                $suplier->no_hp = $suplierData['no_hp'] ?? $suplier->no_hp;
                $suplier->save();
            }
        }

        // Create new PembelianData for each item and update stock
        foreach ($formData as $key => $value) {
            // Handle Barang - either use existing or create new
            $barangId = null;
            if (! empty($value['barang_id'])) {
                // Use existing barang
                $barangId = $value['barang_id'];
            } elseif (! empty($value['barang_nama'])) {
                // Create new barang
                $barang = Barang::create([
                    'nama' => $value['barang_nama'],
                    'tipe' => $value['barang_tipe'] ?? null,
                ]);
                $barangId = $barang->id;
            }

            if ($barangId) {
                $dataPembelianDetail = [
                    'pembelian_id' => $pembelianId,
                    'pembayaran' => $value['pembayaran'],
                    'barang_id' => $barangId,
                    'pembelian_kotor' => $value['pembelian_kotor'],
                    'pembelian_potongan' => $value['pembelian_potongan'],
                    'pembelian_bersih' => $value['pembelian_bersih'],
                    'pembelian_harga' => $value['pembelian_harga'],
                    'pembelian_total' => $value['pembelian_total'],
                    'pembelian_nota_st' => $value['pembelian_nota_st'] ?? 'no',
                ];
                PembelianData::create($dataPembelianDetail);

                // Create stock entry for each item
                StokBarang::create([
                    'barang_id' => $barangId,
                    'suplier_id' => $pembelian->suplier_id,
                    'stok' => $value['pembelian_bersih'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pembelian berhasil diupdate',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pembelianId = $request->id;
        // Delete related pembelian_data first (due to foreign key constraints)
        PembelianData::where('pembelian_id', $pembelianId)->delete();

        // Then delete the pembelian
        if ($pembelian->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pembelian berhasil dihapus',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus data pembelian',
        ], 500);
    }

    public function cetak_image(string $pembelian_id)
    {
        // Ambil data dari database
        $data = Pembelian::with(['suplier', 'pembelianData.barang'])->find($pembelian_id);

        if (! $data) {
            return response()->json([
                'success' => false,
                'message' => 'Pembelian tidak ditemukan',
            ], 404);
        }
        // Konfigurasi ukuran tabel
        $ttlData = count($data->pembelianData);
        $ttlData = $ttlData == 1 ? 2 : $ttlData;
        $width = 500;    // Lebar tabel
        $height = (120 * $ttlData);   // Tinggi tabel
        $rowHeight = 40; // Tinggi setiap baris
        $padding = 10;   // Jarak teks ke border sel
        $titleHeight = 75; // Ruang untuk keterangan di atas tabel

        // Membuat canvas
        $img = Image::canvas($width, $height, '#ffffff');
        // Tambahkan keterangan di atas tabel
        $img->text('Supplier', 10, 20, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $img->text(':', 90, 20, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $img->text($data->suplier->suplier_nama, 120, 20, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $img->text('Tanggal', 10, 45, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $img->text(':', 90, 45, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
        });
        $img->text($data->pembelian_tgl->format('d F Y'), 120, 45, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left');
            $font->valign('middle');
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
        $img->text('Barang', 50, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Tonase', 150, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Harga', 250, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Total', 350, $yPosition + ($rowHeight / 2), function ($font) {
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
        foreach ($data->pembelianData as $row) {
            // Gambar border baris
            $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
                $draw->border(1, '#000000');
            });
            // grand total
            $grandTotal += $row->pembelian_total;
            // Isi teks
            $img->text($no++, $padding, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text($row->barang->nama, 50, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text($row->pembelian_kotor.' | '.$row->pembelian_bersih, 150, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Rp '.number_format($row->pembelian_harga, 0, ',', '.'), 250, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Rp '.number_format($row->pembelian_total, 0, ',', '.'), 350, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });

            $yPosition += $rowHeight;
        }

        $img->text('Grand Total', 250, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Rp '.number_format($grandTotal, 0, ',', '.'), 350, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });

        // Simpan atau kirim ke browser
        if ($img->save(public_path('tabel_pembelian.png'))) {
            // Tentukan path file, misalnya di folder 'public/uploads'
            $filePath = public_path('tabel_pembelian.png');

            // Cek apakah file ada
            if (File::exists($filePath)) {
                // Mengembalikan response download
                return response()->download($filePath);
            } else {
                // Jika file tidak ditemukan, mengembalikan error 404
                return response()->json(['error' => 'File not found.'], 404);
            }
        }
        // return $img->response('png');
    }

    public function detail_download(string $suplier_id)
    {
        // Ambil data dari database
        $data = Suplier::with('pembelian')->where('suplier_id', 'LIKE', $suplier_id)->first();
        // dd($data);
    }

    public function all_data(request $request)
    {
        $data = Suplier::orderBy('suplier_tgl', 'DESC')
            ->where('suplier_tgl', '>=', $request->dateFrom)
            ->where('suplier_tgl', '<=', $request->dateTo)
            ->where('suplier_nama', 'like', '%'.$request->supName.'%')
            ->get();
        // print_r($data);
        $pembayaran = empty($request->pembayaran) ? '%' : $request->pembayaran;
        foreach ($data as $key => $value) {
            $pembayaran = empty($request->pembayaran) ? '%' : $request->pembayaran;
            $listPembelian = Pembelian::where('suplier_id', '=', $value['suplier_id'])->where('pembayaran', 'LIKE', $pembayaran)->get();
            if (count($listPembelian) > 0) {
                $data[$key]['listPembelian'] = $listPembelian;
                $data[$key]['ttlPembelian'] = Pembelian::where('suplier_id', $value['suplier_id'])->where('pembayaran', 'LIKE', $pembayaran)->sum('pembelian_total');
            } else {
                // unset
                $data->forget($key);
            }
        }
        $reindexdata = $data->values();
        // Generate PDF
        $pdf = Pdf::loadView('laporan.laporan_pembelian', compact('reindexdata'))->setPaper('a4', 'landscape');

        // Unduh PDF
        return $pdf->download('user-table.pdf');
    }

    public function print()
    {
        // $connector = new FilePrintConnector("php://output");
        // $printer = new Printer($connector);

        // $printer->text("Toko ABC\n");
        // $printer->text("Tanggal: " . now() . "\n");
        // $printer->text("====================\n");
        // $printer->text("Produk A x2 - Rp10,000\n");
        // $printer->text("Produk B x1 - Rp20,000\n");
        // $printer->text("====================\n");
        // $printer->text("Total: Rp40,000\n");

        // $printer->cut();
        // $printer->close();
        return view('heading.print_2');

        // $connector = new WindowsPrintConnector("NamaPrinter");
        // $printer = new Printer($connector);

        // $printer->text("Toko Anda\n");
        // $printer->text("Jl. Contoh, No. 123\n");
        // $printer->feed(2);
        // $printer->text("Item 1 x2 ......... Rp 20.000\n");
        // $printer->text("Item 2 x1 ......... Rp 10.000\n");
        // $printer->feed(2);
        // $printer->text("Terima Kasih\n");
        // $printer->cut();

        // $printer->close();

    }
}
