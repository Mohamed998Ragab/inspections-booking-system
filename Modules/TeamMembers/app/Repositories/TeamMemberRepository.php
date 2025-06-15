<?php

namespace Modules\TeamMembers\App\Repositories;

use Modules\TeamMembers\App\Models\TeamMember;
use Modules\TeamMembers\App\Repositories\TeamMemberRepositoryInterface;
use Modules\Tenant\App\Services\TenantContextService;

class TeamMemberRepository implements TeamMemberRepositoryInterface
{
    protected $tenantContext;

    public function __construct(TenantContextService $tenantContext)
    {
        $this->tenantContext = $tenantContext;
    }

    public function getByTeam($teamId)
    {
        // TenantScope automatically filters TeamMembers by current tenant
        return TeamMember::with('user')
            ->where('team_id', $teamId)
            ->orderBy('joined_at')
            ->get();
    }



    /**
     * Sync team members - set team membership to exactly match provided user IDs
     * 
     * @param int $teamId
     * @param array $userIds - Array of user IDs that should be team members
     * @return array - Summary of changes made
     */
    public function syncMembers($teamId, array $userIds)
    {
        $currentTenantId = $this->tenantContext->getCurrentTenant();
        
        // Get current team members
        $currentMembers = TeamMember::where('team_id', $teamId)
            ->pluck('user_id')
            ->toArray();
        
        // Determine what needs to be added and removed
        $toAdd = array_diff($userIds, $currentMembers);
        $toRemove = array_diff($currentMembers, $userIds);
        
        $addedCount = 0;
        $removedCount = 0;
        
        // Remove members that shouldn't be in the team anymore
        if (!empty($toRemove)) {
            $removedCount = TeamMember::where('team_id', $teamId)
                ->whereIn('user_id', $toRemove)
                ->delete();
        }
        
        // Add new members
        if (!empty($toAdd)) {
            foreach ($toAdd as $userId) {
                TeamMember::create([
                    'tenant_id' => $currentTenantId,
                    'team_id' => $teamId,
                    'user_id' => $userId,
                    'joined_at' => now(),
                ]);
                $addedCount++;
            }
        }
        
        // Get final team members
        $finalMembers = TeamMember::with('user')
            ->where('team_id', $teamId)
            ->orderBy('joined_at')
            ->get();
        
        return [
            'members' => $finalMembers,
            'added_count' => $addedCount,
            'removed_count' => $removedCount,
            'total_members' => $finalMembers->count(),
            'added_users' => $toAdd,
            'removed_users' => $toRemove
        ];
    }
}