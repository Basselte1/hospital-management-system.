<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProduitPolicy
{
    use HandlesAuthorization;

    /**
     * Admin (1) always has full access
     */
    public function before(User $user, $ability)
    {
        if ($user->role_id === 1) {
            return true;
        }
    }

    /**
     * View a single product
     * Roles: Admin(1), Logistique(5), Pharmacien(7), Comptable(9)
     */
    public function view(User $user, Produit $produit = null)
    {
        return in_array($user->role_id, [1, 5, 7]);
    }

    /**
     * View product list
     * Roles: Admin(1), Logistique(5), Pharmacien(7), Comptable(9)
     */
    public function viewAny(User $user)
    {
        return in_array($user->role_id, [1,3, 5, 7, 9]);
    }

    /**
     * Create products - No approval required
     * Roles: Admin(1), Logistique(5), Pharmacien(7)
     */
    public function create(User $user)
    {
        return in_array($user->role_id, [1, 5, 7]);
    }

    /**
     * Update products - Creates edit request for Logistique/Pharmacien
     * Admin can edit directly
     * Roles: Admin(1), Logistique(5), Pharmacien(7)
     */
    public function update(User $user, Produit $produit = null)
    {
        return in_array($user->role_id, [1, 5, 7]);
    }

    /**
     * Delete products
     * Only Admin and Comptable can delete
     */
    public function delete(User $user, Produit $produit)
    {
        return in_array($user->role_id, [1, 9]);
    }

    /**
     * Edit products (same as update)
     */
    public function edit(User $user)
    {
        return in_array($user->role_id, [1, 5, 7]);
    }

    /**
     * Print products
     */
    public function print(User $user)
    {
        return in_array($user->role_id, [1, 2, 5, 7, 9]);
    }

    /**
     * Anesthesiste products
     */
    public function anesthesiste(User $user)
    {
        return in_array($user->role_id, [2, 5, 7]);
    }

    /**
     * Approve edit requests
     * Only Gestionnaire and Admin
     */
    public function approveEditRequests(User $user)
    {
        return in_array($user->role_id, [1, 3]);
    }

    /**
     * Verify stock (Comptable role)
     */
    public function verifyStock(User $user)
    {
        return in_array($user->role_id, [1, 9]);
    }

    /**
     * View audit logs
     * Only Admin can view full audit trail
     */
    public function viewAuditLogs(User $user)
    {
        return $user->role_id === 1;
    }
}