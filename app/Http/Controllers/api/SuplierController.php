<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Suplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // response
        $dataSuplier = Suplier::all();

        return response()->json([
            'success' => true,
            'dataSuplier' => $dataSuplier,
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
        $validator = Validator::make($request->all(), [
            'alamat' => 'required',
            'no_hp' => 'required',
            'suplier_nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        Suplier::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Suplier::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'detail' => $detail,
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
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'alamat' => 'required',
            'no_hp' => 'required',
            'suplier_nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $suplier = Suplier::findOrFail($id);

        $suplier->update([
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'suplier_nama' => $request->suplier_nama,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
