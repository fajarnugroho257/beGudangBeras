<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Pref;
use App\Models\website\VisiMisi;
use Illuminate\Http\Request;

class VisiMisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Visi & Misi';
        $rs_data = VisiMisi::get();
        $data['rs_data'] = $rs_data;
        $data['detail'] = Pref::where('pref_name', 'pref_misi')->first();
        return view('website.misi.index', $data);
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
        $validated = $request->validate([
            'pref_misi' => 'required',
            'misi' => 'required',
        ]);
        $detail = Pref::where('pref_name', 'pref_misi')->first();
        $detail->pref_value = $request->pref_misi;
        $detail->update();
        // 
        $rs_misi = $request->misi;
        if (!empty($rs_misi)) {
            VisiMisi::truncate();
            // insert
            foreach ($rs_misi as $key => $value) {
                VisiMisi::create([
                    'misi_value' => $value,
                ]);
            }
        }
        
        return redirect()->route('visiMisi')->with('success', 'Data berhasil disimpan');
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
