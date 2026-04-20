<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['nama', 'tipe'];

    protected $casts = [
        'tipe' => 'string',
    ];

    public function pembelianData(): HasMany
    {
        return $this->hasMany(PembelianData::class, 'barang_id', 'id');
    }

    public function stokBarang(): HasMany
    {
        return $this->hasMany(StokBarang::class, 'barang_id', 'id');
    }
}
