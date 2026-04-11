<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $fillable = ['karyawan_nama'];
    public function pengiriman_beban_karyawan(): HasMany
    {
        return $this->HasMany(PengirimanBebanKaryawan::class);
    }

}
