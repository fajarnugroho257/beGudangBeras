<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaBayar extends Model
{
    use HasFactory;
    protected $table = 'nota_bayar';
    protected $primaryKey = 'id';
    protected $fillable = ['nota_id', 'bayar_value'];
    public function nota(): BelongsTo
    {
        return $this->BelongsTo(Nota::class, 'nota_id', 'nota_id');
    }
}
