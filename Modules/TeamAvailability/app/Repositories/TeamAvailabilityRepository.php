<?php

namespace Modules\TeamAvailability\App\Repositories;

use Modules\TeamAvailability\App\Models\TeamAvailability;
use Modules\TeamAvailability\App\Repositories\TeamAvailabilityRepositoryInterface;

class TeamAvailabilityRepository implements TeamAvailabilityRepositoryInterface
{
    public function getByTeam($teamId)
    {
        return TeamAvailability::forTeam($teamId)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }

    public function getActiveByTeam($teamId)
    {
        return TeamAvailability::forTeam($teamId)
            ->active()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }

    public function getByTeamAndDay($teamId, $dayOfWeek)
    {
        return TeamAvailability::forTeam($teamId)
            ->forDay($dayOfWeek)
            ->active()
            ->orderBy('start_time')
            ->get();
    }

    public function create(array $data)
    {
        return TeamAvailability::create($data);
    }

    public function update(TeamAvailability $availability, array $data)
    {
        $availability->update($data);
        return $availability;
    }

    public function delete(TeamAvailability $availability)
    {
        $availability->delete();
    }

    /**
     * Sync team availability - replace all availability for a team
     * @param int $teamId
     * @param array $availabilityData - Array of availability records
     * @return array
     */
    public function syncTeamAvailability($teamId, array $availabilityData)
    {
        // Delete existing availability
        $deletedCount = TeamAvailability::where('team_id', $teamId)->delete();
        
        $createdCount = 0;
        $created = [];
        
        // Create new availability records
        foreach ($availabilityData as $data) {
            $data['team_id'] = $teamId;
            $availability = $this->create($data);
            $created[] = $availability;
            $createdCount++;
        }
        
        return [
            'deleted_count' => $deletedCount,
            'created_count' => $createdCount,
            'availability' => $created
        ];
    }
}