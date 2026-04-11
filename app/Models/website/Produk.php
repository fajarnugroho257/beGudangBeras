<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory, Sluggable;
    protected $table = 'produk';
    protected $fillable = ['slug', 'produk_nama', 'produk_harga', 'produk_rating', 'produk_short_desc', 'produk_desc', 'produk_path', 'produk_image'];
    
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'produk_nama',
                'onUpdate' => true,
            ]
        ];
    }
}
