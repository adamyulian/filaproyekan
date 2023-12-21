<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Component;
use App\Models\User;

class ComponentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     if ($user->id == 1) {
    //         return true; // Admins can view all component
    //     }

    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Component $component): bool
    {
        if ($user->id == 1) {
            return true; // Admins can view all component
        }

        // Non-admin users can only view their own component
        return $user->id === $component->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Component $component): bool
    {
        if ($user->id == 1) {
            return true; // Admins can view all component
        }

        // Non-admin users can only view their own component
        return $user->id === $component->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Component $component): bool
    {
        if ($user->id == 1) {
            return true; // Admins can view all component
        }

        // Non-admin users can only view their own component
        return $user->id === $component->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Component $component): bool
    {
        if ($user->id == 1) {
            return true; // Admins can view all component
        }

        // Non-admin users can only view their own component
        return $user->id === $component->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Component $component): bool
    {
        if ($user->id == 1) {
            return true; // Admins can view all component
        }

        // Non-admin users can only view their own component
        return $user->id === $component->user_id;
    }
}
