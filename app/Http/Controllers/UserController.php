<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Data Pengguna Aplikasi';
        $data['rs_user'] = User::paginate(5);
        return view('user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Pengguna Aplikasi';
        $data['rs_role'] = Role::all();
        return view('user.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'name' => 'required',
            'role_id' => 'required'
        ]);
        $user_id = $this->lat_user_id();
        if ($user_id) {
            User::create([
                'user_id' => $user_id,
                'name' => $request->name,
                'role_id' => $request->role_id,
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);
        }
        //redirect
        return redirect()->route('tambahUser')->with('success', 'Data berhasil disimpan');
    }

    function lat_user_id() {
        // get last user id
        $last_user = User::select('user_id')->orderBy('user_id', 'DESC')->first();
        $last_number = substr($last_user->user_id, 1, 6) + 1;
        $zero = '';
        for ($i=strlen($last_number); $i <=3; $i++) {
            $zero .= '0';
        }
        $new_id = 'U'.$zero.$last_number;
        //
        return $new_id;
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
    public function edit(string $user_id)
    {
        $detail = User::where('user_id', $user_id)->first();
        if (empty($detail)) {
            return redirect()->route('dataUser')->with('error', 'Data tidak ditemukan');
        }
        $data['rs_role'] = Role::all();
        $data['title'] = 'Ubah Data Pengguna Aplikasi';
        $data['detail'] = $detail;
        return view('user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $user_id)
    {
        $detail = User::where('user_id', $user_id)->first();
        if (empty($detail)) {
            return redirect()->route('dataUser')->with('error', 'Data tidak ditemukan');
        }
        $request->validate([
            'username' => 'required|unique:users,username,'.$user_id.',user_id',
            'password' => 'nullable|min:6',
            'name' => 'required',
            'role_id' => 'required'
        ]);
        $detail->name = $request->name;
        $detail->username = $request->username;
        $detail->role_id = $request->role_id;
        if (!empty($request->password)) {
            $detail->password = bcrypt($request->password);
        }
        if($detail->save()){
            return redirect()->route('UpdateUser', [$user_id])->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->route('dataUser')->with('error', 'Gagal update date');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user_id)
    {
        $detail = User::where('user_id', $user_id)->first();
        // dd($detail);
        if (empty($detail)) {
            return redirect()->route('dataUser')->with('error', 'Data tidak ditemukan');
        }
        if($detail->delete()){
            return redirect()->route('dataUser')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->route('dataUser')->with('error', 'Data gagal dihapus');
        }
    }
}
