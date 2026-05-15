<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreFactureExamenRequest — VERSION CORRIGÉE
 *
 * CORRECTION PAR RAPPORT À L'ANCIENNE VERSION :
 *
 *   BUG — Incohérence de nommage 'type_examen' vs colonne 'type_sous' :
 *     La migration 2026_04_23_133002_update_facture_lignes_table crée la colonne
 *     'type_sous' dans facture_lignes. La request validait 'lignes.*.type_examen'
 *     qui n'est pas le nom de la colonne.
 *
 *     DEUX OPTIONS possibles selon votre architecture :
 *
 *     Option A (choisie ici) — Le champ s'appelle 'type_sous' côté API aussi.
 *       Avantage : cohérence totale entre la request, le fillable et la BDD.
 *       Impact : le frontend doit envoyer 'type_sous' au lieu de 'type_examen'.
 *
 *     Option B — Conserver 'type_examen' dans la request et mapper dans le controller :
 *       $data['lignes'] = array_map(fn($l) => [...$l, 'type_sous' => $l['type_examen']], $validated['lignes']);
 *       Avantage : pas de changement frontend.
 *       Inconvénient : logique de mapping dans le controller.
 *
 *     Choisissez l'option qui convient à votre frontend. Le commentaire ci-dessous
 *     documente les deux pour que votre équipe puisse décider.
 *
 *   CONSERVÉ — tous les autres champs inchangés.
 */
class StoreFactureExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id'             => 'required|exists:patients,id',
            'consultation_id'        => 'nullable|exists:consultations,id',
            'user_id'                => 'nullable|exists:users,id',

            'montant_total'          => 'required|numeric|min:0',
            'avance'                 => 'nullable|numeric|min:0',
            'prise_en_charge'        => 'nullable|numeric|min:0|max:100',
            'assurance'              => 'nullable|boolean',
            'numero_assurance'       => 'nullable|string|max:100',
            'mode_paiement'          => 'nullable|string|max:50',
            'mode_paiement_info_sup' => 'nullable|string|max:255',
            'notes'                  => 'nullable|string',

            'lignes'                 => 'required|array|min:1',

            // CORRIGÉ : 'type_sous' au lieu de 'type_examen' pour correspondre
            // à la colonne facture_lignes.type_sous (migration 2026).
            // Si votre frontend envoie 'type_examen', choisissez Option B
            // (mapping dans le controller) et remettez 'type_examen' ici.
            'lignes.*.type_sous'               => 'required|string|in:laboratoire,imagerie',
            'lignes.*.libelle'                 => 'required|string|max:255',
            'lignes.*.montant'                 => 'required|numeric|min:0',
            'lignes.*.technicien'              => 'nullable|string|max:100',
            'lignes.*.examen_laboratoire_id'   => 'nullable|exists:examens_laboratoire,id',
            'lignes.*.imagerie_id'             => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required'     => 'Le patient est obligatoire.',
            'patient_id.exists'       => "Ce patient n'existe pas.",
            'montant_total.required'  => 'Le montant total est obligatoire.',
            'lignes.required'         => "Au moins une ligne d'examen est requise.",
            'lignes.min'              => "Au moins une ligne d'examen est requise.",
            'lignes.*.type_sous.in'   => 'Le type doit être "laboratoire" ou "imagerie".',
            'lignes.*.montant.min'    => "Le montant d'une ligne ne peut pas être négatif.",
        ];
    }
}