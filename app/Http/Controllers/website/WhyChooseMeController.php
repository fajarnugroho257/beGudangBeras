<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Why;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WhyChooseMeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Mengapa Pilih Kami';
        $rs_data = Why::get();
        $data['rs_data'] = $rs_data;
        return view('website.why.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Mengapa Pilih Kami';
        return view('website.why.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'why_desc' => 'required',
            'why_title' => 'required',
            'why_image' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);
        // 
        $why_image = '';
        if ($request->hasFile('why_image')) {
            $tujuan_upload = 'image/why';
            $file = $request->file('why_image');
            $ext  = $file->getClientOriginalExtension();
            $fileName = $request->why_title . "." . $ext;
            //
            if (!$file->move($tujuan_upload, $fileName)) {
                return redirect()->route('addWhyChooseMe')->with('error', 'Gagal simpan foto');
            }
            // name
            $why_image = $fileName;
        }
        // insert
        Why::create([
            'why_desc' => $request->why_desc,
            'why_title' => $request->why_title,
            'why_path' => $tujuan_upload,
            'why_image' => $why_image,
        ]);
        //redirect
        return redirect()->route('addWhyChooseMe')->with('success', 'Data berhasil disimpan');
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
        $detail = Why::find($id);
        if (empty($detail)) {
            return redirect()->route('whyChooseMe')->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = "Ubah Data Mengapa Pilih Kami";
        $data['detail'] = $detail;
        return view('website.why.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $detail = Why::find($id);
        if (empty($detail)) {
            return redirect()->route('whyChooseMe')->with('error', 'Data tidak ditemukan');
        }
        $request->validate([
            'why_desc' => 'required',
            'why_title' => 'required',
        ]);
        if ($request->hasFile('why_image')) {
            $request->validate([
                'why_image' => 'required|image|mimes:jpeg,png,jpg|max:512',
            ]);
        }
        // 
        $why_image = $detail->why_image;
        if ($request->hasFile('why_image')) {
            $tujuan_upload = 'image/why';
            $file = $request->file('why_image');
            $ext  = $file->getClientOriginalExtension();
            $fileName = $request->why_title . "." . $ext;
            // delete dulu
            $path = public_path( $detail->why_path . '/' . $detail->why_image);
            $detail->delete();
            if (File::exists($path)) {
                File::delete($path);
            }
            //
            if (!$file->move($tujuan_upload, $fileName)) {
                return redirect()->route('addWhyChooseMe')->with('error', 'Gagal simpan foto');
            }
            // name
            $why_image = $fileName;
        }
        // 
        $detail->why_desc = $request->why_desc;
        $detail->why_title = $request->why_title;
        $detail->why_image = $why_image;
        if ($detail->save()) {
            return redirect()->route('editWhyChooseMe', $detail->id)->with('success', 'Data berhasil diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Why::find($id);
        if (empty($detail)) {
            // delete dulu
            $path = public_path( $detail->why_path . '/' . $detail->why_image);
            $detail->delete();
            if (File::exists($path)) {
                File::delete($path);
            }
            return redirect()->route('whyChooseMe')->with('error', 'Data tidak ditemukan');
        }
        return redirect()->route('whyChooseMe')->with('success', 'Data Berhasil dihapus');
    }
}
