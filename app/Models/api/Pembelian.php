<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'suplier_id',
        'pembayaran',
        'pembelian_nama',
        'pembelian_kotor',
        'pembelian_potongan',
        'pembelian_bersih',
        'pembelian_harga',
        'pembelian_total',
    ];

    //
    public function suplier(): belongsTo
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'id');
    }

    // Menambahkan Scope untuk menghitung total pembelian
    public function scopeTotalPembelian($query)
    {
        $data = $query->selectRaw('SUM(pembelian_total) AS total')->first();

        // dd($data->total);
        return $data ? $data->total : 0;
    }
}
