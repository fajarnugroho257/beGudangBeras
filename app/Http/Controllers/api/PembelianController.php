<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Pembelian;
use App\Models\api\Suplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
        // print_r($request->dateFrom);
        $data = Suplier::orderBy('suplier_tgl', 'DESC')
            ->where('suplier_tgl', '>=', $request->dateFrom)
            ->where('suplier_tgl', '<=', $request->dateTo)
            ->where('suplier_nama', 'like', '%'.$request->supName.'%')
            ->get();
        $pembayaran = empty($request->pembayaran) ? '%' : $request->pembayaran;
        foreach ($data as $key => $value) {
            $pembayaran = empty($request->pembayaran) ? '%' : $request->pembayaran;
            $listPembelian = Pembelian::where('suplier_id', '=', $value['id'])->where('pembayaran', 'LIKE', $pembayaran)->get();
            if (count($listPembelian) > 0) {
                $data[$key]['listPembelian'] = $listPembelian;
                $data[$key]['ttlPembelian'] = Pembelian::where('suplier_id', $value['id'])->where('pembayaran', 'LIKE', $pembayaran)->sum('pembelian_total');
            } else {
                // unset
                $data->forget($key);
            }
        }
        //
        $reindexdata = $data->values();

        return response()->json([
            'success' => true,
            'data' => $reindexdata,
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
        // dd($request->all());
        $data = $request->all();
        $formData = $data['formData'];
        $suplierData = $data['suplierData'];
        // last suplier ID
        $dataSuplier = [
            'suplier_nama' => $suplierData['suplier_nama'],
            'suplier_tgl' => $suplierData['suplier_tgl'],
        ];
        $stSuplier = Suplier::create($dataSuplier);

        // / insert pembelian
        if ($stSuplier) {
            foreach ($formData as $key => $value) {
                $dataPembelian = [
                    'suplier_id' => $stSuplier->id,
                    'pembayaran' => $value['pembayaran'],
                    'pembelian_nama' => $value['pembelian_nama'],
                    'pembelian_kotor' => $value['pembelian_kotor'],
                    'pembelian_potongan' => $value['pembelian_potongan'],
                    'pembelian_bersih' => $value['pembelian_bersih'],
                    'pembelian_harga' => $value['pembelian_harga'],
                    'pembelian_total' => $value['pembelian_total'],
                ];
                //
                Pembelian::create($dataPembelian);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'suplierData' => $dataPembelian,
        ], 200);
        // end
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
        $data = Suplier::find($id);
        $data['listPembelian'] = Pembelian::where('suplier_id', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $data,
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
        $formData = $params['formData'];
        $suplierData = $params['suplierData'];
        $data = Suplier::find($suplierData['id']);
        // update suplier data
        $data->suplier_nama = $suplierData['suplier_nama'];
        $data->suplier_tgl = $suplierData['suplier_tgl'];
        $data->update();
        // delete pembelian
        Pembelian::where('suplier_id', $suplierData['id'])->delete();
        // insert lagi
        foreach ($formData as $key => $value) {
            $dataPembelian = [
                'suplier_id' => $suplierData['id'],
                'pembayaran' => $value['pembayaran'],
                'pembelian_nama' => $value['pembelian_nama'],
                'pembelian_kotor' => $value['pembelian_kotor'],
                'pembelian_potongan' => $value['pembelian_potongan'],
                'pembelian_bersih' => $value['pembelian_bersih'],
                'pembelian_harga' => $value['pembelian_harga'],
                'pembelian_total' => $value['pembelian_total'],
            ];
            //
            Pembelian::create($dataPembelian);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (Suplier::where('id', $request->id)->delete()) {
            return response()->json([
                'success' => true,
            ], 200);
        }
    }

    public function cetak_image(string $suplier_id)
    {
        // Ambil data dari database
        // $data = Suplier::with('pembelian')->where('suplier_id', 'LIKE', $suplier_id)->first();
        $data = Suplier::with('pembelian')->where('id', $suplier_id)->first();
        // Konfigurasi ukuran tabel
        $ttlData = count($data->pembelian);
        $ttlData = $ttlData == 1 ? 2 : $ttlData;
        $width = 500;    // Lebar tabel
        $height = (120 * $ttlData);   // Tinggi tabel
        $rowHeight = 40; // Tinggi setiap baris
        $padding = 10;   // Jarak teks ke border sel
        $titleHeight = 75; // Ruang untuk keterangan di atas tabel

        // Membuat canvas
        $img = Image::canvas($width, $height, '#ffffff');
        // Tambahkan keterangan di atas tabel
        $img->text('Nama', 10, 20, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text(':', 90, 20, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text($data->suplier_nama, 120, 20, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text('Tanggal', 10, 45, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text(':', 90, 45, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text(date('d F Y', strtotime($data->suplier_tgl)), 120, 45, function ($font) {
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
        foreach ($data->pembelian as $row) {
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
            $img->text($row->pembelian_nama, 50, $yPosition + ($rowHeight / 2), function ($font) {
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

            $yPosition += $rowHeight; // Pindah ke baris berikutnya
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
