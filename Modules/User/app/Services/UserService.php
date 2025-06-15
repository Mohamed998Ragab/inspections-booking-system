<?php

namespace Modules\User\App\Services;

use Modules\User\App\Repositories\UserRepositoryInterface;
use Modules\User\App\Services\UserServiceInterface;
use Modules\User\App\Models\User;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function create(array $data)
    {
        // $data['password'] = bcrypt($data['password']);
        return $this->userRepository->create($data);
    }

    public function update(User $user, array $data)
    {
        // if (isset($data['password'])) {
        //     $data['password'] = bcrypt($data['password']);
        // }
        return $this->userRepository->update($user, $data);
    }

    public function delete(User $user)
    {
        $this->userRepository->delete($user);
    }
}