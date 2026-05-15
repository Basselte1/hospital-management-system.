<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * PatientPolicy — Access Control Matrix
 * ────────────────────────────────────────────────────────────────
 * Role IDs:
 *   1  = Admin          → all rights (via before())
 *   2  = Médecin        → clinical + admin view
 *   3  = Gestionnaire   → full patient view (clinical + admin + financial)
 *   4  = Infirmier      → clinical view + parameters
 *   5  = Logistique     → admin/financial view ONLY (no medical records)
 *   6  = Secrétaire     → admin view + limited create/edit
 *                          CAN create/edit bon de laboratoire (Phase 1 only)
 *   7  = Pharmacien     → admin/financial view ONLY (no medical records)
 *   8  = (reserved)
 *   9  = Comptable      → admin/financial view ONLY (no medical records)
 *  10  = Laborantin     → can create bons, enter results, export lab results
 *                         NO access to: consultations, prescriptions, surgical notes,
 *                         financial records, parameters, imaging
 * ────────────────────────────────────────────────────────────────
 */
class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * Admin bypass — role 1 always returns true so individual methods
     * are not evaluated for admin users.
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Actions reserved for admin only.
     */
    public function adminOnly(User $user): bool
    {
        return $user->role_id === 1;
    }

    // ─── List ──────────────────────────────────────────────────
    public function viewList(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 3, 4, 5, 6, 7, 9, 10]);
    }

    // ─── Create / Update ──────────────────────────────────────
    public function create(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 6, 4, 3]);
    }

    public function update(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 6, 4, 3]);
    }

    // ─── Patient visits ───────────────────────────────────────
    public function viewVisitsList(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 4, 6]);
    }

    public function createVisit(User $user): bool
    {
        return in_array($user->role_id, [1, 6, 4]);
    }

    public function updateVisitStatus(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 6]);
    }

    // ─── Print / PDF ──────────────────────────────────────────
    public function print(User $user): bool
    {
        return in_array($user->role_id, [1, 6, 9, 10]);
    }

    // ─── Delete ───────────────────────────────────────────────
    /**
     * Hard delete of a Patient record — Admin only.
     * Note: LaboratoireController@destroy (archive) uses laboratoireWrite,
     * NOT this method.
     */
    public function delete(User $user): bool
    {
        return $user->role_id === 1;
    }

    // ─── Clinical consultation view ───────────────────────────
    public function consulter(User $user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }
        return in_array($user->role_id, [1, 3, 2, 4, 6]);
    }

    // ─── Laboratory access ────────────────────────────────────

    /**
     * Who can ACCESS the lab module (read lab records).
     */
    public function laboratoire(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 2, 3, 6, 10]);
    }

    /**
     * Who can CREATE a bon de laboratoire (Phase 1 — pré-analytique only).
     */
    public function laboratoireCreate(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 6, 10]);
    }

    /**
     * Who can ENTER RESULTS, VALIDATE, ARCHIVE (Phase 2 & 3).
     * Restricted to Laborantin (10) and Admin (1) only.
     *
     * BUG FIX #3: LaboratoireController@destroy now uses this method
     * instead of the 'delete' policy, so laborantins can archive records.
     */
    public function laboratoireWrite(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 10]);
    }

    /**
     * NEW: Who can mark results as validated via the standalone endpoint.
     * Same as laboratoireWrite — kept separate for semantic clarity and
     * future granularity (e.g. if a senior biologist role is added).
     */
    public function valider(User $user, \App\Models\ExamenLaboratoire $exam = null): bool
    {
        return in_array($user->role_id, [1, 10]);
    }

    /**
     * NEW: Who can mark results as remis via the standalone endpoint.
     */
    public function remettre(User $user, \App\Models\ExamenLaboratoire $exam = null): bool
    {
        return in_array($user->role_id, [1, 10]);
    }

    // ─── Role-based access groupings ─────────────────────────

    public function infirmier(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [4]);
    }

    public function infirmier_secretaire(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 4, 6]);
    }

    public function medecin_secretaire(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 2, 6]);
    }

    public function infirmier_chirurgien(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 4, 2]);
    }

    public function secretaire(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 6]);
    }

    public function medecin(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 2]);
    }

    public function med_inf_anes(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return in_array($user->role_id, [1, 2, 3, 4]);
    }

    public function anesthesiste(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return $user->isAnesthesiste();
    }

    public function chirurgien(User $user = null): bool
    {
        if (!$user) $user = auth()->user();
        return $user->isChirurgien() || $user->isPediatre() || $user->isGeneraliste();
    }

    // ─── Static helper methods ────────────────────────────────

    public static function isLimitedRole(User $user): bool
    {
        return in_array($user->role_id, [5, 7, 9]);
    }

    public static function isLaborantin(User $user): bool
    {
        return $user->role_id === 10;
    }

    public static function isSecretaire(User $user): bool
    {
        return $user->role_id === 6;
    }
}