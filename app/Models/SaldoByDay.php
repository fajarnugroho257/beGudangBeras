<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoByDay extends Model
{
    use HasFactory;

    protected $table = 'saldo_by_day';
    protected $fillable = ['saldo_val', 'saldo_tagihan', 'saldo_sisa'];
}
