<?php

namespace App\Services;

use App\Filters\UserFilter;
use App\Models\User;

class AdminService
{
    public function __construct(
        protected UserFilter $user_filter
    ) {}
    public function index(array $filters = [])
    {
        $query = User::query()
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            });
        $users = $this->user_filter->filter($query, $filters);
        return $users->paginate(10)->withQueryString();
    }
    public function block(array $data, User $user)
    {
        $is_active =  match ($data['status']) {
            'block' => false,
            'unblock' => true,
        };
        $user->update(['is_active' => $is_active]);
        return $user;
    }
    public function addRole(array $data, User $user)
    {
        if ($data['role'] === "user") {
            $user->syncRoles([]);
        } else {
            $user->syncRoles($data['role']);
        }
        return $user;
    }
    public function destroy(User $user){
        $user->delete();
        return $user;
    }
    public function deletedUsers(array $filters = []){
        $query = User::onlyTrashed();
        $users = $this->user_filter->filter($query, $filters);
        return $users->paginate(5)->withQueryString();
    }
    public function restore(User $user){
        $user->restore();
        return $user->fresh();

    }
}
