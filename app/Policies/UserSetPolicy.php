<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserSet;

class UserSetPolicy
{
    /**
     * Determina si el usuario puede ver todos los sets.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede ver un set en particular.
     */
    public function view(User $user, UserSet $userSet)
    {
        return $user->hasRole('admin') || $user->id === $userSet->user_id;
    }
}
