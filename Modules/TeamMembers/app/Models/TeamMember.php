<?php

namespace Modules\TeamMembers\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Teams\App\Models\Team;
use Modules\Tenant\App\Traits\BelongsToTenant;
use Modules\User\App\Models\User;

class TeamMember extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id', 
        'team_id',
        'user_id',
        'joined_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Custom scope to filter by tenant through team relationship
    public function scopeForTenant($query, $tenantId)
    {
        return $query->whereHas('team', function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId);
        });
    }
}
