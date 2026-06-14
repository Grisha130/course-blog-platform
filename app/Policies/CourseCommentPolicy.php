<?php

namespace App\Policies;

use App\Models\CourseComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CourseCommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CourseComment $courseComment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CourseComment $courseComment): bool
    {
        return $user->id === $courseComment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CourseComment $courseComment): bool
    {
        return $user->id === $courseComment->user_id || $user->id === $courseComment->course->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CourseComment $courseComment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CourseComment $courseComment): bool
    {
        return false;
    }
}
