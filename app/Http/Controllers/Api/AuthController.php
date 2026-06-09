<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $user_service
    ){}
    public function register(RegisterRequest $request){
        $user = $this->user_service->register($request->validated());
        $user->refresh();
        $token = $user->createToken('auth_token')->plainTextToken;
        $cookie = cookie('auth_token', $token, 1440, null, null, true, true);
        return response()->json([
            'message'=>'user created',
            'user'=> new UserResource($user),
        ])->withCookie($cookie);

    }
    public function login(LoginRequest $request){
        $user = $this->user_service->login($request->validated());
        if(!$user){
            return response()->json([
                'message'=>'incorrect password or email'
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        $cookie = cookie('auth_token', $token, 1440, null, null, true, true);
        return response()->json([
            'message'=>'logged in succssefully',
            'user'=>new UserResource($user)
        ])->withCookie($cookie);
    }
    public function logout(){
        request()->user()->currentAccessToken()->delete();
        $cookie = cookie()->forget('auth_token');
        return response()->json([
            'message'=>'logged out successfully'
        ])->withCookie($cookie);
    }
}   
