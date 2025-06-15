<?php

namespace Modules\TeamMembers\App\Services;

interface TeamMemberServiceInterface
{
    public function getTeamMembers($teamId);

    public function syncTeamMembers($teamId, array $userIds);
}