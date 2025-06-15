<?php

namespace Modules\User\App\Services;

use Modules\User\App\Models\User;

interface UserServiceInterface
{
    public function getAll();
    public function find($id);
    public function create(array $data);
    public function update(User $user, array $data);
    public function delete(User $user);
}