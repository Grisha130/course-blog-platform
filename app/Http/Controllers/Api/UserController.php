<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $user_service
    ){}
    public function show(){
        $user = auth()->user();
        return response()->json([
            'user'=>new UserResource($user),
            'message'=>'profile info'
        ]);
    }
    public function update(UpdateRequest $request){
        $user = $this->user_service->update($request->validated());
        return response()->json([
            'message'=>'updated successfully',
            'user'=>new UserResource($user),
        ]);
    }
    public function updatePassword(UpdatePasswordRequest $request){
        $user = $this->user_service->updatePassword($request->validated());
        if(!$user){
            return response()->json([
                'message'=>'incorrect password'
            ], 422);
        }
        return response()->json([
            'message'=>'password updated',
        ]);

    }
}
