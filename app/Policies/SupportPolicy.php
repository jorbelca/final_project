<?php

namespace App\Policies;


use App\Models\Support;
use App\Models\User;


class SupportPolicy
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
    public function view(User $user, Support $incidencie): bool
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
    public function update(User $user, Support $incidencie): bool
    {
        return $user->admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Support $incidency): bool
    {

        return $user->id === $incidency->questioner_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Support $incidencie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Support $incidency): bool
    {
        return false;
    }
}
