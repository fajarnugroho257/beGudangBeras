<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $fillable = ['karyawan_nama'];

    public function pengkrimanBebanKaryawan()
    {
        return $this->hasMany(PengirimanBebanKaryawan::class, 'karyawan_id', 'id');
    }
}
