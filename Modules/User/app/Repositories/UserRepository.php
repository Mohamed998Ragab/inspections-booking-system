<?php

// modules/Users/Repositories/UserRepository.php
namespace Modules\User\App\Repositories;

use Modules\User\App\Repositories\UserRepositoryInterface;
use Modules\User\App\Models\User;


class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user)
    {
        $user->delete();
    }
}