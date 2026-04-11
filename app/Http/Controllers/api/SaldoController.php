<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Saldo;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validatedData = $request->validate([
            'saldo_val' => 'required',
        ]);
        // cek
        $detail = Saldo::where('id', $request->saldo_id)->first();
        if (empty($detail)) {
            $model = Saldo::create($validatedData);
            if ($model) {
                return response()->json([
                    'success' => true,
                    'message' => 'Saldo berhasil disimpan',
                    'validatedData' => $validatedData,
                ], 200);
            }
        } else {
            // update
            $detail->saldo_val = $request->saldo_val;
            if ($detail->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Saldo berhasil diupdate',
                ], 200);
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
