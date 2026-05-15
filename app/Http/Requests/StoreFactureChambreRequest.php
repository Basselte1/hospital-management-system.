<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreFactureChambreRequest
 *
 * CORRECTION :
 *   La validation `date_entree` a été renommée en `date_entre`
 *   pour correspondre au nom de colonne BDD réel (migration 2019).
 *
 *   La règle after_or_equal utilisait 'date_entree' comme référence,
 *   ce qui ne fonctionnait pas car le champ s'appelle 'date_entre'.
 */
class StoreFactureChambreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id'             => 'required|exists:patients,id',
            'chambre_id'             => 'nullable|exists:chambres,id',
            'user_id'                => 'nullable|exists:users,id',

            // CORRIGÉ : 'date_entre' (nom BDD réel) et non 'date_entree'
            'date_entre'             => 'nullable|date',
            'date_sortie'            => 'nullable|date|after_or_equal:date_entre',

            'avance'                 => 'nullable|numeric|min:0',
            'prise_en_charge'        => 'nullable|numeric|min:0|max:100',
            'assurance'              => 'nullable|boolean',
            'numero_assurance'       => 'nullable|string|max:100',
            'mode_paiement'          => 'nullable|string|max:50',
            'mode_paiement_info_sup' => 'nullable|string|max:255',
            'notes'                  => 'nullable|string',

            'lignes'                 => 'required|array|min:1',
            'lignes.*.type_ligne'    => 'required|string|in:hebergement,soin_infirmier,traitement,autre',
            'lignes.*.libelle'       => 'required|string|max:255',
            'lignes.*.montant'       => 'required|numeric|min:0',
            'lignes.*.quantite'      => 'nullable|integer|min:1',
            'lignes.*.date_soin'     => 'nullable|date',
            'lignes.*.reference_id'  => 'nullable|integer',
            'lignes.*.reference_type'=> 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required'        => 'Le patient est obligatoire.',
            'date_sortie.after_or_equal' => "La date de sortie doit être égale ou après la date d'entrée.",
            'lignes.required'            => 'Au moins une ligne de séjour est requise.',
            'lignes.min'                 => 'Au moins une ligne de séjour est requise.',
            'lignes.*.type_ligne.in'     => 'Type invalide. Valeurs acceptées : hebergement, soin_infirmier, traitement, autre.',
            'lignes.*.montant.min'       => 'Le montant ne peut pas être négatif.',
        ];
    }
}