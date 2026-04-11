<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suplier extends Model
{
    use HasFactory;

    protected $table = 'suplier';

    protected $fillable = ['suplier_nama', 'suplier_tgl'];

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
