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
        'pembelian_tgl',
    ];

    protected $casts = [
        'pembelian_tgl' => 'date',
    ];

    public function suplier()
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'id');
    }

    public function pembelianData()
    {
        return $this->hasMany(PembelianData::class, 'pembelian_id', 'id');
    }
}
