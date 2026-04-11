<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk_image extends Model
{
    use HasFactory;
    protected $table = 'produk_image';
    protected $fillable = ['produk_id', 'path', 'image'];
}
