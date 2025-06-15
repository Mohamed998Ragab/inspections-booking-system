<?php

namespace Modules\User\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\User\App\Services\UserServiceInterface;
use Modules\User\App\Models\User;
use Modules\User\App\Http\Requests\StoreUserRequest;
use Modules\User\App\Http\Requests\UpdateUserRequest;
use Modules\User\App\Transformers\UserResource;

class UserController
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAll();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return new UserResource($user);
    }

    public function show($id)
    {
        try {
            $user = $this->userService->find($id);
            return new UserResource($user);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->find($id);
        $updatedUser = $this->userService->update($user, $request->validated());
        return new UserResource($updatedUser);
    }

    public function destroy($id)
    {
        $user = $this->userService->find($id);
        $this->userService->delete($user);
        return response()->json(['message' => 'User deleted successfully']);
    }
}