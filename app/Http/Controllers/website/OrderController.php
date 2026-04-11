<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\website\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Cara order';
        $rs_data = Order::get();
        $data['rs_data'] = $rs_data;
        return view('website.order.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Cara order';
        return view('website.order.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'urut' => 'required',
            'title' => 'required',
            'desc' => 'required',
        ]);
        Order::create($validated);
        return redirect()->route('addOrder')->with('success', 'Data berhasil disimpan');
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
        $data['title'] = 'Ubah Cara order';
        $detail = Order::find($id);
        if (empty($detail)) {
            return redirect()->route('caraOrder')->with('error', 'Data tidak ditemukan');
        }
        $data['detail'] = $detail;
        return view('website.order.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'urut' => 'required',
            'title' => 'required',
            'desc' => 'required',
        ]);
        $detail = Order::find($id);
        if (empty($detail)) {
            return redirect()->route('caraOrder')->with('error', 'Data tidak ditemukan');
        }
        $detail->update($validated);
        return redirect()->route('editOrder', $id)->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Order::find($id);
        if (empty($detail)) {
            return redirect()->route('caraOrder')->with('error', 'Data tidak ditemukan');
        }
        $detail->delete();
        return redirect()->route('caraOrder')->with('success', 'Data berhasil dihapus');

    }
}
