<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected UserService $user_service
    ) {}
    public function show()
    {
        $user = auth()->user();
        return $this->success(new UserResource($user), 'profile info', 200);
    }
    public function update(UpdateRequest $request)
    {
        $user = $this->user_service->update($request->validated());
        return $this->success(
            new UserResource($user),
            'updated successfully',
            200,
        );
    }
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $this->user_service->updatePassword($request->validated());
        if (!$user) {
            return $this->error(
                'incorrect password',
                422,
            );
        }
        return $this->success(
            null,
            'password updated',
            200,
        );
    }
}
