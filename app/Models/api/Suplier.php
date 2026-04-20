<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suplier extends Model
{
    use HasFactory;

    protected $table = 'suplier';

        protected $fillable = ['suplier_nama', 'alamat', 'no_hp'];

        protected $casts = [
            //
        ];

    public function notaData(): HasMany
    {
        return $this->hasMany(NotaData::class, 'suplier_id', 'id');
    }

    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'suplier_id', 'id');
    }

    public function stokBarang(): HasMany
    {
        return $this->hasMany(StokBarang::class, 'suplier_id', 'id');
    }
}
