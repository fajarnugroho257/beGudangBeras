<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';

    protected $fillable = ['nama_pembeli', 'pengiriman_tgl', 'uang_muka', 'status'];

    protected $casts = [
        'pengiriman_tgl' => 'date',
        'status' => 'string',
    ];

    protected $visible = [
        'id',
        'nama_pembeli',
        'pengiriman_tgl',
        'uang_muka',
        'status',
        'pengirimanData',
        'pengiriman_data_sum_data_total',
        'pengiriman_data_sum_data_tonase',
    ];

    public function bebanPengiriman()
    {
        return $this->hasMany(BebanPengiriman::class, 'pengiriman_id', 'id');
    }

    public function pengirimanBebanKaryawan()
    {
        return $this->hasMany(PengirimanBebanKaryawan::class, 'pengiriman_id', 'id');
    }

    public function pengirimanBebanLain()
    {
        return $this->hasMany(PengirimanBebanLain::class, 'pengiriman_id', 'id');
    }

    public function pengirimanData()
    {
        return $this->hasMany(PengirimanData::class, 'pengiriman_id', 'id');
    }
}
