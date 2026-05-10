<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\PembelianData;
use App\Models\Pengiriman;
use App\Models\PengirimanBebanKaryawan;
use App\Models\PengirimanBebanLain;
use App\Models\PengirimanData;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class LaporanBerasController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $start, string $end)
    {
        $period = CarbonPeriod::create($start, $end);

        $rs_data = [];
        $key = 0;
        foreach ($period as $date) {
            // $res_date[] = $key++;
            $resultDate = $date->format('Y-m-d');
            $tanggal[$key]['tanggal'] = $resultDate;
            // pembelian
            $total_pembelian = PembelianData::whereRelation(
                'pembelian',
                'pembelian_tgl',
                $resultDate
            )->sum('pembelian_total');
            $tanggal[$key]['total_pembelian'] = $total_pembelian ?? 0;
            // tonase
            $total_pembelian_tonase = PembelianData::whereRelation(
                'pembelian',
                'pembelian_tgl',
                $resultDate
            )->sum('pembelian_bersih');
            $tanggal[$key]['total_pembelian_tonase'] = $total_pembelian_tonase ?? 0;
            //
            $bebanKaryawan = PengirimanBebanKaryawan::whereRelation('pengiriman', 'pengiriman_tgl', $resultDate)->sum('beban_value');
            $tanggal[$key]['total_bebanKaryawan'] = $bebanKaryawan ?? 0;
            $bebanLain = PengirimanBebanLain::whereRelation('pengiriman', 'pengiriman_tgl', $resultDate)->sum('beban_value');
            $tanggal[$key]['total_bebanLain'] = $bebanLain ?? 0;
            $tanggal[$key]['total_bebanSemua'] = $bebanLain + $bebanKaryawan;
            // pengiriman
            $pengiriman = PengirimanData::whereRelation(
                'pengiriman',
                'pengiriman_tgl',
                $resultDate
            )->sum('data_total');
            $tanggal[$key]['total_pengiriman'] = $pengiriman ?? 0;
            // tonase
            $total_pengiriman_tonase = PengirimanData::whereRelation(
                'pengiriman',
                'pengiriman_tgl',
                $resultDate
            )->sum('data_tonase');
            $tanggal[$key]['total_pengiriman_tonase'] = $total_pengiriman_tonase ?? 0;
            $key++;
        }

        // $pengiriman = Pengiriman::with('pengirimanData')
        //     ->get();

        return response()->json([
            'success' => true,
            'start' => $start,
            'end' => $end,
            'rs_data' => $tanggal,
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
