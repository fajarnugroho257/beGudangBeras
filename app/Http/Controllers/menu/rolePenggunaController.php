<?php

namespace App\Http\Controllers\menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class rolePenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Data Role Pengguna Aplikasi';
        $data['rs_role'] = Role::paginate(10);
        return view('rolePengguna.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Role Pengguna Aplikasi';
        return view('rolePengguna.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
        ]);
        $role_id = $this->last_role_id();
        if ($role_id) {
            Role::create([
                'role_id' => $role_id,
                'role_name' => $request->role_name,
            ]);
        }
        //redirect
        return redirect()->route('rolePengguna')->with('success', 'Data berhasil disimpan');
    }

    function last_role_id() {
        // get last data
        $last_data = Role::select('role_id')->orderBy('role_id', 'DESC')->first();
        $last_number = substr($last_data->role_id, 1, 6) + 1;
        $zero = '';
        for ($i=strlen($last_number); $i <=3; $i++) {
            $zero .= '0';
        }
        $new_id = 'R'.$zero.$last_number;
        //
        return $new_id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $role_id)
    {
        $detail = Role::where('role_id', $role_id)->first();
        if (empty($detail)) {
            return redirect()->route('rolePengguna')->with('error', 'Data tidak ditemukan');
        }
        $data['detail'] = $detail;
        $data['title'] = 'Update Data Role Pengguna Aplikasi';
        return view('rolePengguna.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $role_id)
    {
        $detail = Role::where('role_id', $role_id)->first();
        if (empty($detail)) {
            return redirect()->route('rolePengguna')->with('error', 'Data tidak ditemukan');
        }
        $request->validate([
            'role_name' => 'required',
        ]);
        $detail->role_name = $request->role_name;
        if($detail->save()){
            return redirect()->route('updateRolePengguna', [$role_id])->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->route('rolePengguna')->with('error', 'Gagal update date');
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
