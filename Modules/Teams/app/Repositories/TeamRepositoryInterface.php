<?php
namespace Modules\Teams\App\Repositories;

use Modules\Teams\App\Models\Team;

interface TeamRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update(Team $team, array $data);
    public function delete(Team $team);
    public function getActiveTeams();
    public function getTeamWithMembers($id);
}