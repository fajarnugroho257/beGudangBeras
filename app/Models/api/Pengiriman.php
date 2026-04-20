<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';

    protected $fillable = ['nama_pembeli', 'pengiriman_tgl', 'uang_muka', 'status'];

    protected $casts = [
        'pengiriman_tgl' => 'date',
        'status' => 'string',
    ];

    public function bebanPengiriman(): HasMany
    {
        return $this->hasMany(BebanPengiriman::class, 'pengiriman_id', 'id');
    }

    public function pengirimanBebanKaryawan(): HasMany
    {
        return $this->hasMany(PengirimanBebanKaryawan::class, 'pengiriman_id', 'id');
    }

    public function pengirimanBebanLain(): HasMany
    {
        return $this->hasMany(PengirimanBebanLain::class, 'pengiriman_id', 'id');
    }

    public function pengirimanData(): HasMany
    {
        return $this->hasMany(PengirimanData::class, 'pengiriman_id', 'id');
    }
}
