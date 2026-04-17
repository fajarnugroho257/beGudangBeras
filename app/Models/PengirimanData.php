<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanData extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_data';
    protected $fillable = ['pengiriman_id', 'barang_id', 'data_tonase', 'data_harga', 'data_total', 'pembayaran_st'];

    protected $casts = [
        'pembayaran_st' => 'string',
    ];

    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }

    public function operasional()
    {
        return $this->hasMany(Operasional::class, 'pengiriman_data_id', 'id');
    }
}
