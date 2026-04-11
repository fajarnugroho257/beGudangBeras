<?php

namespace App\Http\Controllers\menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Heading;

class menuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Data Menu Aplikasi';
        $data['rs_menu'] = Menu::paginate(10);
        return view('menu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Heading Aplikasi';
        $data['rs_head'] = Heading::all();
        return view('menu.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'app_heading_id' => 'required',
            'menu_name' => 'required',
            'menu_url' => 'required',
        ]);
        $menu_id = $this->last_menu_id();
        if ($menu_id) {
            Menu::create([
                'menu_id' => $menu_id,
                'app_heading_id' => $request->app_heading_id,
                'menu_name' => $request->menu_name,
                'menu_url' => $request->menu_url,
                'menu_parent' => '0'
            ]);
        }
        //redirect
        return redirect()->route('menuApp')->with('success', 'Data berhasil disimpan');
    }

    function last_menu_id() {
        // get last data
        $last_data = Menu::select('menu_id')->orderBy('menu_id', 'DESC')->first();
        $last_number = substr($last_data->menu_id, 1, 6) + 1;
        $zero = '';
        for ($i=strlen($last_number); $i <=3; $i++) {
            $zero .= '0';
        }
        $new_id = 'M'.$zero.$last_number;
        //
        return $new_id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $menu_id)
    {
        $detail = Menu::where('menu_id', $menu_id)->first();
        if (empty($detail)) {
            return redirect()->route('menuApp')->with('error', 'Data tidak ditemukan');
        }
        $data['detail'] = $detail;
        $data['title'] = 'Update Data Menu Aplikasi';
        $data['rs_head'] = Heading::all();
        return view('menu.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $menu_id)
    {
        $detail = Menu::where('menu_id', $menu_id)->first();
        if (empty($detail)) {
            return redirect()->route('menuApp')->with('error', 'Data tidak ditemukan');
        }
        $request->validate([
            'app_heading_id' => 'required',
            'menu_name' => 'required',
            'menu_url' => 'required',
        ]);
        $detail->app_heading_id = $request->app_heading_id;
        $detail->menu_name = $request->menu_name;
        $detail->menu_url = $request->menu_url;
        if($detail->save()){
            return redirect()->route('updateMenuApp', [$menu_id])->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->route('menuApp')->with('error', 'Gagal update date');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
