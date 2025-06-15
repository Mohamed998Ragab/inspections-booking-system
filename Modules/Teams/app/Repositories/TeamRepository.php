<?php

namespace Modules\Teams\App\Repositories;

use Modules\Teams\App\Repositories\TeamRepositoryInterface;
use Modules\Teams\App\Models\Team;

class TeamRepository implements TeamRepositoryInterface
{
    public function getAll()
    {
        // return Team::with(['members'])->get();
        return Team::all();
    }

    public function find($id)
    {
        return Team::findOrFail($id);
    }

    public function create(array $data)
    {
        return Team::create($data);
    }

    public function update(Team $team, array $data)
    {
        $team->update($data);
        return $team;
    }

    public function delete(Team $team)
    {
        $team->delete();
    }

    public function getActiveTeams()
    {
        // return Team::active()->with(['members'])->get();
        return Team::active()->get(); 

    }

    public function getTeamWithMembers($id)
    {
        return Team::with('members')->findOrFail($id);
    }

    public function getTeamWithAvailability($id)
    {
        return Team::with('availability')->findOrFail($id);
    }
}
