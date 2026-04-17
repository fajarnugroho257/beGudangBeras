<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operasional extends Model
{
    use HasFactory;

    protected $table = 'operasional';
    protected $fillable = ['pengiriman_data_id', 'ops_nama', 'ops_jumlah', 'ops_total'];

    public function pengirimanData()
    {
        return $this->belongsTo(PengirimanData::class, 'pengiriman_data_id', 'id');
    }
}
