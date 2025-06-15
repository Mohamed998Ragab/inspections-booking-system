<?php

namespace Modules\Teams\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Teams\App\Services\TeamServiceInterface;
use Modules\Teams\App\Models\Team;
use Modules\Teams\App\Http\Requests\StoreTeamRequest;
use Modules\Teams\App\Http\Requests\UpdateTeamRequest;

use Modules\Teams\App\Transformers\TeamResource;

class TeamController
{
    protected $teamService;

    public function __construct(TeamServiceInterface $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        $teams = $this->teamService->getAll();
        return TeamResource::collection($teams);
    }

    public function store(StoreTeamRequest $request)
    {
        $team = $this->teamService->create($request->validated());
        return new TeamResource($team);
    }

    public function show($id)
    {
        try {
            $team = $this->teamService->find($id);
            return new TeamResource($team);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Team not found'], 404);
        }
    }


    public function update(UpdateTeamRequest $request, $id)
    {
        $team = $this->teamService->find($id);
        $updatedTeam = $this->teamService->update($team, $request->validated());
        return new TeamResource($updatedTeam);
    }

    public function destroy($id)
    {
        $team = $this->teamService->find($id);
        $this->teamService->delete($team);
        return response()->json(['message' => 'Team deleted successfully']);
    }

    public function active()
    {
        $teams = $this->teamService->getActiveTeams();
        return TeamResource::collection($teams);
    }


}