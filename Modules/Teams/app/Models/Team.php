<?php
namespace Modules\Teams\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Tenant\App\Traits\BelongsToTenant;


class Team extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'is_active',
        'max_concurrent_bookings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_concurrent_bookings' => 'integer',
    ];

    protected $attributes = [
        'is_active' => true,
        'max_concurrent_bookings' => 1,
    ];


    public function members()
    {
        return $this->belongsToMany(
            \Modules\User\App\Models\User::class,
            'team_members',
            'team_id',
            'user_id'
        )->withPivot('joined_at')->withTimestamps();
    }


    public function availability()
    {
        return $this->hasMany(\Modules\TeamAvailability\App\Models\TeamAvailability::class);
    }
    
    public function activeAvailability()
    {
        return $this->hasMany(\Modules\TeamAvailability\App\Models\TeamAvailability::class)
                    ->where('is_active', true)
                    ->orderBy('day_of_week')
                    ->orderBy('start_time');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


}
