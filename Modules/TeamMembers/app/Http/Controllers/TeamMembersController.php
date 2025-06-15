<?php

namespace Modules\TeamMembers\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\TeamMembers\App\Services\TeamMemberServiceInterface;
use Modules\TeamMembers\App\Http\Requests\SyncTeamMembersRequest;
use Modules\TeamMembers\App\Transformers\TeamMemberResource;
use Modules\Teams\App\Models\Team;

class TeamMembersController
{
    protected $teamMemberService;

    public function __construct(TeamMemberServiceInterface $teamMemberService)
    {
        $this->teamMemberService = $teamMemberService;
    }

    /**
     * Get all members of a team
     */
    public function index($teamId)
    {
        try {
            $team = Team::findOrFail($teamId);
            
            $members = $this->teamMemberService->getTeamMembers($teamId);
            return response()->json([
                'message' => 'Team members retrieved successfully',
                'data' => TeamMemberResource::collection($members),
                'total' => $members->count()
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Team not found'], 404);
        }
    }



    /**
     * Sync team members - set team membership to exactly match provided user IDs
     * This is the main method you should use for updating team membership
     * 
     * POST /teams/{team}/members/sync
     * Body: { "user_ids": [1, 2, 3] } or { "user_ids": [] } to remove all
     */
    public function sync(SyncTeamMembersRequest $request, $teamId)
    {
        try {
            $team = Team::findOrFail($teamId);
            
            $userIds = $request->input('user_ids', []);
            
            $result = $this->teamMemberService->syncTeamMembers($teamId, $userIds);
            
            $message = $this->buildSyncMessage($result);
            
            return response()->json([
                'message' => $message,
                'data' => TeamMemberResource::collection($result['members']),
                'summary' => [
                    'total_members' => $result['total_members'],
                    'added' => $result['added_count'],
                    'removed' => $result['removed_count'],
                    'added_users' => $result['added_users'],
                    'removed_users' => $result['removed_users']
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Team not found'], 404);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Build a descriptive message for sync operation
     */
    private function buildSyncMessage(array $result): string
    {
        $parts = [];
        
        if ($result['added_count'] > 0) {
            $parts[] = "added {$result['added_count']} member(s)";
        }
        
        if ($result['removed_count'] > 0) {
            $parts[] = "removed {$result['removed_count']} member(s)";
        }
        
        if (empty($parts)) {
            return "Team membership is already up to date";
        }
        
        $action = implode(' and ', $parts);
        return "Successfully {$action}. Team now has {$result['total_members']} member(s)";
    }
}