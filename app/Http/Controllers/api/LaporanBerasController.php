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

class LaporanBerasController extends Controller
{
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
            $pengiriman = Pengiriman::where(
                'pengiriman_tgl',
                $resultDate
            )->sum('total_biaya');
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

        return response()->json([
            'success' => true,
            'start' => $start,
            'end' => $end,
            'rs_data' => $tanggal,
        ], 200);
    }

    public function detail(string $tanggal)
    {
        $pembelian = Pembelian::with(['pembelianData.barang', 'suplier'])->where('pembelian_tgl', $tanggal)->get();
        $pengiriman = Pengiriman::with([
            'pengirimanData.barang',
            'pengirimanData.suplier',
            'bebanPengiriman',
            'pengirimanBebanKaryawan.karyawan',
            'pengirimanBebanLain',
        ])
            ->where('pengiriman_tgl', $tanggal)
            ->get();

        return response()->json([
            'success' => true,
            'tanggal' => $tanggal,
            'pembelian' => $pembelian,
            'pengiriman' => $pengiriman,
        ], 200);
    }
}
