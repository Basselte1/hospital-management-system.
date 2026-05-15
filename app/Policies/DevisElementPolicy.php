<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DevisElement;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevisElementPolicy
{
    use HandlesAuthorization;

    /**
     * Admin has all permissions
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the user can view any elements
     */
    public function viewAny(User $user)
    {
        return in_array($user->role_id, [1, 3]); // Only admin and Gestionaire
    }

    /**
     * Determine if the user can create elements
     */
    public function create(User $user)
    {
        return in_array($user->role_id, [1, 3]); // Only admin and Gestionaire
    }

    /**
     * Determine if the user can update elements
     */
    public function update(User $user)
    {
        return in_array($user->role_id, [1, 3]); // Only admin and Gestionaire
    }

    /**
     * Determine if the user can delete elements
     */
    public function delete(User $user)
    {
        return in_array($user->role_id, [1 ,3]); // Only admin
    }
}