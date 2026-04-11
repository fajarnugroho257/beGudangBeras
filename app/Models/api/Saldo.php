<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $table = 'saldo_by_day';

    protected $fillable = ['saldo_val', 'saldo_tagihan', 'saldo_sisa'];
}
