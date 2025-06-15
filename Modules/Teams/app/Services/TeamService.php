<?php

namespace Modules\Teams\App\Services;

use Modules\Teams\App\Repositories\TeamRepositoryInterface;

use Modules\Teams\App\Services\TeamServiceInterface;
use Modules\Teams\App\Models\Team;

class TeamService implements TeamServiceInterface
{
    protected $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function getAll()
    {
        return $this->teamRepository->getAll();
    }

    public function find($id)
    {
        return $this->teamRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->teamRepository->create($data);
    }

    public function update(Team $team, array $data)
    {
        return $this->teamRepository->update($team, $data);
    }

    public function delete(Team $team)
    {
        $this->teamRepository->delete($team);
    }

    public function getActiveTeams()
    {
        return $this->teamRepository->getActiveTeams();
    }

    public function getTeamWithMembers($id)
    {
        return $this->teamRepository->getTeamWithMembers($id);
    }


}