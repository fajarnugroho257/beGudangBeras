<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Galery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GaleryController extends Controller
{
    public function index()
    {
        $data['title'] = 'Galery';
        $data['rs_galery'] = Galery::all();
        // dd($data);
        return view('website.galery.index', $data);
    }

    public function add_proses_galery(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'file' => 'required',
            'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        // menyimpan data file yang diupload ke variabel $file
        $tujuan_upload = 'image/galery';
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $image) {
                // Unggah gambar ke folder `public/images`
                $image->move($tujuan_upload, $image->getClientOriginalName());
                $imageName[] = $image->getClientOriginalName(); // Simpan path ke dalam array
            }
        }
        // upload file
        if ($imageName) {
            foreach ($imageName as $key => $value) {
                Galery::create([
                    'deskripsi' => $request->deskripsi,
                    'image_path' => $tujuan_upload,
                    'image_name' => $value,
                ]);
            }
            return redirect()->route('galery')->with('success', 'Image berhasil ditambah');
        } else {
            return redirect()->route('galery')->with('error', 'Gagal tambah image');
        }

    }

    public function delete_proses_galery(string $id)
    {
        $detail = Galery::find($id);
        if ($detail->delete()) {
            // delete dulu
            $path = public_path( $detail->image_path . '/' . $detail->image_name);
            $detail->delete();
            if (File::exists($path)) {
                File::delete($path);
            }
            return redirect()->route('galery')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->route('galery')->with('error', 'Data gagal dihapus');
        }
    }
    
}
