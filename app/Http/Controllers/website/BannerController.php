<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Banner";
        $data['rs_data'] = Banner::orderByRaw('CAST(banner_urut AS UNSIGNED) ASC')->paginate(10);
        // dd($data);
        return view('website.banner.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = "Tambah Banner";
        return view('website.banner.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'banner_title' => 'required',
            'banner_desc' => 'required',
            'banner_urut' => 'required',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
        ]);
        //
        $banner_name = '';
        if ($request->hasFile('banner_image')) {
            $tujuan_upload = 'image/banner';
            $file = $request->file('banner_image');
            //
            if (!$file->move($tujuan_upload, $file->getClientOriginalName())) {
                return redirect()->route('addBanner')->with('error', 'Gagal simpan foto');
            }
            // name
            $banner_name = $file->getClientOriginalName();
        }
        // insert
        Banner::create([
            'banner_path' => $tujuan_upload . "/" . $banner_name,
            'banner_title' => $request->banner_title,
            'banner_desc' => $request->banner_desc,
            'banner_urut' => $request->banner_urut,
        ]);
        //redirect
        return redirect()->route('addBanner')->with('success', 'Data berhasil disimpan');
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
        $detail = Banner::find($id);
        if (empty($detail)) {
            return redirect()->route('banner')->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = "Ubah Banner";
        $data['detail'] = $detail;
        return view('website.banner.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $detail = Banner::find($id);
        if (empty($detail)) {
            return redirect()->route('banner')->with('error', 'Data tidak ditemukan');
        }
        $request->validate([
            'banner_title' => 'required',
            'banner_desc' => 'required',
            'banner_urut' => 'required',
        ]);
        if ($request->hasFile('banner_image')) {
            $request->validate([
                'banner_image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            ]);
        }
        // 
        $banner_path = $detail->banner_path;
        if ($request->hasFile('banner_image')) {
            // delete
            File::delete(public_path($detail->banner_path));
            // 
            $tujuan_upload = 'image/banner';
            $file = $request->file('banner_image');
            //
            if (!$file->move($tujuan_upload, $file->getClientOriginalName())) {
                return redirect()->route('editBanner', $detail->id)->with('error', 'Gagal simpan foto');
            }
            // name
            $banner_path = $tujuan_upload . "/" . $file->getClientOriginalName();
        }
        // update
        $detail->banner_path = $banner_path;
        $detail->banner_title = $request->banner_title;
        $detail->banner_desc = $request->banner_desc;
        $detail->banner_urut = $request->banner_urut;
        if ($detail->save()) {
            return redirect()->route('editBanner', $detail->id)->with('success', 'Data Berhasil disimpan');
        } else {
            return redirect()->route('editBanner', $detail->id)->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Banner::find($id);
        if (empty($detail)) {
            return redirect()->route('banner')->with('error', 'Data tidak ditemukan');
        }
        if ($detail->delete()) {
            File::delete(public_path($detail->banner_path));
            return redirect()->route('banner')->with('success', 'Data Berhasil dihapus');
        } else {
            return redirect()->route('banner')->with('error', 'Data gagal dihapus');
        }
    }
}
