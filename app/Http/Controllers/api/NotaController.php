<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Nota;
use App\Models\api\NotaBayar;
use App\Models\api\NotaData;
use App\Models\api\Pembelian;
use App\Models\api\Suplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // hapus jika ada yang null
        $null = Nota::whereNotIn('nota_id', function ($query) {
            $query->select('nota_id')->from('nota_data');
        })->get();
        foreach ($null as $key => $value) {
            // print_r($value->nota_id);
            $detail = Nota::where('nota_id', $value->nota_id)->first();
            $detail->delete();
        }
        // response
        $dataNota = Nota::with([
            'nota_data.suplier.pembelian'
        ])
            ->select('nota_id', 'nota_st', DB::raw('DATE(created_at) AS tanggal'), DB::raw('TIME(created_at) AS waktu'))
            ->where(DB::raw('DATE(created_at)'), '>=', $request->dateFrom)
            ->where(DB::raw('DATE(created_at)'), '<=', $request->dateTo)
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($dataNota);
        return response()->json([
            'success' => true,
            'dataNota' => $dataNota
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
        $rs_suplier_id = $request->selectedIds;
        // cek
        $text = '';
        $error = array();
        foreach ($rs_suplier_id as $key => $value) {
            $jlh = NotaData::where('suplier_id', $value)->first();
            if (!empty($jlh)) {
                $error[] = $value;
            }
            // echo $jlh;
            // if ($jlh >= 1) {
            //     $error[] = $value;
            //     $text .= $value . " - ";
            // }
        }
        // print_r(count($error));
        if (!empty($error)) {
            return response()->json([
                'data' => $error,
                'success' => false,
                'message' => $text,
            ], 200);
            // echo "sini";
        } else {
            // insert nota
            $nota_id = Carbon::now()->format('YmdHis') . Str::random(3); // Format: 20241120093015ABC
            $detail = Nota::where('nota_id', $nota_id)->count();
            if (empty($detail)) {
                Nota::create([
                    'nota_id' => $nota_id,
                ]);
                // nota data
                foreach ($rs_suplier_id as $key => $value) {
                    // insert
                    NotaData::create([
                        'nota_id' => $nota_id,
                        'suplier_id' => $value,
                    ]);
                    // update suplier st
                    $sup = Suplier::where('suplier_id', $value)->first();
                    if (!empty($sup)) {
                        $sup->suplier_nota_st = 'yes';
                        $sup->save();
                    }
                }
                // respomse
                return response()->json([
                    'data' => '',
                    'success' => true,
                    'message' => "Sukses membuat draft nota",
                ], 200);
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $nota_id)
    {
        $detail = Nota::select('*')->with('nota_bayar')->where('nota_id', $nota_id)->first();
        if (!empty($detail)) {
            $ttl_pembelian = DB::selectOne("SELECT a.nota_id, SUM(d.pembelian_total) AS 'pembelian_total'
                                FROM nota a
                                INNER JOIN nota_data b ON a.nota_id = b.nota_id
                                INNER JOIN suplier c ON b.suplier_id = c.suplier_id
                                INNER JOIN pembelian d ON c.suplier_id = d.suplier_id
                                WHERE a.nota_id = ?
                                GROUP BY a.nota_id", [$nota_id]);
            $pembelian = Nota::with('nota_data.suplier.pembelian')->where('nota_id', $nota_id)->first();
            // response
            return response()->json([
                'ttl_pembelian' => $ttl_pembelian,
                'data' => $detail,
                'pembelian' => $pembelian,
                'success' => true,
                'message' => "Oke",
            ], 200);
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Nota tidak ditemukan',
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show_bayar(Request $request)
    {
        $detail = NotaBayar::find($request->id);
        if ($detail) {
            return response()->json([
                'data' => $detail,
                'success' => true,
                'message' => 'Okee',
            ], 200);
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Error',
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function add_bayar(Request $request)
    {
        // print_r($request->all());
        $request->validate([
            'bayarValue' => 'required',
            'notaId' => 'required',
        ]);
        //
        $st = NotaBayar::create([
            'nota_id' => $request->notaId,
            'bayar_value' => $request->bayarValue,
        ]);
        if ($st) {
            return response()->json([
                'data' => null,
                'success' => true,
                'message' => 'Okee',
            ], 200);
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Error',
            ], 200);
        }
    }

    public function update(Request $request)
    {
        // print_r($request->all());
        $request->validate([
            'bayarValue' => 'required',
            'notaId' => 'required',
            'id' => 'required',
        ]);
        //
        $detail = NotaBayar::find($request->id);
        if ($detail) {
            $detail->bayar_value = $request->bayarValue;
            if ($detail->save()) {
                return response()->json([
                    'data' => null,
                    'success' => true,
                    'message' => 'Okee',
                ], 200);
            }
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Error',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $detail = NotaBayar::find($request->id);
        if ($detail->delete()) {
            return response()->json([
                'data' => null,
                'success' => true,
                'message' => 'Okee',
            ], 200);
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Error',
            ], 200);
        }
    }

    public function update_nota(Request $request)
    {
        $request->validate([
            'nota_id' => 'required',
            'nota_st' => 'required',
        ]);
        $detail = Nota::where('nota_id', $request->nota_id)->first();
        $detail->nota_st = $request->nota_st;
        if ($detail->update()) {
            // update pembelian
            $stPembayaran = $request->nota_st == 'yes' ? 'cash' : 'hutang';
            $datasPembelian = Pembelian::whereRelation('suplier.nota_data', 'nota_id', $detail->nota_id)->get();
            //
            foreach ($datasPembelian as $key => $dtPembelian) {
                $dtPembelian->pembayaran = $stPembayaran;
                if (!$dtPembelian->save()) {
                    return response()->json([
                        'data' => null,
                        'success' => false,
                        'message' => 'Error',
                    ], 200);
                }
            }
            return response()->json([
                'data' => null,
                'success' => true,
                'message' => $datasPembelian,
            ], 200);
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Error',
            ], 200);
        }
    }

    public function cetak_image(string $nota_id)
    {
        // return response()->json(['status' => file_exists(public_path('fonts/Roboto-Regular.ttf'))]);
        // 
        $detail = Nota::select('*')->with('nota_bayar')->where('nota_id', $nota_id)->first();
        if (!empty($detail)) {
            $ttl_pembelian = DB::selectOne("SELECT a.nota_id, SUM(d.pembelian_total) AS 'pembelian_total'
                                FROM nota a
                                INNER JOIN nota_data b ON a.nota_id = b.nota_id
                                INNER JOIN suplier c ON b.suplier_id = c.suplier_id
                                INNER JOIN pembelian d ON c.suplier_id = d.suplier_id
                                WHERE a.nota_id = ?
                                GROUP BY a.nota_id", [$nota_id]);
            $pembelian = Nota::with('nota_data.suplier.pembelian')->where('nota_id', $nota_id)->first();
            // response
            // return response()->json([
            //     'ttl_pembelian' => $ttl_pembelian,
            //     'data' => $detail,
            //     'pembelian' => $pembelian,
            //     'success' => true,
            //     'message' => "Oke",
            // ], 200);
            // dd($pembelian->nota_data);
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Nota tidak ditemukan',
            ], 200);
        }
        $ttlBayar = $detail->nota_bayar->count() * 50;
        // echo $ttlBayar;
        // die;
        // Konfigurasi ukuran tabel
        $ttlDataPembelian = Pembelian::whereRelation('suplier.nota_data', 'nota_id', $nota_id)->count();
        // dd($ttlData);
        $width = 910;    // Lebar tabel
        $height = 60 + (55 * $ttlDataPembelian) + $ttlBayar + 80;   // Tinggi tabel
        $height = $height == 0 ? 120 : $height;
        // echo $height;
        // die;
        // $height = 1000;
        $rowHeight = 40; // Tinggi setiap baris
        $padding = 10;   // Jarak teks ke border sel
        $titleHeight = 60; // Ruang untuk keterangan di atas tabel
        // Membuat canvas
        $img = Image::canvas($width, $height, '#ffffff');
        // Tambahkan keterangan di atas tabel
        $img->text('Tanggal Cetak', 10, 25, function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text(':', 120, 20, function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->align('left'); // Center secara horizontal
            $font->valign('middle'); // Center secara vertikal
        });
        $img->text(date("d F Y H:i:s", strtotime(date('Y-m-d H:i:s'))), 150, 25, function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
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
        $img->text('No', 10, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Suplier', 50, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Tanggal', 120, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Barang', 240, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Tonase', 320, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Harga', 430, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Total', 530, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('SubTotal', 660, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(14);
            $font->color('#000000');
            $font->valign('middle');
        });

        // Gambar data dan border
        $y = $rowHeight;
        $no = 1;
        $grandTotal = 0;
        $yPosition += $rowHeight;
        $pengurangan = 0;
        $pembelian = Nota::with('nota_data.suplier.pembelian')->where('nota_id', $nota_id)->first();
        // dd($pembelian);
        $grand_ttl_pembelian = 0;
        // temp
        $tempSuplier = '';
        foreach ($pembelian->nota_data as $key => $value) {
            $img->text($no++, 10, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text($value->suplier->suplier_nama, 50, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            foreach ($value->suplier->pembelian as $key => $pembelian) {
                if (count($value->suplier->pembelian) == 1) {
                    $img->rectangle(0, $yPosition, $width, $yPosition + 0, function ($draw) {
                        $draw->border(1, '#000000');
                    });
                } else {
                    $img->rectangle(0, $yPosition, 640, $yPosition + 0, function ($draw) {
                        $draw->border(1, '#000000');
                    });
                }
                $img->text(date("d F Y", strtotime(date($value->suplier->suplier_tgl))), 120, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text($pembelian->pembelian_nama, 240, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text($pembelian->pembelian_kotor . " || " . $pembelian->pembelian_bersih, 320, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text("Rp " . number_format($pembelian->pembelian_harga, 0, ',', '.'), 430, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                $img->text("Rp " . number_format($pembelian->pembelian_total, 0, ',', '.'), 530, $yPosition + ($rowHeight / 2), function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                    $font->valign('middle');
                });
                if ($key == 0) {
                    $subTtlPembelian = 0;
                    foreach ($value->suplier->pembelian as $key_2 => $pembelian_2) {
                        $subTtlPembelian += $pembelian_2->pembelian_total;
                    }
                    $img->rectangle(630, $yPosition, $width, $yPosition + 0, function ($draw) {
                        $draw->border(1, '#000000');
                    });
                    $img->rectangle(640, $yPosition, 640, $yPosition + $rowHeight, function ($draw) {
                        $draw->border(1, '#000000');
                    });
                    $ttlDatas = count($value->suplier->pembelian);
                    if ($ttlDatas == 1) {
                        $img->text("Rp " . number_format($subTtlPembelian, 0, ',', '.'), 650, $yPosition + ($rowHeight / 2), function ($font) {
                            $font->file(public_path('fonts/Roboto-Regular.ttf'));
                            $font->size(12);
                            $font->color('#000000');
                            $font->valign('middle');
                        });
                    } else {
                        $img->text("Rp " . number_format($subTtlPembelian, 0, ',', '.'), 650, $yPosition + ($rowHeight * $ttlDatas / 2), function ($font) {
                            $font->file(public_path('fonts/Roboto-Regular.ttf'));
                            $font->size(12);
                            $font->color('#000000');
                            $font->valign('middle');
                        });
                    }
                } else {
                    $img->rectangle(640, $yPosition, 640, $yPosition + $rowHeight, function ($draw) {
                        $draw->border(1, '#000000');
                    });
                }
                $yPosition += $rowHeight; // Pindah ke baris berikutnya
                $grand_ttl_pembelian += $pembelian->pembelian_total;
            }
        }
        $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
            $draw->border(1, '#000000');
        });
        $img->text("TOTAL", 530, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text("Rp " . number_format($grand_ttl_pembelian, 0, ',', '.'), 655, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $yPosition = $yPosition + 40;
        $ttl_cicil = 0;
        foreach ($detail->nota_bayar as $row) {
            // Gambar border baris
            $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeight, function ($draw) {
                $draw->border(1, '#000000');
            });
            $img->text("TU", 530, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text("Rp " . number_format($row->bayar_value, 0, ',', '.'), 655, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text(date("d M Y H:i:s", strtotime($row->updated_at)), 770, $yPosition + ($rowHeight / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $ttl_cicil += $row->bayar_value;
            $yPosition += $rowHeight; // Pindah ke baris berikutnya
        }

        $img->text('Kekurangan Pembayaran', 490, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text("Rp " . number_format($grand_ttl_pembelian - $ttl_cicil, 0, ',', '.'), 655, $yPosition + ($rowHeight / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });

        // Simpan atau kirim ke browser
        if ($img->save(public_path('tabel_nota.png'))) {
            // Tentukan path file, misalnya di folder 'public/uploads'
            $filePath = public_path('tabel_nota.png');

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
    
    public function delete_nota(Request $request)
    {
        $detail = Nota::where('nota_id', '=', $request->nota_id)->first();
        // print_r($detail);
        if (!empty($detail)) {
            // nota bayar
            $rs_data = NotaData::with('suplier')->where('nota_id', $detail->nota_id)->get();
            // loop
            foreach ($rs_data as $key => $value) {
                $value->suplier->suplier_nota_st = 'no';
                $value->suplier->save();
            }
            //
            $detail->delete();
            // response
            return response()->json([
                // 'ttl_pembelian' => $ttl_pembelian,
                // 'data' => $detail,
                'rs_data' => $rs_data,
                'success' => true,
                'message' => "Oke",
            ], 200);
        } else {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Nota tidak ditemukan',
            ], 200);
        }
    }
}
