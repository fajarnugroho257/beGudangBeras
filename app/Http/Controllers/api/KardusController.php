<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Data_kardus;
use Illuminate\Http\Request;

class KardusController extends Controller
{
    public function index()
    {
        // response
        $dataKardus = Data_kardus::all();
        return response()->json([
            'success' => true,
            'dataKardus' => $dataKardus
        ], 200);
    }

    public function show(string $id)
    {
        $detail = Data_kardus::find($id);
        if ($detail) {
            return response()->json([
                'status' => true,
                'data' => $detail,
            ], 200);
        }
    }

    public function update(Request $request)
    {
        $detail = Data_kardus::where('id', $request->id)->first();
        $detail->harga = $request->harga;
        $detail->jumlah = $request->jumlah;
        if ($detail->save()) {
            return response()->json([
                'success' => true,
                'data' => 'Okee',
            ], 200);
        }
    }


}
