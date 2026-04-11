<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Artikel Author';
        $data['rs_data'] = Author::all();
        return view('website.author.index', $data);
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
            'author_name' => 'required',
            'author_desc' => 'required',
            'author_image' => 'required|image|mimes:jpeg,png,jpg|max:512',
        ]);
        $author_path = 'image/author';
        if ($request->hasFile('author_image')) {
            $author_image = $request->file('author_image');
            // 
            $ext  = $author_image->getClientOriginalExtension();
            $fileName = str_replace(' ', '_', $request->author_name) . "." . $ext;
            // dd($fileName);
            // 
            $author_image->move($author_path, $fileName);
            $author_image = $fileName;
        }
        $validated['author_path'] = $author_path;
        $validated['author_image'] = $author_image;
        // dd($validated);
        Author::create($validated);
        return redirect()->route('artikelAuthor')->with('success', 'Data berhasil ditambah');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->query('id');
        $detail = Author::find($id);
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
            'author_name' => 'required',
            'author_desc' => 'required',
        ]);
        $detail = Author::find($request->id);
        if (empty($detail)) {
            return redirect()->route('artikelAuthor')->with('error', 'Data tidak ditemukan');
        }
        $author_image = $detail->author_image;
        if ($request->hasFile('author_image')) {
            // 
            $request->validate([
                'author_image' => 'required|image|mimes:jpeg,png,jpg|max:512',
            ]);
            // 
            $author_path = 'image/author';
            $author_image = $request->file('author_image');
            // 
            $ext  = $author_image->getClientOriginalExtension();
            $fileName = str_replace(' ', '_', $request->author_name) . "." . $ext;
            // dd($fileName);
            // 
            $author_image->move($author_path, $fileName);
            $author_image = $fileName;
        }
        $validated['author_image'] = $author_image;
        // 
        $detail->update($validated);
        return redirect()->route('artikelAuthor')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Author::find($id);
        if ($detail->delete()) {
             $path = public_path( $detail->author_path . '/' . $detail->author_image);
            if (File::exists($path)) {
                File::delete($path);
            }
            return redirect()->route('artikelAuthor')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->route('artikelAuthor')->with('error', 'Data gagal dihapus');
        }
    }
}
