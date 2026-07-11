<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Traits\HasRoles;

class UserPolicy
{
    
    public function update(User $user, User $model): bool
    {
        return false;
    }
    public function block(User $user, User $model): bool
    {
        if($user->id === $model->id){
            return false;
        }
        return $user->can('block-users');
    }
    public function addRole(User $user, User $model): bool
    {
        if($user->id === $model->id){
            return false;
        }
        return $user->can('manage-user-roles');
    
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->id !== $model->id ;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if(!$user->hasRole('Super Admin')){
            return false;
        }
        return $user->can('restore-users');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if(!$user->hasRole('Super Admin')){
            return false;
        }
        return $user->can('force-delete-users');
    }
}
