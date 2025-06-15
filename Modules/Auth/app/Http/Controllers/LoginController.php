<?php

namespace Modules\Auth\App\Http\Controllers;

use Modules\Auth\App\Http\Requests\LoginRequest;
use Modules\Auth\App\Services\AuthenticationService;
use Modules\User\App\Models\User;
use Modules\User\App\Transformers\UserResource;

class LoginController
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request->validated());
        if (!$token) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $user = User::where('email', $request->email)->first();
        // Transform user into array
        $data = (new UserResource($user))->resolve();

        // Inject token fields *inside* that data block
        $data['token']      = $token;
        $data['token_type'] = 'Bearer';

        // Return exactly: { "data": { ...user fields..., token, token_type } }
        return response()->json(['data' => $data], 200);
    }
}
