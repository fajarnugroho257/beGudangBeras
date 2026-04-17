<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['nama', 'tipe', 'jenis'];

    protected $casts = [
        'tipe' => 'string',
    ];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'barang_id', 'id');
    }

    public function stokBarang()
    {
        return $this->hasMany(StokBarang::class, 'barang_id', 'id');
    }
}
