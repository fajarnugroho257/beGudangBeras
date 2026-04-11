<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Pembelian;
use App\Models\api\Pengiriman;
use App\Models\api\PengirimanBebanKaryawan;
use App\Models\api\PengirimanBebanLain;
use App\Models\api\PengirimanData;
use App\Models\api\Saldo;
use App\Models\api\Suplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $tanggal_awal = date("Y-01-01", time());
        // $tanggal_akhir = date("Y-11-t", time());
        // echo date("t", time());
        // echo $tanggal_awal;
        // echo $tanggal_akhir;
        $a_date = $request->dateFrom;
        // $tgl_akhir = date("t", strtotime($a_date));
        $b_date = $request->dateTo;
        $num = 0;
        $result = [];
        while (strtotime($a_date) <= strtotime($b_date)) {
            // echo "$a_date<br/>";
            // $result['data'][]['tgl'] = ;
            $pembelian = $this->getDataPembelian($a_date);
            $pengiriman = $this->getDataPengiriman($a_date);
            $ttl_pembelian = is_array($pembelian) ? $pembelian['ttl_pembelian'] : $pembelian->ttl_pembelian;
            $ttl_pengiriman = is_array($pengiriman) ? $pengiriman['ttl_pengiriman'] : $pengiriman->ttl_pengiriman;
            // beban
            // $bebanKaryawan = PengirimanBebanKaryawan::where('beban_tgl', $a_date)->sum('beban_value');
            // $bebanLain = PengirimanBebanLain::where('beban_tgl', $a_date)->sum('beban_value');
            // PENGIRIMAN ID
            // $dataPengiriman = Pengiriman::where('pengiriman_tgl', '=', $a_date)->first();
            $dataPengiriman = DB::table('pengiriman')->where('pengiriman_tgl', '=', $a_date)->first();
            $bebanKaryawan = PengirimanBebanKaryawan::where('pengiriman_id', $dataPengiriman?->id)->sum('beban_value');
            $bebanLain = PengirimanBebanLain::where('pengiriman_id', $dataPengiriman?->id)->sum('beban_value');
            //
            $result['data'][$num][$a_date]['pembelian'] = $pembelian;
            $result['data'][$num][$a_date]['pengiriman'] = $this->getDataPengiriman($a_date);
            //
            $bebanKardus = Pengiriman::withSum('pengirimanData as totalRupiahKardus', 'data_box_rupiah')->where('pengiriman_tgl', $a_date)->first();
            //
            $bbnKardus = empty($bebanKardus->totalRupiahKardus) ? 0 : $bebanKardus->totalRupiahKardus;
            $operasional = $bebanKaryawan + $bebanLain + $bbnKardus;
            $modal = $operasional + $ttl_pembelian;
            $grand_ttl = $ttl_pengiriman - $modal;
            $result['data'][$num][$a_date]['a_date'] = $a_date;
            $result['data'][$num][$a_date]['grand_ttl'] = $grand_ttl;
            $result['data'][$num][$a_date]['operasional'] = $operasional;
            $result['data'][$num][$a_date]['modal'] = $modal;
            $result['data'][$num][$a_date]['bebanKaryawan'] = $bebanKaryawan;
            $result['data'][$num][$a_date]['bebanLain'] = $bebanLain;
            $result['data'][$num][$a_date]['bbnKardus'] = $bbnKardus;
            //
            $result['data'][$num][$a_date]['pengiriman_id'] = $dataPengiriman?->id;

            $a_date = date('Y-m-d', strtotime('+1 day', strtotime($a_date))); // looping tambah 1 date
            $num++;
        }

        // $pembelian = DB::table('pembelian')
        //     ->join('suplier', 'pembelian.suplier_id', '=', 'suplier.suplier_id')
        //     ->select(DB::raw('SUM(pembelian.pembelian_total)ttl_pembelian'), 'suplier.suplier_tgl')
        //     ->where('suplier.suplier_tgl', '=', $a_date)
        //     ->groupBy('suplier.suplier_tgl')
        //     ->get();
        // dd($result);
        return response()->json([
            'success' => true,
            'data' => $result,
        ], 200);
    }

    public function getDataPembelian($suplier_tgl)
    {
        $data = DB::table('pembelian')
            ->join('suplier', 'pembelian.suplier_id', '=', 'suplier.id')
            ->select(DB::raw('SUM(pembelian.pembelian_total)ttl_pembelian'), 'suplier.suplier_tgl')
            ->where('suplier.suplier_tgl', '=', $suplier_tgl)
            ->groupBy('suplier.suplier_tgl')
            ->first();
        if (empty($data)) {
            $data = ['ttl_pembelian' => 0, 'suplier_tgl' => $suplier_tgl];
        }

        return $data;
    }

    public function getDataPengiriman($suplier_tgl)
    {
        $data = DB::table('pengiriman_data')
            ->join('pengiriman', 'pengiriman_data.pengiriman_id', '=', 'pengiriman.id')
            ->select(DB::raw('SUM(data_total)ttl_pengiriman'), 'pengiriman_tgl')
            ->where('pengiriman.pengiriman_tgl', '=', $suplier_tgl)
            ->groupBy('pengiriman.pengiriman_tgl')
            ->first();
        if (empty($data)) {
            $data = ['ttl_pengiriman' => 0, 'pengiriman_tgl' => $suplier_tgl];
        }

        return $data;
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

    public function generateID($dateString)
    {
        $date = Carbon::parse($dateString);

        return $date->format('dmy'); // ddmmyy
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $pembelian = Suplier::orderBy('suplier_tgl', 'DESC')
            ->where('suplier_tgl', '=', $request->suplier_tgl)
            ->get();
        //
        foreach ($pembelian as $key => $value) {
            $pembelian[$key]['listPembelian'] = Pembelian::where('suplier_id', '=', $value->id)->orderBy('pembelian_nama', 'ASC')->get();
            $pembelian[$key]['ttlPembelian'] = Pembelian::where('suplier_id', $value->id)->sum('pembelian_total');
            $pembelian[$key]['ttlPembelianCash'] = Pembelian::where('suplier_id', $value->id)->where('pembayaran', 'cash')->sum('pembelian_total');
        }

        // pembelian
        $group['pemBarang'] = Pembelian::select(
            'pembelian_nama',
            DB::raw('ROUND(SUM(pembelian_kotor),2) AS ttlGroupBarang'),
            DB::raw('SUM(pembelian_total) AS ttlGroupTotal')
        )
            ->whereRelation('suplier', 'suplier_tgl', '=', $request->suplier_tgl)
            ->groupBy('pembelian_nama')
            ->orderBy('pembelian_nama', 'ASC')
            ->get();
        // total beban
        $totalBebanLain = 0;
        // pengiriman
        $pengiriman = Pengiriman::where('pengiriman_tgl', '=', $request->suplier_tgl)
            ->orderBy('pengiriman_tgl', 'DESC')
            ->get();
        foreach ($pengiriman as $key => $value) {
            $pengiriman[$key]['listPengiriman'] =
                PengirimanData::where('pengiriman_id', '=', $value['id'])
                    ->orderBy('data_barang', 'ASC')
                    ->get();
            $bebanKaryawan = PengirimanBebanKaryawan::with('karyawan')->where('pengiriman_id', $value['pengiriman_id'])->get();
            $bebanLain = PengirimanBebanLain::where('pengiriman_id', $value['pengiriman_id'])->get();
            $pengiriman[$key]['bebanKaryawan'] = $bebanKaryawan;
            $pengiriman[$key]['bebanLain'] = $bebanLain;
            $totalBebanLain += PengirimanBebanLain::where('pengiriman_id', $value['pengiriman_id'])->sum('beban_value');
        }
        // pengiriman
        // $group['pengBarang'] = PengirimanData::select(
        //     'data_barang',
        //     DB::raw('SUM(data_tonase) AS ttlGroupBarang'),
        //     DB::raw('SUM(data_total) AS ttlGroupTotal')
        // )
        //     ->whereRelation('pengiriman', 'pengiriman_tgl', '=', $request->suplier_tgl)
        //     ->groupBy('data_barang')
        //     ->orderBy('data_barang', 'ASC')
        //     ->get();
        // --- OLD
        // NEW
        // pembelian
        $group_barang_pembelian = Pembelian::select(
            'pembelian_nama AS barang',
        )
            ->whereRelation('suplier', 'suplier_tgl', '=', $request->suplier_tgl)
            ->groupBy('pembelian_nama')
            ->orderBy('pembelian_nama', 'ASC');
        // Pengiriman
        $group_barang_pengiriman = PengirimanData::select(
            'data_barang  AS barang',
        )
            ->whereRelation('pengiriman', 'pengiriman_tgl', '=', $request->suplier_tgl)
            ->groupBy('data_barang')
            ->orderBy('data_barang', 'ASC');
        $combinedQuery = $group_barang_pembelian->union($group_barang_pengiriman);
        $results = $combinedQuery->orderBy('barang', 'ASC')->get();
        //
        foreach ($results as $key => $value) {
            $results[$key]['pembelian'] = round(Pembelian::where('pembelian_nama', $value['barang'])
                ->whereRelation('suplier', 'suplier_tgl', '=', $request->suplier_tgl)
                ->sum('pembelian_kotor'), 2);
            $results[$key]['pengiriman'] = PengirimanData::where('data_barang', $value['barang'])
                ->whereRelation('pengiriman', 'pengiriman_tgl', '=', $request->suplier_tgl)
                ->sum('data_tonase');
        }
        $group['pengBarang'] = $results;
        //
        $data['pembelian'] = $pembelian;
        $data['pengiriman'] = $pengiriman;
        // ops kardus
        $bebanKardus = Pengiriman::withSum('pengirimanData as totalRupiahKardus', 'data_box_rupiah')->where('pengiriman_tgl', $request->suplier_tgl)->first();
        //
        $bbnKardus = empty($bebanKardus->totalRupiahKardus) ? 0 : $bebanKardus->totalRupiahKardus;
        $data['bbnKardus'] = $bbnKardus;
        //
        $saldo = Saldo::where('id', $this->generateID($request->suplier_tgl))->first();
        $data['saldo_be'] = $saldo['saldo_val'] ?? 0;
        $data['totalBebanLain'] = $totalBebanLain;

        //
        return response()->json([
            'success' => true,
            'data' => $data,
            'groupBarang' => $group,
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
