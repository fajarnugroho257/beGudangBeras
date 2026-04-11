<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Artikel;
use App\Models\website\Author;
use App\Models\website\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Artikel';
        $data['rs_data'] = Artikel::orderBy('artikel_date', 'desc')->paginate(50);
        return view('website.artikel.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Artikel';
        $data['rs_author'] = Author::all();
        $data['rs_kategori'] = Kategori::all();
        // dd($data);
        return view('website.artikel.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'kategori_id' => 'required',
            'author_id' => 'required',
            'artikel_title' => 'required',
            'artikel_desc' => 'required',
            'artikel_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'artikel_date' => 'required',
            'artikel_time' => 'required',
        ]);
        //
        $tujuan_image = 'image/artikel';
        $nama_file = '';
        if ($request->hasFile('artikel_image')) {
            //
            $artikel_image = $request->file('artikel_image');
            $artikel_title = str_replace(' ', '_', $request->artikel_title);
            $extension2 = $artikel_image->extension();
            $nama_file = $artikel_title . '.' . $extension2;
            // dd($nama_file);
            $artikel_image->move($tujuan_image, $nama_file);
        }
        $status = Artikel::create([
            'author_id' => $request->author_id,
            'kategori_id' => $request->kategori_id,
            'artikel_title' => $request->artikel_title,
            'artikel_desc' => $request->artikel_desc,
            'artikel_views' => '1',
            'artikel_date' => $request->artikel_date . " " . $request->artikel_time,
            'artikel_path' => $tujuan_image,
            'artikel_name' => $nama_file,
        ]);
        if ($status) {
            return redirect()->route('addArtikelData')->with('success', 'Data berhasil ditambah');
        } else {
            return redirect()->route('addArtikelData')->with('error', 'Data gagal ditambah');
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
    public function edit(string $slug)
    {
        $data['title'] = 'Ubah Artikel';
        $data['rs_author'] = Author::all();
        $data['rs_kategori'] = Kategori::all();
        $detail = Artikel::where('artikel_slug', $slug)->first();
        if (empty($detail)) {
            return redirect()->route('dataArtikel')->with('error', 'Data tidak ditemukan');
        }
        $tanggal = date('Y-m-d', strtotime($detail->artikel_date));
        $time = date('H:m', strtotime($detail->artikel_date));
        $detail->artikel_date = $tanggal;
        $detail->artikel_time = $time;
        // dd($detail);
        $data['detail'] = $detail;
        return view('website.artikel.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'kategori_id' => 'required',
            'author_id' => 'required',
            'artikel_title' => 'required',
            'artikel_desc' => 'required',
            'artikel_date' => 'required',
            'artikel_time' => 'required',
        ]);
        $tujuan_image = 'image/artikel';
        $detail = Artikel::find($id);
        // dd($detail);
        if (empty($detail)) {
            return redirect()->route('artikelData')->with('error', 'Data tidak ditemukan');
        }
        $nama_file = $detail->artikel_name;
        if ($request->hasFile('artikel_image')) {
            $artikel_title = str_replace(' ', '_', $request->artikel_title);
            $this->validate($request, [
                'artikel_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $artikel_image = $request->file('artikel_image');
            $extension2 = $artikel_image->extension();
            $nama_file = $artikel_title . '.' . $extension2;
            // delete dulu
            $path = public_path( $detail->artikel_path . '/' . $detail->artikel_name);
            if (File::exists($path)) {
                File::delete($path);
            }
            // dd($nama_file);
            $artikel_image->move($tujuan_image, $nama_file);
        }
        // update
        $detail->kategori_id = $request->kategori_id;
        $detail->author_id = $request->author_id;
        $detail->artikel_title = $request->artikel_title;
        $detail->artikel_desc = $request->artikel_desc;
        $detail->artikel_date = $request->artikel_date . " " . $request->artikel_time;
        $detail->artikel_path = $tujuan_image;
        $detail->artikel_name = $nama_file;
        if ($detail->update()) {
            return redirect()->route('editArtikelData', $detail->artikel_slug)->with('success', 'Data berhasil diubah');
        } else {
            return redirect()->route('editArtikelData', $detail->artikel_slug)->with('error', 'Data gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $detail = Artikel::where('artikel_slug', $slug)->first();
        if (empty($detail)) {
            return redirect()->route('artikelData')->with('error', 'Data tidak ditemukan');
        }
        // delete dulu
        $path = public_path( $detail->artikel_path . '/' . $detail->artikel_name);
        $detail->delete();
        if (File::exists($path)) {
            File::delete($path);
        }
        return redirect()->route('artikelData')->with('success', 'Data Berhasil dihapus');
    }
}
