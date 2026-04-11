<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;
    protected $table = 'artikel_author';
    protected $fillable = ['author_name', 'author_desc', 'author_path', 'author_image'];

    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'kategori_id', 'id');
    }
    
}
