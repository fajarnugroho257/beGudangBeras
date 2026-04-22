<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengirimanData extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_data';

    protected $fillable = ['pengiriman_id', 'barang_id', 'supplier_id', 'data_tonase', 'data_harga', 'data_total', 'pembayaran_st'];

    protected $casts = [
        'pembayaran_st' => 'string',
    ];

    public function pengiriman(): BelongsTo
    {
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id', 'id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }

    public function suplier(): BelongsTo
    {
        return $this->belongsTo(Suplier::class, 'supplier_id', 'id');
    }

    public function operasional(): HasMany
    {
        return $this->hasMany(Operasional::class, 'pengiriman_data_id', 'id');
    }
}
