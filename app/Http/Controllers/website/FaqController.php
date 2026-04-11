<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\TokoPusat;
use App\Models\website\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Faq';
        $rs_data = Faq::get();
        $data['rs_data'] = $rs_data;
        return view('website.faq.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Faq';
        return view('website.faq.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'desc' => 'required',
        ]);
        Faq::create($validated);
        return redirect()->route('addFaq')->with('success', 'Data berhasil disimpan');
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
        $data['title'] = 'Ubah Faq';
        $detail = Faq::find($id);
        if (empty($detail)) {
            return redirect()->route('faq')->with('error', 'Data tidak ditemukan');
        }
        $data['detail'] = $detail;
        return view('website.faq.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'desc' => 'required',
        ]);
        $detail = Faq::find($id);
        if (empty($detail)) {
            return redirect()->route('faq')->with('error', 'Data tidak ditemukan');
        }
        $detail->update($validated);
        return redirect()->route('editFaq', $id)->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Faq::find($id);
        if (empty($detail)) {
            return redirect()->route('faq')->with('error', 'Data tidak ditemukan');
        }
        $detail->delete();
        return redirect()->route('faq')->with('success', 'Data berhasil dihapus');

    }
}
