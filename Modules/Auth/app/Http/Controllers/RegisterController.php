<?php

namespace Modules\Auth\App\Http\Controllers;

use Modules\Auth\App\Http\Requests\RegisterRequest;
use Modules\Auth\App\Services\AuthenticationService;
use Modules\User\App\Transformers\UserResource;

class RegisterController
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;
        return (new UserResource($user))->additional([
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
