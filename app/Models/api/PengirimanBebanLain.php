<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanBebanLain extends Model
{
    use HasFactory;
    protected $table = 'pengiriman_beban_lain';
    protected $fillable = ['pengiriman_id', 'beban_nama', 'beban_value', 'beban_tgl'];
}
