<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Artikel extends Model
{
    use HasFactory, Sluggable;
    protected $table = 'artikel';
    protected $fillable = ['author_id', 'kategori_id', 'artikel_title', 'artikel_slug', 'artikel_desc', 'artikel_views', 'artikel_date', 'artikel_path', 'artikel_name'];

    public function sluggable(): array
    {
        return [
            'artikel_slug' => [
                'source' => 'artikel_title',
                'onUpdate' => true,
            ]
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

}
