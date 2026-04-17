<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    use HasFactory;

    protected $table = 'stok_barang';
    protected $fillable = ['barang_id', 'suplier_id', 'stok'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }

    public function suplier()
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'id');
    }
}
