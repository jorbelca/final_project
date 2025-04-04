<?php

namespace App\Policies;

use App\Models\Prompt;
use App\Models\User;

class PromptPolicy
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
    public function view(User $user, Prompt $prompt): bool
    {
        return $user->hasCredits();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Prompt $prompt): bool
    {
        return $user->hasCredits() && $user->isActive();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Prompt $prompt): bool
    {
        return $user->hasCredits() && $user->isActive();;
    }
}
