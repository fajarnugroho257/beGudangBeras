<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'suplier_id',
        'pembelian_tgl',
        'pembelian_nota_st',
    ];

    protected $casts = [
        'pembelian_tgl' => 'date',
    ];

    //
    public function suplier(): belongsTo
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'id');
    }

    public function pembelianData(): hasMany
    {
        return $this->hasMany(PembelianData::class, 'pembelian_id', 'id');
    }

    // Menambahkan Scope untuk menghitung total pembelian
    public function scopeTotalPembelian($query)
    {
        $data = $query->selectRaw('SUM(pembelian_total) AS total')->first();

        // dd($data->total);
        return $data ? $data->total : 0;
    }
}
