<?php

namespace App\Models\website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Why extends Model
{
    use HasFactory;
    protected $table = 'why_choose';
    protected $fillable = ['why_image', 'why_path', 'why_title', 'why_desc'];

}
