<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NotaData extends Model
{
    use HasFactory;
    protected $table = 'nota_data';
    protected $fillable = ['nota_id', 'suplier_id'];
    protected $primaryKey = 'id';
    public function nota(): BelongsTo
    {
        return $this->belongsTo(Nota::class, 'nota_id', 'nota_id');
    }

    public function suplier(): belongsTo
    {
        return $this->belongsTo(Suplier::class, 'suplier_id', 'suplier_id');
    }
}
