<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Nota;
use App\Models\api\NotaBayar;
use App\Models\api\NotaData;
use App\Models\api\Pembelian;
use App\Models\api\Suplier;
use App\Models\PembelianData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // response
        $dataNota = Nota::with([
            'nota_data.pembelian.pembelianData', 'nota_data.pembelian.suplier',
        ])
            ->select('id', 'nota_st', DB::raw('DATE(created_at) AS tanggal'), DB::raw('TIME(created_at) AS waktu'))
            ->where(DB::raw('DATE(created_at)'), '>=', $request->dateFrom)
            ->where(DB::raw('DATE(created_at)'), '<=', $request->dateTo)
            ->orderBy('created_at', 'DESC')
            ->get();

        // dd($dataNota);
        return response()->json([
            'success' => true,
            'dataNota' => $dataNota,
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
        $nota = Nota::create([
            'nota_st' => 'no',
        ]);
        // nota data
        foreach ($rs_suplier_id as $key => $value) {
            // insert
            NotaData::create([
                'nota_id' => $nota->id,
                'pembelian_id' => $value,
            ]);
            // update suplier st
            $pembelian = Pembelian::where('id', $value)->first();
            if (! empty($pembelian)) {
                $pembelian->pembelian_nota_st = 'yes';
                $pembelian->save();
            }
            $datas[] = [
                'nota_id' => $nota->id,
                'pembelian_id' => $value,
            ];
        }

        // response
        return response()->json([
            'data' => '',
            'success' => true,
            'message' => 'Sukses membuat draft nota',
            'rs_suplier_id' => $rs_suplier_id,
            'datas' => $datas,
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $nota_id)
    {
        $detail = Nota::select('*')->with('nota_bayar')->where('id', $nota_id)->first();
        if (! empty($detail)) {
            $ttl_pembelian = DB::selectOne("SELECT a.id, SUM(d.pembelian_total) AS 'pembelian_total'
                                FROM nota a
                                INNER JOIN nota_data b ON a.id = b.nota_id
                                INNER JOIN pembelian c ON b.pembelian_id = c.id
                                INNER JOIN pembelian_data d ON c.id = d.pembelian_id
                                WHERE a.id = ?
                                GROUP BY a.id", [$nota_id]);
            $pembelian = Nota::with(['nota_data.pembelian.pembelianData.barang', 'nota_data.pembelian.suplier'])->where('id', $nota_id)->first();

            // response
            return response()->json([
                'ttl_pembelian' => $ttl_pembelian,
                'data' => $detail,
                'pembelian' => $pembelian,
                'success' => true,
                'message' => 'Oke',
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
            'id' => 'required',
            'nota_st' => 'required',
        ]);
        $detail = Nota::find($request->id);
        $detail->nota_st = $request->nota_st;
        if ($detail->update()) {
            // update pembelian
            $stPembayaran = $request->nota_st == 'yes' ? 'cash' : 'hutang';
            $nota_datas = NotaData::where('nota_id', $detail->id)->get();
            //
            foreach ($nota_datas as $key => $nota_data) {
                $status = PembelianData::where('pembelian_id', $nota_data->pembelian_id)
                    ->update([
                        'pembayaran' => $stPembayaran,
                    ]);
                if (! $status) {
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
                'message' => 'Sukses',
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
        $detail = Nota::select('*')->with('nota_bayar')->where('id', $nota_id)->first();
        if (empty($detail)) {
            return response()->json([
                'data' => null,
                'success' => false,
                'message' => 'Nota tidak ditemukan',
            ], 200);
        }

        $ttlBayar = $detail->nota_bayar->count() * 50;
        
        // Konfigurasi ukuran tabel
        $width = 1110;    
        // Perkirakan tinggi awal sedikit lebih besar untuk mengantisipasi text wrap
        $height = 800; 
        
        $rowHeightDefault = 40; 
        $titleHeight = 60; 

        // Helper fungsi untuk memotong teks (Word Wrap)
        $wrapText = function ($text, $maxChars = 18) {
            if (strlen($text) <= $maxChars) {
                return [$text];
            }
            return explode("\n", wordwrap($text, $maxChars, "\n", true));
        };

        // Membuat canvas
        $img = Image::canvas($width, $height, '#ffffff');

        // Tambahkan keterangan di atas tabel
        $img->text('Tanggal Cetak', 10, 25, function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text(':', 120, 20, function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text(date('d F Y H:i:s'), 150, 25, function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(16);
            $font->color('#000000');
            $font->valign('middle');
        });

        $yPosition = $titleHeight;

        // Header tabel
        $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeightDefault, function ($draw) {
            $draw->border(1, '#000000');
        });
        
        $headers = [
            'No' => 10, 'Suplier' => 50, 'Tanggal' => 180, 'Barang' => 290, 
            'Tonase' => 440, 'Harga' => 580, 'Total' => 700, 'SubTotal' => 850
        ];
        
        foreach ($headers as $label => $x) {
            $img->text($label, $x, $yPosition + ($rowHeightDefault / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(14);
                $font->color('#000000');
                $font->valign('middle');
            });
        }

        $no = 1;
        $grand_ttl_pembelian = 0;
        $yPosition += $rowHeightDefault;
        
        $pembelian = Nota::with(['nota_data.pembelian.pembelianData.barang', 'nota_data.pembelian.suplier'])->where('id', $nota_id)->first();

        foreach ($pembelian->nota_data as $key => $value) {
            // Wrap teks Supplier (Maksimal 16 karakter per baris)
            $suplierLines = $wrapText($value->pembelian->suplier->suplier_nama, 16);
            
            foreach ($value->pembelian->pembelianData as $key_2 => $pembelian_dt) {
                // Wrap teks Barang (Maksimal 18 karakter per baris)
                $barangLines = $wrapText($pembelian_dt->barang->nama, 18);
                
                // Tentukan tinggi baris berdasarkan teks paling panjang di baris ini
                $maxLines = max(count($suplierLines), count($barangLines));
                $currentRowHeight = max($rowHeightDefault, $maxLines * 20);

                // Garis pembatas baris
                $img->rectangle(0, $yPosition, $width, $yPosition + $currentRowHeight, function ($draw) {
                    $draw->border(1, '#000000');
                });

                // Cetak No & Supplier (hanya di baris pertama jika pembelian punya banyak detail)
                if ($key_2 == 0) {
                    $img->text($no++, 10, $yPosition + 20, function ($font) {
                        $font->file(public_path('fonts/Roboto-Regular.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                    });
                    
                    // Print multiline supplier
                    foreach ($suplierLines as $idx => $line) {
                        $img->text($line, 50, $yPosition + 20 + ($idx * 16), function ($font) {
                            $font->file(public_path('fonts/Roboto-Regular.ttf'));
                            $font->size(12);
                            $font->color('#000000');
                        });
                    }
                }

                // Cetak Tanggal
                $img->text(date('d F Y', strtotime($value->pembelian->pembelian_tgl)), 180, $yPosition + 20, function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                });

                // Cetak Multiline Barang
                foreach ($barangLines as $idx => $line) {
                    $img->text($line, 290, $yPosition + 20 + ($idx * 16), function ($font) {
                        $font->file(public_path('fonts/Roboto-Regular.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                    });
                }

                // Cetak Tonase, Harga, dan Total
                $img->text($pembelian_dt->pembelian_kotor.' || '.$pembelian_dt->pembelian_bersih, 440, $yPosition + 20, function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                });
                $img->text('Rp '.number_format($pembelian_dt->pembelian_harga, 0, ',', '.'), 580, $yPosition + 20, function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                });
                $img->text('Rp '.number_format($pembelian_dt->pembelian_total, 0, ',', '.'), 700, $yPosition + 20, function ($font) {
                    $font->file(public_path('fonts/Roboto-Regular.ttf'));
                    $font->size(12);
                    $font->color('#000000');
                });

                // SubTotal calculation & print
                $subTtlPembelian = 0;
                if ($key_2 == 0) {
                    foreach ($value->pembelian->pembelianData as $pembelian_2) {
                        $subTtlPembelian += $pembelian_2->pembelian_total;
                    }
                    $img->text('Rp '.number_format($subTtlPembelian, 0, ',', '.'), 850, $yPosition + 20, function ($font) {
                        $font->file(public_path('fonts/Roboto-Regular.ttf'));
                        $font->size(12);
                        $font->color('#000000');
                    });
                    $grand_ttl_pembelian += $subTtlPembelian;
                }

                // Pindah ke posisi Y berikutnya mengikuti tinggi baris dinamis
                $yPosition += $currentRowHeight;
            }
        }

        // Baris Grand Total
        $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeightDefault, function ($draw) {
            $draw->border(1, '#000000');
        });
        $img->text('TOTAL', 730, $yPosition + ($rowHeightDefault / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Rp '.number_format($grand_ttl_pembelian, 0, ',', '.'), 850, $yPosition + ($rowHeightDefault / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });

        $yPosition += 40;
        $ttl_cicil = 0;

        // Data Pembayaran / Cicilan
        foreach ($detail->nota_bayar as $row) {
            $img->rectangle(0, $yPosition, $width, $yPosition + $rowHeightDefault, function ($draw) {
                $draw->border(1, '#000000');
            });
            $img->text('TU', 760, $yPosition + ($rowHeightDefault / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text('Rp '.number_format($row->bayar_value, 0, ',', '.'), 850, $yPosition + ($rowHeightDefault / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $img->text(date('d M Y H:i:s', strtotime($row->updated_at)), 960, $yPosition + ($rowHeightDefault / 2), function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(12);
                $font->color('#000000');
                $font->valign('middle');
            });
            $ttl_cicil += $row->bayar_value;
            $yPosition += $rowHeightDefault;
        }

        // Kekurangan Pembayaran
        $img->text('Kekurangan Pembayaran', 680, $yPosition + ($rowHeightDefault / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });
        $img->text('Rp '.number_format($grand_ttl_pembelian - $ttl_cicil, 0, ',', '.'), 850, $yPosition + ($rowHeightDefault / 2), function ($font) {
            $font->file(public_path('fonts/Roboto-Regular.ttf'));
            $font->size(12);
            $font->color('#000000');
            $font->valign('middle');
        });

        // Simpan file
        if ($img->save(public_path('tabel_nota.png'))) {
            $filePath = public_path('tabel_nota.png');
            if (File::exists($filePath)) {
                return $img->response('png');
            } else {
                return response()->json(['error' => 'File not found.'], 404);
            }
        }
    }

    public function delete_nota(Request $request)
    {
        $detail = Nota::where('id', $request->nota_id)->first();
        // print_r($detail);
        if (! empty($detail)) {
            // nota bayar
            $rs_data = NotaData::with('pembelian')->where('nota_id', $detail->id)->get();
            // loop
            foreach ($rs_data as $key => $value) {
                $value->pembelian->pembelian_nota_st = 'no';
                $value->pembelian->save();
            }
            //
            $detail->delete();

            // response
            return response()->json([
                'rs_data' => $rs_data,
                'success' => true,
                'message' => 'Oke',
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
