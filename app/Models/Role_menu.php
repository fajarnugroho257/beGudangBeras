<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Models\Role;

class Role_menu extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'app_role_menu';
    protected $primaryKey = 'role_menu_id';
    protected $fillable = ['role_menu_id', 'menu_id', 'role_id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
