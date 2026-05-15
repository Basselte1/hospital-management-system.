<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FactureConsultation;
use Illuminate\Auth\Access\HandlesAuthorization;

class FactureConsultationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can update the invoice
     * Only admin (role_id = 1) and secretaire (role_id = 6) can update invoices
     */
    public function update(User $user, FactureConsultation $factureConsultation)
    {
        // Check if user is admin or secretaire
        return in_array($user->role_id, [1,6]);
    }

    /**
     * Determine if the user can delete the invoice
     * Only admin (role_id = 1) can delete invoices
     */
    public function delete(User $user, FactureConsultation $factureConsultation)
    {
        return $user->role_id === 1;
    }

    /**
     * Determine if the user can view the invoice
     */
    public function view(User $user, FactureConsultation $factureConsultation)
    {
        return in_array($user->role_id, [1, 2, 3, 4, 6, 9]);
    }

   
}
