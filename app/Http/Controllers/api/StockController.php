<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StokBarang::query()
            ->with(['suplier', 'barang']);

        // Exact filter
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->input('barang_id'));
        }

        // Filter barang_nama
        if ($request->filled('barang_nama')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->input('barang_nama') . '%');
            });
        }

        // Filter tipe
        if ($request->filled('tipe')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('tipe', $request->input('tipe'));
            });
        }

        // Filter suplier
        if ($request->filled('suplier')) {
            $query->whereHas('suplier', function ($q) use ($request) {
                $q->where('suplier_nama', 'like', '%' . $request->input('suplier') . '%');
            });
        }

        $dataStock = $query->get();

        $grouped = $dataStock
            ->groupBy('barang_id')
            ->map(function ($items) {
                $barang = $items->first()->barang;

                return [
                    'barang_id'   => $barang->id,
                    'barang_nama' => $barang->nama,
                    'tipe'        => $barang->tipe,
                    'total_stok'  => $items->sum('stok'),

                    'suppliers' => $items->map(function ($item) {
                        return [
                            'suplier_id'   => $item->suplier->id,
                            'suplier_nama' => $item->suplier->suplier_nama,
                            'stok'         => $item->stok,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $grouped,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function get_stock(Request $request)
    {
        $query = StokBarang::query()->with(['suplier', 'barang']);

        if ($request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }

        $dataStock = $query->get();

        return response()->json([
            'success' => true,
            'dataStock' => $dataStock,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required',
            'suplier_id' => 'required',
            'stok' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $stock = StokBarang::where('barang_id', $request->barang_id)
            ->where('suplier_id', $request->suplier_id)
            ->first();

        if ($stock) {
            $stock->stok = $request->stok;
            $stock->save();
            $message = 'Stok berhasil ditambahkan';
        } else {
            $stock = StokBarang::create([
                'barang_id' => $request->barang_id,
                'suplier_id' => $request->suplier_id,
                'stok' => $request->stok,
            ]);
            $message = 'Data stok berhasil dibuat';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $stock,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
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
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
