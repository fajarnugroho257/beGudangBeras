<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaData extends Model
{
    use HasFactory;

    protected $table = 'nota_data';
    protected $fillable = ['nota_id', 'suplier_id'];

    public function nota()
    {
        return $this->belongsTo(Nota::class, 'nota_id', 'id');
    }

    public function suplier()
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'id');
    }
}
