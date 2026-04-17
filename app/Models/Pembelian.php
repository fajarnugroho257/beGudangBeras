<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $fillable = [
        'suplier_id',
        'pembayaran',
        'barang_id',
        'pembelian_kotor',
        'pembelian_potongan',
        'pembelian_bersih',
        'pembelian_harga',
        'pembelian_total',
        'pembelian_nota_st',
    ];

    protected $casts = [
        'pembayaran' => 'string',
        'pembelian_nota_st' => 'string',
    ];

    public function suplier()
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}
