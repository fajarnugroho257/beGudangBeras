<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suplier extends Model
{
    use HasFactory;

    protected $table = 'suplier';

    protected $fillable = ['suplier_nama', 'alamat', 'no_hp', 'suplier_tgl', 'suplier_nota_st'];

    //
    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'suplier_id', 'id');
    }

    public function nota_data(): HasMany
    {
        return $this->HasMany(NotaData::class, 'suplier_id', 'id');
    }
}
