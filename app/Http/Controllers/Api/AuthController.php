<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected UserService $user_service
    ) {}
    public function register(RegisterRequest $request)
    {
        $user = $this->user_service->register($request->validated());
        $user->refresh();
        $token = $user->createToken('auth_token')->plainTextToken;
        $cookie = cookie('auth_token', $token, 1440, null, null, true, true);
        return $this->success(
            new UserResource($user),
            'user created',
            201
        )->withCookie($cookie);
    }
    public function login(LoginRequest $request)
    {
        $user = $this->user_service->login($request->validated());
        if (!$user) {
            return $this->error('incorrect password or email', 401);
        }

        if (!$user->is_active) {
            return $this->error('your account has been blocked', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $cookie = cookie('auth_token', $token, 1440, null, null, true, true);
        return $this->success(
            new UserResource($user),
            'logged in successfully',
            200
        )->withCookie($cookie);
    }
    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        $cookie = cookie()->forget('auth_token');
        return $this->success(
            null,
            'logged out successfully'
        )->withCookie($cookie);
    }
}
