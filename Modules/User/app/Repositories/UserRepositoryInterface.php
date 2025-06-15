<?php

namespace Modules\User\App\Repositories;

use Modules\User\App\Models\User;

interface UserRepositoryInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update(User $user, array $data);
    public function delete(User $user);
}
