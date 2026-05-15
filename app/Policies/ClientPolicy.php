<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * before() : l'admin passe partout, sans vérifier les autres méthodes.
     * Laravel appelle before() AVANT chaque méthode de policy.
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    // ---------------------------------------------------------------
    // Voir la liste des clients (pas besoin d'une instance Client)
    // Appelé avec : $this->authorize('viewAny', Client::class)
    // ---------------------------------------------------------------
    public function viewAny(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 3, 6, 9]);
    }

    // ---------------------------------------------------------------
    // Voir UN client spécifique
    // Appelé avec : $this->authorize('view', $client)   ← instance
    //          OU : @can('view', \App\Models\Client::class) ← classe
    //
    // CORRECTION : $client est rendu OPTIONNEL (valeur par défaut null)
    // pour éviter l'erreur "Too few arguments" quand Laravel appelle
    // la méthode avec seulement l'utilisateur (cas @can avec classe).
    // ---------------------------------------------------------------
    public function view(User $user, ?Client $client = null): bool
    {
        return in_array($user->role_id, [1, 2, 3, 6, 9]);
    }

    // ---------------------------------------------------------------
    // Créer un client externe
    // Appelé avec : $this->authorize('create', Client::class)
    // ---------------------------------------------------------------
    public function create(User $user): bool
    {
        return in_array($user->role_id, [1, 6, 3]);
    }

    // ---------------------------------------------------------------
    // Modifier un client
    // Appelé avec : $this->authorize('update', $client)
    // ---------------------------------------------------------------
    public function update(User $user, Client $client): bool
    {
        return in_array($user->role_id, [1, 6]);
    }

    // ---------------------------------------------------------------
    // Supprimer un client
    // Appelé avec : $this->authorize('delete', $client)
    // ---------------------------------------------------------------
    public function delete(User $user, ?Client $client = null ): bool
    {
        return in_array($user->role_id, [1]);
    }

    // ---------------------------------------------------------------
    // Générer une facture
    // Appelé avec : $this->authorize('generateFacture', Client::class)
    //          OU : @can('generateFacture', \App\Models\Client::class)
    //
    // CORRECTION : même principe que view(), $client optionnel
    // ---------------------------------------------------------------
    public function generateFacture(User $user, ?Client $client = null): bool
    {
        return in_array($user->role_id, [1, 3, 9,6]);
    }
}