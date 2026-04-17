<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'nota';
    protected $fillable = ['nota_st'];

    protected $casts = [
        'nota_st' => 'string',
    ];

    public function notaBayar()
    {
        return $this->hasMany(NotaBayar::class, 'nota_id', 'id');
    }

    public function notaData()
    {
        return $this->hasMany(NotaData::class, 'nota_id', 'id');
    }
}
