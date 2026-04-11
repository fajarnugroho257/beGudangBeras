<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heading extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'app_heading_menu';
    protected $primaryKey = 'app_heading_id';
    protected $fillable = ['app_heading_id', 'app_heading_name', 'app_heading_icon'];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'app_heading_id', 'app_heading_id');
    }
}
