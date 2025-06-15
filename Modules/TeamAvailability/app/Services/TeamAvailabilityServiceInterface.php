<?php

namespace Modules\TeamAvailability\App\Services;

interface TeamAvailabilityServiceInterface
{
    public function getTeamAvailability($teamId);
    public function getActiveTeamAvailability($teamId);
    public function getTeamAvailabilityForDay($teamId, $dayOfWeek);
    public function createAvailability(array $data);
    public function updateAvailability($id, array $data);
    public function deleteAvailability($id);
    public function syncTeamAvailability($teamId, array $availabilityData);
}