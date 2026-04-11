<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'app_role';
    protected $primaryKey = 'role_id';
    protected $fillable = ['role_id', 'role_name'];

    public function user()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
