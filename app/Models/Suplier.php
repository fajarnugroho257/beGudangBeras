<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    use HasFactory;

    protected $table = 'suplier';
    protected $fillable = ['suplier_nama', 'alamat', 'no_hp', 'suplier_tgl', 'suplier_nota_st'];

    protected $casts = [
        'suplier_tgl' => 'date',
        'suplier_nota_st' => 'string',
    ];

    public function notaData()
    {
        return $this->hasMany(NotaData::class, 'suplier_id', 'id');
    }

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'suplier_id', 'id');
    }

    public function stokBarang()
    {
        return $this->hasMany(StokBarang::class, 'suplier_id', 'id');
    }
}
