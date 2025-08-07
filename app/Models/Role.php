<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_name',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(UserAccount::class, 'role_user_account');
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
