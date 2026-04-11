<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengirimanBebanKaryawan extends Model
{
    use HasFactory;
    protected $table = 'pengiriman_beban_karyawan';
    protected $fillable = ['pengiriman_id', 'karyawan_id', 'beban_value', 'beban_tgl', 'beban_st'];
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }
}
