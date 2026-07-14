<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use App\Enums\BlogStatus;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
    public function view(User $user, Blog $blog): bool
    {
        if ($blog->status === BlogStatus::PUBLISHED && $blog->is_active) {
            return true;
        }
        if ($user->can('manage-blogs')) {
            return true;
        }
        return $user->id === $blog->user_id;
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Blog $blog): bool
    {
        if ($user->can('manage-blogs')) {
            return true;
        }
        return $user->id === $blog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Blog $blog): bool
    {
        if ($user->can('manage-blogs')) {
            return true;
        }
        return $user->id === $blog->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Blog $blog): bool
    {
        if ($user->can('manage-blogs')) {
            return true;
        }
        return $user->id === $blog->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Blog $blog): bool
    {
        return $user->can('manage-blogs');
    }
    public function allDeleted(User $user): bool
    {
        return $user->can('manage-blogs');
    }
    public function viewBlock(User $user): bool
    {
        return $user->can('manage-blogs');
    }
    public function block(User $user, Blog $blog): bool
    {
        return $user->can('manage-blogs');
    }
    public function viewAll(User $user): bool
    {
        return $user->can('manage-blogs');
    }
}
