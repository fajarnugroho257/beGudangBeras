<?php

namespace App\Models\api;

use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaData extends Model
{
    use HasFactory;

    protected $table = 'nota_data';

    protected $fillable = ['nota_id', 'pembelian_id'];

    public function nota(): BelongsTo
    {
        return $this->belongsTo(Nota::class, 'nota_id', 'nota_id');
    }

    public function pembelian(): belongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'id');
    }
}
