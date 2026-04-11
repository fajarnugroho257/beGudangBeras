<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\baseController\AppController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // public function __construct(AppController $AppController)
    // {
    //     $this->container = $AppController;
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Login';
        return view('login', $data);
    }

    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
        // dd($request->all());
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // return redirect()->intended('dashboard');
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Data tidak ditemukan']);
    }

    public function logOut(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
