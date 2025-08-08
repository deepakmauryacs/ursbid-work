<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Module;
use App\Models\RolePermission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'parent_id',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasModulePermission(string $moduleSlug, string $permission): bool
    {
        if ($this->user_type == 1 &&  $this->parent_id == 0) {
            return true;
        }

        $module = Module::where('slug', $moduleSlug)->first();
        if (!$module) {
            return false;
        }

        $roleIds = $this->roles()->pluck('roles.id')->toArray();
        if (empty($roleIds)) {
            return false;
        }

        return RolePermission::whereIn('role_id', $roleIds)
            ->where('module_id', $module->id)
            ->where($permission, 1)
            ->exists();
    }
}
