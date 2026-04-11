<?php

namespace App\Http\Controllers\menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Heading;

class headingAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Data Heading Aplikasi';
        $data['rs_head'] = Heading::paginate(5);
        return view('heading.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Heading Aplikasi';
        return view('heading.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'app_heading_name' => 'required',
            // 'app_heading_icon' => 'required',
        ]);
        $app_heading_id = $this->last_heading_id();
        if ($app_heading_id) {
            Heading::create([
                'app_heading_id' => $app_heading_id,
                'app_heading_name' => $request->app_heading_name,
                'app_heading_icon' => $request->app_heading_icon,
            ]);
        }
        //redirect
        return redirect()->route('headingApp')->with('success', 'Data berhasil disimpan');
    }

    function last_heading_id() {
        // get last data
        $last_data = Heading::select('app_heading_id')->orderBy('app_heading_id', 'DESC')->first();
        $last_number = substr($last_data->app_heading_id, 1, 6) + 1;
        $zero = '';
        for ($i=strlen($last_number); $i <=3; $i++) {
            $zero .= '0';
        }
        $new_id = 'H'.$zero.$last_number;
        //
        return $new_id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $app_heading_id)
    {
        $detail = Heading::where('app_heading_id', $app_heading_id)->first();
        if (empty($detail)) {
            return redirect()->route('headingApp')->with('error', 'Data tidak ditemukan');
        }
        $data['detail'] = $detail;
        $data['title'] = 'Update Data Heading Aplikasi';
        return view('heading.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $app_heading_id)
    {
        $detail = Heading::where('app_heading_id', $app_heading_id)->first();
        if (empty($detail)) {
            return redirect()->route('headingApp')->with('error', 'Data tidak ditemukan');
        }
        $request->validate([
            'app_heading_name' => 'required',
            'app_heading_icon' => 'required'
        ]);
        $detail->app_heading_name = $request->app_heading_name;
        $detail->app_heading_icon = $request->app_heading_icon;
        if($detail->save()){
            return redirect()->route('updateHeadingApp', [$app_heading_id])->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->route('headingApp')->with('error', 'Gagal update date');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $app_heading_id)
    {
        $detail = Heading::where('app_heading_id', $app_heading_id)->first();
        // dd($detail);
        if (empty($detail)) {
            return redirect()->route('headingApp')->with('error', 'Data tidak ditemukan');
        }
        if($detail->delete()){
            return redirect()->route('headingApp')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->route('headingApp')->with('error', 'Data gagal dihapus');
        }
    }
}
