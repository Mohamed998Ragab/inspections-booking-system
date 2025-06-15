<?php

namespace Modules\TeamMembers\App\Services;

use Modules\TeamMembers\App\Repositories\TeamMemberRepositoryInterface;
use Modules\TeamMembers\App\Services\TeamMemberServiceInterface;
use Modules\User\App\Models\User;

class TeamMemberService implements TeamMemberServiceInterface
{
    protected $teamMemberRepository;

    public function __construct(TeamMemberRepositoryInterface $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    public function getTeamMembers($teamId)
    {
        return $this->teamMemberRepository->getByTeam($teamId);
    }



    /**
     * Sync team members - set team membership to exactly match provided user IDs
     * Empty array will remove all members
     * 
     * @param int $teamId
     * @param array $userIds
     * @return array
     */
    public function syncTeamMembers($teamId, array $userIds)
    {
        // If empty array provided, we'll sync to empty (remove all members)
        if (empty($userIds)) {
            $result = $this->teamMemberRepository->syncMembers($teamId, []);
            return $result;
        }

        // Validate users exist and are active in current tenant
        $validUsers = User::whereIn('id', $userIds)
            ->where('is_active', true)
            ->get();

        $validUserIds = $validUsers->pluck('id')->toArray();
        
        // Check if some user IDs were invalid
        $invalidUserIds = array_diff($userIds, $validUserIds);
        if (!empty($invalidUserIds)) {
            throw new \InvalidArgumentException('Some user IDs are invalid or users are inactive: ' . implode(', ', $invalidUserIds));
        }

        return $this->teamMemberRepository->syncMembers($teamId, $validUserIds);
    }
}