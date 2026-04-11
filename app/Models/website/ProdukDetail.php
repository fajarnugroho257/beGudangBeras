<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukDetail extends Model
{
    use HasFactory;
    protected $table = 'produk_detail';
    protected $fillable = ['produk_id', 'detail_jenis', 'detail_title', 'detail_desc'];
    
}
