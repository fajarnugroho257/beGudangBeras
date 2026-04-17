<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanBebanKaryawan extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_beban_karyawan';
    protected $fillable = ['pengiriman_id', 'karyawan_id', 'beban_value', 'beban_st', 'beban_tgl'];

    protected $casts = [
        'beban_tgl' => 'date',
        'beban_st' => 'string',
    ];

    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id', 'id');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
}
