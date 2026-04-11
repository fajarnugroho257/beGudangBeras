<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->role_id;
        $data['title'] = 'Dashboard';
        // dd($user);
        return view('dashboard.dashboard', $data);
    }
}
