<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $fillable = ['nama', 'tipe', 'is_process'];

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

    public function processInputData()
    {
        return $this->hasMany(
            ProcessInputData::class,
            'barang_id'
        );
    }

    public function processOutputData()
    {
        return $this->hasMany(
            ProcessOutputData::class,
            'barang_id'
        );
    }
}
