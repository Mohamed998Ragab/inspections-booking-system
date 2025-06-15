<?php

namespace Modules\TeamAvailability\App\Repositories;

use Modules\TeamAvailability\App\Models\TeamAvailability;

interface TeamAvailabilityRepositoryInterface
{
    public function getByTeam($teamId);
    public function getActiveByTeam($teamId);
    public function getByTeamAndDay($teamId, $dayOfWeek);
    public function create(array $data);
    public function update(TeamAvailability $availability, array $data);
    public function delete(TeamAvailability $availability);
    public function syncTeamAvailability($teamId, array $availabilityData);
}