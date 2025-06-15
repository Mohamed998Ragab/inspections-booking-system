<?php

namespace Modules\Tenant\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Tenant\Database\Factories\TenantFactory;

class Tenant extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'domain', 'is_active'];

    public function users()
    {
        return $this->hasMany(\Modules\User\App\Models\User::class);
    }

    protected static function newFactory()
{
    return TenantFactory::new();
}
}