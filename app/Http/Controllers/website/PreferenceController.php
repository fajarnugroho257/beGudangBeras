<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Pref;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Web Preference';
        $rs_pref = Pref::whereIn('pref_name', ['no_wa', 'no_wa_zero', 'alamat', 'cta_title', 'cta_desc'])->get();
        $data['rs_pref'] = $rs_pref;
        // dd($data);
        return view('website.preference.index', $data);
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
        $data['title'] = 'Ubah Web Preference';
        $detail = Pref::where('id', $id)->first();
        if (empty($detail)) {
            return redirect()->route('preference')->with('error', 'Data tidak ditemukan');
        }
        $data['detail'] = $detail;
        return view('website.preference.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'pref_value' => 'required',
        ]);
        $detail = Pref::where('id', $id)->first();
        if (empty($detail)) {
            return redirect()->route('preference')->with('error', 'Data tidak ditemukan');
        }
        $detail->update($validated);
        return redirect()->route('editPreference', ['id' => $detail->id])->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
