<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Produk;
use App\Models\website\Produk_image;
use App\Models\website\ProdukDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Produk Kami';
        $data['rs_data'] = Produk::paginate(20);
        return view('website.produk.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Produk Kami';
        return view('website.produk.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'produk_nama' => 'required',
            'produk_rating' => 'required',
            'produk_harga' => 'required',
            'produk_short_desc' => 'required',
            'produk_desc' => 'required',
            'produk_image' => 'required',
            'produk_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'detail_desc' => 'nullable|array',
            // 'file' => 'required',
            // 'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        //
        $produk_path = 'image/produk';
        if ($request->hasFile('produk_image')) {
            $produk_image = $request->file('produk_image');
            // 
            $ext  = $produk_image->getClientOriginalExtension();
            $fileName = $request->produk_nama . "." . $ext;
            // dd($fileName);
            // 
            $produk_image->move($produk_path, $fileName);
            $produk_image = $fileName;
        }
        // 
        Produk::create([
            'produk_nama' => $request->produk_nama,
            'produk_rating' => $request->produk_rating,
            'produk_harga' => $request->produk_harga,
            'produk_short_desc' => $request->produk_short_desc,
            'produk_path' => $produk_path,
            'produk_image' => $produk_image,
            'produk_desc' => $request->produk_desc,
        ]);
        // last id
        $last_id = Produk::orderBy('id', 'desc')->first();
        // 
        $tujuan_upload = 'image/produk/' . str_replace(' ', '_', $request->produk_nama) . "/";
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $key => $image) {
                // Unggah gambar ke folder `public/images`
                $ext  = $image->getClientOriginalExtension();
                $fileName = $request->produk_nama . "_" . ($key + 1) . "." . $ext;
                // 
                $image->move($tujuan_upload, $fileName);
                $imageName[] = $fileName;
            }
            foreach ($imageName as $key => $value) {
                Produk_image::create([
                    'produk_id' => $last_id->id,
                    'path' => $tujuan_upload,
                    'image' => $value,
                ]);
            }
        }
        // desc
        $rs_detail_desc = $request->detail_desc;
        foreach ($rs_detail_desc as $key => $detail_desc) {
            ProdukDetail::create([
                'produk_id' => $last_id->id,
                'detail_jenis' => 'desc',
                'detail_title' => null,
                'detail_desc' => $detail_desc,
            ]);
        }
        $spesifikasi = $request->spesifikasi;
        $spesifikasi_detail = $request->spesifikasi_detail;
        for ($i=0; $i <count($spesifikasi); $i++) { 
            if (!empty($spesifikasi[$i])) {
                ProdukDetail::create([
                    'produk_id' => $last_id->id,
                    'detail_jenis' => 'detail',
                    'detail_title' => $spesifikasi[$i],
                    'detail_desc' => $spesifikasi_detail[$i],
                ]);
            }
        }
        // 
        return redirect()->route('addkatalogProduk')->with('success', 'Image berhasil ditambah');
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
        $detail = Produk::where('slug', $slug)->first();
        if (empty($detail)) {
            return redirect()->route('katalogProduk')->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Ubah Produk Kami';
        $data['detail'] = $detail;
        $data['rs_image'] = Produk_image::where('produk_id', $detail->id)->get();
        $data['rs_desc'] = ProdukDetail::where('detail_jenis', 'desc')->where('produk_id', $detail->id)->get();
        $data['rs_detail'] = ProdukDetail::where('detail_jenis', 'detail')->where('produk_id', $detail->id)->get();
        return view('website.produk.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $validated = $this->validate($request, [
            'produk_nama' => 'required',
            'produk_rating' => 'required',
            'produk_harga' => 'required',
            'produk_short_desc' => 'required',
            'produk_desc' => 'required',
            // 'file' => 'required',
            // 'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $detail = Produk::where('slug', $slug)->first();
        if (empty($detail)) {
            return redirect()->route('katalogProduk')->with('error', 'Data tidak ditemukan');
        }
        // 
        $produk_image = $detail->produk_image;
        if ($request->hasFile('produk_image')) {
            $this->validate($request, [
                'produk_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            // upload
            $produk_image = $request->file('produk_image');
            // delete file sebelumnya
            File::delete(public_path($detail->produk_path . "/" . $detail->produk_image));
            $ext  = $produk_image->getClientOriginalExtension();
            $fileName = $request->produk_nama . "." . $ext;
            // 
            $produk_image->move($detail->produk_path, $fileName);
            $produk_image = $fileName;
        }
        // 
        $validated['produk_image'] = $produk_image;
        $detail->update($validated);

        // last id
        $tujuan_upload = 'image/produk/' . str_replace(' ', '_', $request->produk_nama) . "/";
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $key => $image) {
                // Unggah gambar ke folder `public/images`
                $ext  = $image->getClientOriginalExtension();
                $fileName = $request->produk_nama . "_" . ($key + 1) . "." . $ext;
                // 
                $image->move($tujuan_upload, $fileName);
                $imageName[] = $fileName;
            }
            // 
            foreach ($imageName as $key => $value) {
                Produk_image::create([
                    'produk_id' => $detail->id,
                    'path' => $tujuan_upload,
                    'image' => $value,
                ]);
            }
        }
        // desc
        $rs_detail_desc = $request->detail_desc;
        if (!empty($rs_detail_desc)) {
            // delete dulu
            ProdukDetail::where('produk_id', $detail->id)->where('detail_jenis', 'desc')->delete();
            foreach ($rs_detail_desc as $key => $detail_desc) {
                ProdukDetail::create([
                    'produk_id' => $detail->id,
                    'detail_jenis' => 'desc',
                    'detail_title' => null,
                    'detail_desc' => $detail_desc,
                ]);
            }
        }
        // 
        $spesifikasi = $request->spesifikasi;
        $spesifikasi_detail = $request->spesifikasi_detail;
        if (!empty($spesifikasi)) {
            // delete dulu
            ProdukDetail::where('produk_id', $detail->id)->where('detail_jenis', 'detail')->delete();
            for ($i=0; $i <count($spesifikasi); $i++) { 
                if (!empty($spesifikasi[$i])) {
                    ProdukDetail::create([
                        'produk_id' => $detail->id,
                        'detail_jenis' => 'detail',
                        'detail_title' => $spesifikasi[$i],
                        'detail_desc' => $spesifikasi_detail[$i],
                    ]);
                }
            }
        }
        return redirect()->route('editkatalogProduk', $detail->slug)->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $detail = Produk::where('slug', $slug)->first();
        if (empty($detail)) {
            return redirect()->route('katalogProduk')->with('error', 'Data tidak ditemukan');
        }
        $rs_image = Produk_image::where('produk_id', $detail->id)->get();
        foreach ($rs_image as $key => $value) {
            // image
            $path_detail = public_path( $value->path . '/' . $value->image);
            if (File::exists($path_detail)) {
                File::delete($path_detail);
            }
            Produk_image::find($value->id)->delete();
        }
        $path = public_path( $detail->produk_path . '/' . $detail->produk_image);
        $detail->delete();
        if (File::exists($path)) {
            File::delete($path);
        }
        return redirect()->route('katalogProduk', $detail->slug)->with('success', 'Data berhasil dihapus');
    }

    public function delete_produk_image(string $slug,  string $id)
    {
        $detail = Produk_image::where('id', $id)->first();
        if (empty($detail)) {
            return redirect()->route('katalogProduk')->with('error', 'Data tidak ditemukan');
        }
        if ($detail->delete()) {
            $path = public_path( $detail->path . '/' . $detail->image);
            if (File::exists($path)) {
                File::delete($path);
            }
            return redirect()->route('editkatalogProduk', $slug)->with('success', 'Image berhasil dihapus');
        }
    }

     
}
