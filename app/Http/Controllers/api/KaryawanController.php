<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Karyawan;
use App\Models\api\PengirimanBebanKaryawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // response
        $dataKaryawan = Karyawan::all();
        return response()->json([
            'success' => true,
            'dataKaryawan' => $dataKaryawan
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
        $validatedData = $request->validate([
            'karyawan_nama' => 'required|string',
        ]);

        // Use the create method to insert the data into the database
        $model = Karyawan::create($validatedData);
        if ($model) {
            return response()->json([
                'success' => true,
                'data' => 'Okee',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Karyawan::where('id', $id)->first();
        if ($detail) {
            return response()->json([
                'status' => true,
                'data' => $detail,
            ], 200);
        }
    }

    public function show_gaji(string $id, string $dateFrom, string $dateTo)
    {
        $gaji = PengirimanBebanKaryawan::where('karyawan_id', $id)
            ->whereBetween(DB::raw('beban_tgl'), [$dateFrom, $dateTo])
            ->orderBy('beban_tgl', 'ASC')
            ->get();
        // $detail = Karyawan::where('id', $id)->first();
        return response()->json([
            'status' => true,
            'gaji' => $gaji,
            'karyawan' => Karyawan::find($id),
        ], 200);
    }

    public function update_gaji(Request $request)
    {
        $gaji = PengirimanBebanKaryawan::find($request->id);
        $gaji->beban_st = $request->beban_st;
        // $detail = Karyawan::where('id', $id)->first();
        if ($gaji->save()) {
            return response()->json([
                'status' => true,
                'gaji' => $gaji,
            ], 200);
        }
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
        $detail = Karyawan::where('id', $request->id)->first();
        $detail->karyawan_nama = $request->karyawan_nama;
        if ($detail->save()) {
            return response()->json([
                'status' => true,
                'data' => $detail,
            ], 200);
        }
    }

    public function gaji_checked(Request $request)
    {
        // $gaji = PengirimanBebanKaryawan::whereIn('id', $request->tempId)
        //     ->orderBy('beban_tgl', 'ASC')
        //     ->get();
        $gaji = PengirimanBebanKaryawan::where('karyawan_id', $request->idKaryawan)
            ->whereBetween(DB::raw('beban_tgl'), [$request->from, $request->to])
            ->where('beban_st', 'yes')
            ->orderBy('beban_tgl', 'ASC')
            ->get();
        // $detail = Karyawan::where('id', $id)->first();
        return response()->json([
            'status' => true,
            'gaji' => $gaji,
            'karyawan' => Karyawan::find($request->idKaryawan),
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
