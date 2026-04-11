<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pref extends Model
{
    use HasFactory;
    protected $table = 'preference';
    protected $fillable = ['pref_name', 'pref_value'];
}
