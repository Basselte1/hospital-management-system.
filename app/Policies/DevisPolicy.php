<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Devi;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevisPolicy
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
     * Determine if the user can view devis
     */
    public function view(User $user)
    {
        return in_array($user->role_id, [
            1, // Admin
            2, // Medecin
            3, // Gestionnaire
            6  // Secretaire
        ]);
    }

    /**
     * Determine if the user can create devis
     */
    public function create(User $user)
    {
        return in_array($user->role_id, [
            1, // Admin
            3, // Gestionnaire
        ]);
    }

    /**
     * Determine if the user can update devis
     */
    public function update(User $user)
    {
        return in_array($user->role_id, [
            1, // Admin
            3, // Gestionnaire
        ]);
    }

    /**
     * Determine if the user can validate/refuse devis
     * NOTE: Cannot be named "validate" - that's reserved by Laravel's HandlesAuthorization trait.
     * Use "valider" instead, and call $this->authorize('valider', Devi::class) in the controller.
     */
    public function valider(User $user)
    {
        return in_array($user->role_id, [
            1, // Admin
            2  // Medecin
        ]);
    }

    /**
     * Determine if the user can apply reduction
     */
    // public function applyReduction(User $user, Devi $devi = null)
    // {
    //     return in_array($user->role_id, [
    //         1, // Admin
    //         2  // Medecin
    //     ]);
    // }

    public function applyReduction(User $user, Devi $devi)
    {
        // Admin or the assigned doctor
        return $user->role_id == 1 || $devi->medecin_id == $user->id;
    }

    /**
     * Determine if the user can print devis
     */
    public function print(User $user)
    {
        return in_array($user->role_id, [
            1, // Admin
            2, // Medecin
            3, // Gestionnaire
            6  // Secretaire
        ]);
    }

    /**
     * Determine if the user can delete devis
     */
    public function delete(User $user)
    {
        return in_array($user->role_id, [
            1, 3 // Only admin
        ]);
    }
}