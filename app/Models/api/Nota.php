<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nota extends Model
{
    use HasFactory;
    protected $table = 'nota';
    protected $primaryKey = 'nota_id';// Tentukan tipe data primary key (string)
    protected $keyType = 'string';
    protected $fillable = ['nota_id', 'nota_st'];

    public function nota_data(): HasMany
    {
        return $this->HasMany(NotaData::class, 'nota_id', 'nota_id');
    }

    public function nota_bayar(): HasMany
    {
        return $this->HasMany(NotaBayar::class, 'nota_id', 'nota_id');
    }


}
