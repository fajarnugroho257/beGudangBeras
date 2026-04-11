<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengirimanData extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_data';

    protected $fillable = ['pengiriman_id', 'data_merek', 'data_barang', 'data_box', 'data_box_rupiah', 'data_tonase', 'data_estimasi', 'data_datas', 'data_st', 'data_harga', 'data_total'];

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id', 'id');
    }
}
