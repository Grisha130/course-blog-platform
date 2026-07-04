<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Http\Requests\Role\SuperAdminRequest;
use App\Http\Requests\Role\UserBlockRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SuperAdminController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected AdminService $admin_service
    ){}
    public function index(SuperAdminRequest $request){
        Gate::authorize('view-users');
        $users = $this->admin_service->index($request->validated());
        return $this->paginate(UserResource::collection($users), 'all users');   
    }
    public function block(UserBlockRequest $request, User $user){
        $this->authorize('block', $user);
        $user = $this->admin_service->block($request->validated(), $user);
        return $this->success(new UserResource($user), 'user blocked');
    }
    public function addRole(RoleRequest $request, User $user){
        $this->authorize('addRole', $user);
        $user = $this->admin_service->addRole($request->validated(), $user);
        return $this->success(new UserResource($user), 'role aded', 201);
    }
    public function destroy(User $user){
        $this->authorize('delete', $user);
        $user = $this->admin_service->destroy($user);
        return $this->success(new UserResource($user), 'user deleted');
    }
    public function deletedUsers(SuperAdminRequest $request){
        Gate::authorize('view-deleted-users');
        $users = $this->admin_service->deletedUsers($request->validated());
        return $this->paginate(UserResource::collection($users), 'all deleted users');
    }
    public function restore(User $user){
        $this->authorize('restore', $user);
        $user = $this->admin_service->restore($user);
        return $this->success(new UserResource($user), 'user restored');
    }
    public function forceDelete(User $user){
        $this->authorize('forceDelete', $user);
        $user->forceDelete();
        return $this->success(new UserResource($user), 'user force deleted');
    }
}
