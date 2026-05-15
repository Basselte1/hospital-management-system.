<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FactureConsultationRequest — VERSION COMPLÈTE
 *
 * CORRECTION PAR RAPPORT À L'ANCIENNE VERSION :
 *   L'ancienne version ne validait que 'numero' (unique). C'est insuffisant :
 *   un patient_id invalide ou un montant négatif passait sans erreur.
 *
 *   Cette version s'aligne sur StoreFactureExamenRequest et StoreFactureActeRequest
 *   en termes de complétude et de structure.
 *
 * NOTE PRODUCTION : cette Request n'impacte pas la base de données.
 *   Elle peut être déployée immédiatement. Les controllers qui utilisent
 *   l'ancienne version recevront maintenant des erreurs 422 pour les champs
 *   manquants — vérifiez que votre frontend envoie bien tous les champs requis.
 */
class FactureConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Pour les mises à jour, on ignore le numéro de la facture courante.
        $factureId = $this->route('facture_consultation')?->id
                  ?? $this->route('factureConsultation')?->id
                  ?? null;

        $uniqueNumero = $factureId
            ? "required|unique:facture_consultations,numero,{$factureId}"
            : 'required|unique:facture_consultations,numero';

        return [
            'patient_id'             => 'required|exists:patients,id',
            'consultation_id'        => 'nullable|exists:consultations,id',
            'user_id'                => 'nullable|exists:users,id',

            'numero'                 => $uniqueNumero,
            'motif'                  => 'required|string|max:255',

            // 'montant' (et non 'montant_total') : legacy de la migration 2019.
            'montant'                => 'required|numeric|min:0',
            'avance'                 => 'nullable|numeric|min:0',
            'prise_en_charge'        => 'nullable|numeric|min:0|max:100',
            'assurance'              => 'nullable|string|max:100',
            'numero_assurance'       => 'nullable|string|max:100',
            'mode_paiement'          => 'nullable|string|max:50',
            'mode_paiement_info_sup' => 'nullable|string|max:255',
            'notes'                  => 'nullable|string',

            'date_insertion'         => 'nullable|date',
            'medecin_r'              => 'nullable|string|max:100',
            'demarcheur'             => 'nullable|string|max:100',

            // Lignes optionnelles (une facture consultation peut exister sans lignes
            // dans le modèle legacy, mais si elles sont fournies, on les valide).
            'lignes'                 => 'nullable|array',
            'lignes.*.type_acte'     => 'required_with:lignes|string|max:100',
            'lignes.*.libelle'       => 'required_with:lignes|string|max:255',
            'lignes.*.montant'       => 'required_with:lignes|numeric|min:0',
            'lignes.*.medecin'       => 'nullable|string|max:100',
            'lignes.*.infirmiere'    => 'nullable|string|max:100',
            'lignes.*.ordre'         => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required'    => 'Le patient est obligatoire.',
            'patient_id.exists'      => "Ce patient n'existe pas.",
            'numero.required'        => 'Le numéro de facture est obligatoire.',
            'numero.unique'          => 'Cette facture existe déjà.',
            'motif.required'         => 'Le motif de consultation est obligatoire.',
            'montant.required'       => 'Le montant est obligatoire.',
            'montant.min'            => 'Le montant ne peut pas être négatif.',
        ];
    }
}