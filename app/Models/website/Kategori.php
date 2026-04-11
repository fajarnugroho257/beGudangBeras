<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'artikel_kategori';
    protected $fillable = ['name', 'color', 'stastu'];

    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'kategori_id', 'id');
    }

}
