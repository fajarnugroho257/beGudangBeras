<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Heading;

class Menu extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'app_menu';
    protected $primaryKey = 'menu_id';
    protected $fillable = ['menu_id', 'app_heading_id', 'menu_name', 'menu_url', 'menu_parent'];

    public function heading()
    {
        return $this->belongsTo(Heading::class, 'app_heading_id', 'app_heading_id');
    }

    public function role_menu()
    {
        return $this->hasMany(Role_menu::class, 'menu_id', 'menu_id');
    }
}
