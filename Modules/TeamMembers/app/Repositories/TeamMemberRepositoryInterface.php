<?php

namespace Modules\TeamMembers\App\Repositories;

interface TeamMemberRepositoryInterface
{
    public function getByTeam($teamId);

    public function syncMembers($teamId, array $userIds);

}