<?php

namespace Modules\TeamAvailability\App\Services;

use Modules\TeamAvailability\App\Repositories\TeamAvailabilityRepositoryInterface;
use Modules\TeamAvailability\App\Services\TeamAvailabilityServiceInterface;
use Modules\TeamAvailability\App\Models\TeamAvailability;
use Modules\Teams\App\Services\TeamServiceInterface;

class TeamAvailabilityService implements TeamAvailabilityServiceInterface
{
    protected $availabilityRepository;
    protected $teamService;

    public function __construct(
        TeamAvailabilityRepositoryInterface $availabilityRepository,
        TeamServiceInterface $teamService
    ) {
        $this->availabilityRepository = $availabilityRepository;
        $this->teamService = $teamService;
    }

    public function getTeamAvailability($teamId)
    {
        // Verify team exists and user has access (through team's tenant scope)
        $this->teamService->find($teamId);
        
        return $this->availabilityRepository->getByTeam($teamId);
    }

    public function getActiveTeamAvailability($teamId)
    {
        $this->teamService->find($teamId);
        return $this->availabilityRepository->getActiveByTeam($teamId);
    }

    public function getTeamAvailabilityForDay($teamId, $dayOfWeek)
    {
        $this->teamService->find($teamId);
        
        if ($dayOfWeek < 0 || $dayOfWeek > 6) {
            throw new \InvalidArgumentException('Day of week must be between 0 (Sunday) and 6 (Saturday)');
        }
        
        return $this->availabilityRepository->getByTeamAndDay($teamId, $dayOfWeek);
    }

    public function createAvailability(array $data)
    {
        // Verify team exists and user has access
        $this->teamService->find($data['team_id']);
        
        $this->validateAvailabilityData($data);
        
        return $this->availabilityRepository->create($data);
    }

    public function updateAvailability($id, array $data)
    {
        $availability = TeamAvailability::forCurrentTenant()->findOrFail($id);
        
        $this->validateAvailabilityData($data);
        
        return $this->availabilityRepository->update($availability, $data);
    }

    public function deleteAvailability($id)
    {
        $availability = TeamAvailability::forCurrentTenant()->findOrFail($id);
        
        $this->availabilityRepository->delete($availability);
    }

    public function syncTeamAvailability($teamId, array $availabilityData)
    {
        // Verify team exists and user has access
        $this->teamService->find($teamId);
        
        // Validate all availability data
        foreach ($availabilityData as $data) {
            $this->validateAvailabilityData($data);
        }
        
        return $this->availabilityRepository->syncTeamAvailability($teamId, $availabilityData);
    }

    private function validateAvailabilityData(array $data)
    {
        // Validate day of week
        if (isset($data['day_of_week']) && ($data['day_of_week'] < 0 || $data['day_of_week'] > 6)) {
            throw new \InvalidArgumentException('Day of week must be between 0 (Sunday) and 6 (Saturday)');
        }
        
        // Validate time format and logic
        if (isset($data['start_time']) && isset($data['end_time'])) {
            $startTime = $data['start_time'];
            $endTime = $data['end_time'];
            
            // Convert to comparable format if they're strings
            if (is_string($startTime)) {
                $startTime = date('H:i:s', strtotime($startTime));
            }
            if (is_string($endTime)) {
                $endTime = date('H:i:s', strtotime($endTime));
            }
            
            if ($startTime >= $endTime) {
                throw new \InvalidArgumentException('Start time must be before end time');
            }
        }
    }
}