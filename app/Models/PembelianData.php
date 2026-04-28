<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianData extends Model
{
    use HasFactory;

    protected $table = 'pembelian_data';

    protected $fillable = [
        'pembelian_id',
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

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}
