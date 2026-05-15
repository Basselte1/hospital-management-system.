<?php

namespace App\Policies;

use App\Models\User;

class DailyFacturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role_id, [1, 6]); // admin, secretaire
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, $dailyFacture)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return in_array($user->role_id, [1, 6]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $dailyFacture)
    {
        return in_array($user->role_id, [1, 6]) && !$dailyFacture->isSoldee();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, $dailyFacture)
    {
        return $user->role_id === 1 && !$dailyFacture->isSoldee();
    }

    /**
     * Determine whether the user can generate invoice.
     */
    public function generateInvoice(User $user)
    {
        return in_array($user->role_id, [1, 6]);
    }
}
