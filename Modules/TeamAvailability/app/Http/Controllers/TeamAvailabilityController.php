<?php

namespace Modules\TeamAvailability\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\TeamAvailability\App\Services\TeamAvailabilityServiceInterface;
use Modules\TeamAvailability\App\Http\Requests\StoreTeamAvailabilityRequest;
use Modules\TeamAvailability\App\Http\Requests\UpdateTeamAvailabilityRequest;
use Modules\TeamAvailability\App\Http\Requests\SyncTeamAvailabilityRequest;
use Modules\TeamAvailability\App\Transformers\TeamAvailabilityResource;

class TeamAvailabilityController
{
    protected $availabilityService;

    public function __construct(TeamAvailabilityServiceInterface $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    public function index($teamId)
    {
        $availability = $this->availabilityService->getTeamAvailability($teamId);
        return TeamAvailabilityResource::collection($availability);
    }

    public function active($teamId)
    {
        $availability = $this->availabilityService->getActiveTeamAvailability($teamId);
        return TeamAvailabilityResource::collection($availability);
    }

    public function forDay($teamId, $dayOfWeek)
    {
        $availability = $this->availabilityService->getTeamAvailabilityForDay($teamId, $dayOfWeek);
        return TeamAvailabilityResource::collection($availability);
    }

    public function store(StoreTeamAvailabilityRequest $request)
    {
        $availability = $this->availabilityService->createAvailability($request->validated());
        return new TeamAvailabilityResource($availability);
    }

    public function update(UpdateTeamAvailabilityRequest $request, $id)
    {
        $availability = $this->availabilityService->updateAvailability($id, $request->validated());
        return new TeamAvailabilityResource($availability);
    }

    public function destroy($id)
    {
        $this->availabilityService->deleteAvailability($id);
        return response()->json(['message' => 'Team availability deleted successfully']);
    }

    public function sync($teamId, SyncTeamAvailabilityRequest $request)
    {
        $result = $this->availabilityService->syncTeamAvailability($teamId, $request->input('availability'));
        
        return response()->json([
            'message' => 'Team availability synchronized successfully',
            'deleted_count' => $result['deleted_count'],
            'created_count' => $result['created_count'],
            'availability' => TeamAvailabilityResource::collection($result['availability'])
        ]);
    }
}