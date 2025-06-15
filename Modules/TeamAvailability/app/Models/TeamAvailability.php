<?php

namespace Modules\TeamAvailability\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Teams\App\Models\Team;

class TeamAvailability extends Model
{
    use HasFactory;

    protected $table = 'team_availability';

    protected $fillable = [
        'team_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    // Relationships
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    // Custom scope for tenant isolation through team relationship
    public function scopeForCurrentTenant($query)
    {
        $tenantContext = app(\Modules\Tenant\App\Services\TenantContextService::class);
        $currentTenant = $tenantContext->getCurrentTenant();
        
        if ($currentTenant) {
            return $query->whereHas('team', function ($q) use ($currentTenant) {
                $q->where('tenant_id', $currentTenant);
            });
        }
        
        return $query;
    }

    // Helper methods
    public function getDayName()
    {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];
        
        return $days[$this->day_of_week] ?? 'Unknown';
    }

    public function getFormattedTimeRange()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }
}