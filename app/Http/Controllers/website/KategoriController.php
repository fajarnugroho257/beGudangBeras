<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Artikel Kategori';
        $data['rs_data'] = Kategori::all();
        return view('website.kategori.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'color' => 'required',
            'stastu' => 'required',
        ]);
        Kategori::create($validated);
        return redirect()->route('artikelKategori')->with('success', 'Data berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->query('id');
        $detail = Kategori::find($id);
        if (!empty($detail)) {
            $status = true;
            $data = $detail;
            $message = "oke";
        } else {
            $status = false;
            $data = array();
            $message = "Data tidak ditemukan";
        }
        return response()->json([
            'status' => $status,
            'id' => $id,
            'data' => $data,
            'message' => $message,
        ]);
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
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'color' => 'required',
            'stastu' => 'required',
        ]);
        $detail = Kategori::find($request->id);
        $detail->update($validated);
        return redirect()->route('artikelKategori')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Kategori::find($id);
        if (empty($detail)) {
            return redirect()->route('artikelKategori')->with('error', 'Data tidak ditemukan');
        }
        $detail->delete();
        return redirect()->route('artikelKategori')->with('success', 'Data berhasil diubah');
    }
}
