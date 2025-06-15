<?php

namespace Modules\Auth\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Auth\App\Services\AuthenticationService;

class LogoutController
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function logout(Request $request)
    {
        try {
            $result = $this->authService->logout($request->user());

            if (!$result) {
                return response()->json(['error' => 'Unauthorized tenant access'], 403);
            }

            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
