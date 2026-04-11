<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Pref;
use Illuminate\Http\Request;

class AboutMeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Tentang kami';
        $barang_nama = empty($barang_nama) ? '%' : '%' . $barang_nama . '%';
        $about_me = Pref::where('pref_name', 'aboutme')->first();
        $data['detail'] = $about_me;
        $rs_image = Pref::where('pref_name', 'pref_image')->get();
        $data['rs_image'] = $rs_image;
        return view('website.aboutme.index', $data);
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
        $validated = $request->validate([
            'pref_value' => 'required',
        ]);
        $detail = Pref::find($id);
        if (empty($detail)) {
            return redirect()->route('tentangKami')->with('error', 'Data tidak ditemukan');
        }
        // update
        if($detail->update($validated)){
            // insert image
            $path = 'image/tentang_kami';
            if ($request->hasFile('pref_image')) {
                $pref_image = $request->file('pref_image');
                $this->validate($request, [
                    'pref_image' => 'required',
                    'pref_image.*' => 'image|mimes:jpeg,png,jpg|max:2048',
                ]);
                // dd($pref_image);
                // loop
                // foreach ($rs_pref_image as $image) {
                $detail = Pref::find('7');
                $tujuan_upload = 'image/aboutme';
                $file = $request->file('pref_image');
                $ext  = $file[0]->getClientOriginalExtension();
                $fileName = "tentang-putra-cabe." . $ext;
                if (!$file[0]->move($tujuan_upload, $fileName)) {
                    return redirect()->route('tentangKami')->with('error', 'Gagal simpan foto');
                }
                $detail->pref_value = $fileName;
                $detail->update();
            }
        }
        return redirect()->route('tentangKami')->with('success', 'Data Berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Pref::find($id);
        if (empty($detail)) {
            return redirect()->route('tentangKami')->with('error', 'Data tidak ditemukan');
        }
        if ($detail->delete()) {
           return redirect()->route('tentangKami')->with('success', 'Data Berhasil dihapus');
        }
    }
}
