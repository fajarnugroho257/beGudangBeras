<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_kardus extends Model
{
    use HasFactory;
    protected $table = 'data_kardus';
    protected $fillable = ['nama', 'jumlah', 'harga'];
}
