<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Modules\Tenant\App\Traits\BelongsToTenant;
use Modules\User\Database\Factories\UserFactory;

class User extends Authenticatable
{
    use HasApiTokens, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'role' => 'customer',
        'is_active' => true,
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (isset($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    protected static function newFactory()
{
    return UserFactory::new();
}
}
