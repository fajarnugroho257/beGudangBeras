<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BebanPengiriman extends Model
{
    use HasFactory;

    protected $table = 'beban_pengiriman';
    protected $fillable = ['pengiriman_id', 'nama', 'jumlah'];

    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id', 'id');
    }
}
