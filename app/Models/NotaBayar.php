<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaBayar extends Model
{
    use HasFactory;

    protected $table = 'nota_bayar';
    protected $fillable = ['nota_id', 'bayar_value'];

    public function nota()
    {
        return $this->belongsTo(Nota::class, 'nota_id', 'id');
    }
}
