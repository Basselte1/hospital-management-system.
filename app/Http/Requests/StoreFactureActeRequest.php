<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreFactureActeRequest
 */
class StoreFactureActeRequest extends FormRequest
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
            'lignes.*.type_acte'     => 'required|string|max:100',
            'lignes.*.libelle'       => 'required|string|max:255',
            'lignes.*.montant'       => 'required|numeric|min:0',
            'lignes.*.quantite'      => 'nullable|integer|min:1',
            'lignes.*.medecin'       => 'nullable|string|max:100',
            'lignes.*.infirmiere'    => 'nullable|string|max:100',
            'lignes.*.date_acte'     => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required'    => 'Le patient est obligatoire.',
            'montant_total.required' => 'Le montant total est obligatoire.',
            'lignes.required'        => 'Au moins un acte médical est requis.',
            'lignes.min'             => 'Au moins un acte médical est requis.',
        ];
    }
}