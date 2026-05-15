{{--
    ============================================================
    PARTIAL : admin/etats/partials/corps_facture_consultation.blade.php

    Variables attendues (toutes passées depuis consultation.blade.php) :
        $facture           → array (toArray() de FactureConsultation)
        $patient           → array
        $printer           → array|null
        $is_proforma       → bool
        $isPaiementDevise  → bool
        $montantDevise     → float|null
        $tauxConversion    → float|null
        $devise            → string
        $renduFCFA         → float
        $lignes            → array  (lignes de facture_lignes)
        $typeLabels        → array  (labels lisibles par type_acte)
        $totalLignes       → float  (somme des montants des lignes)

    CORRECTION 1 : $montantConsultationBase est calculé ici directement
                   à partir de $facture et $lignes — plus d'Undefined variable.
    CORRECTION 4 : chaque ligne affiche son montant dans la colonne Montant.
    ============================================================
--}}

@php
    /*
     * ── CALCUL DU MONTANT DE BASE CONSULTATION ─────────────────────────────
     *
     * Règle : si des lignes existent, la première ligne de type "consultation"
     * contient le montant snapshot de la consultation de base.
     * Si cette ligne n'existe pas (ancienne facture sans lignes), on affiche
     * le montant total de la facture dans la ligne consultation, et les lignes
     * ajoutées affichent chacune leur montant propre.
     *
     * AVANT (bug) : $montantConsultationBase était utilisé sans être défini → erreur.
     * APRÈS (fix) : calculé ici à partir des données disponibles.
     */
    $lignesCollection = collect($lignes);

    // Chercher la ligne de type "consultation" (snapshot de base)
    $ligneConsultationBase = $lignesCollection->firstWhere('type_acte', 'consultation');

    if ($ligneConsultationBase) {
        // Cas normal : la consultation a été snapshotée en ligne lors du 1er ajout
        $montantConsultationBase = (int) ($ligneConsultationBase['montant'] ?? 0);
        // Les "autres" lignes = tout sauf la ligne consultation de base
        $autresLignes = $lignesCollection->where('type_acte', '!=', 'consultation')->values();
    } else {
        // Ancienne facture sans lignes ou facture simple sans ajout
        // → montant total = montant de la consultation, pas de lignes supplémentaires
        $montantConsultationBase = (int) ($facture['montant'] ?? 0);
        $autresLignes = collect([]);
    }

    // Titre du document
    $titreDocument = $is_proforma ? 'FACTURE PROFORMA' : 'REÇU';

    // Nom patient (avec fallback snapshot)
    $nomPatient = trim(($patient['name'] ?? '') . ' ' . ($patient['prenom'] ?? ''));
    if (empty(trim($nomPatient))) {
        $nomPatient = $facture['patient_name'] ?? '[Patient inconnu]';
    }

    // Caissier
    $caissier = '';
    if (!empty($printer)) {
        $caissier = trim(($printer['prenom'] ?? '') . ' ' . ($printer['name'] ?? ''));
    }

    // Prise en charge assurance
    $priseEnCharge = (float) ($patient['prise_en_charge'] ?? 0);
@endphp

{{-- ── EN-TÊTE ÉTABLISSEMENT ── --}}
<div class="text-center" style="border-bottom: 1px solid #000; padding-bottom: 8px; margin-bottom: 8px;">
    <h5 class="bold">CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE</h5>
    <h6>Vallée Manga Bell — Douala-Bali</h6>
    <h6>Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945</h6>
    <h6>www.cmcu-cm.com</h6>
</div>

{{-- ── TITRE FACTURE ── --}}
<div class="text-center" style="margin: 8px 0;">
    <h4 class="bold">
        {{ $titreDocument }} — {{ strtoupper($facture['details_motif'] ?? $facture['motif'] ?? 'CONSULTATION') }}
    </h4>
    <h6>N° Dossier : {{ $patient['numero_dossier'] ?? '—' }}</h6>
</div>

{{-- ── INFORMATIONS PATIENT / FACTURE ── --}}
<table style="margin: 8px 0; font-size: 10px;">
    <tr>
        <td style="width:25%; border:none; padding:2px 4px;"><strong>Patient</strong></td>
        <td style="width:25%; border:none; padding:2px 4px;">{{ $nomPatient }}</td>
        <td style="width:25%; border:none; padding:2px 4px;"><strong>N° Facture</strong></td>
        <td style="width:25%; border:none; padding:2px 4px;">{{ $facture['numero'] ?? '—' }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Date</strong></td>
        <td style="border:none; padding:2px 4px;">
            {{ isset($facture['created_at']) ? \Carbon\Carbon::parse($facture['created_at'])->format('d/m/Y') : '—' }}
        </td>
        <td style="border:none; padding:2px 4px;"><strong>Mode paiement</strong></td>
        <td style="border:none; padding:2px 4px;">{{ ucfirst($facture['mode_paiement'] ?? '—') }}</td>
    </tr>
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Motif</strong></td>
        <td style="border:none; padding:2px 4px;">{{ $facture['motif'] ?? '—' }}</td>
        <td style="border:none; padding:2px 4px;"><strong>Médecin</strong></td>
        <td style="border:none; padding:2px 4px;">{{ $facture['medecin_r'] ?? '—' }}</td>
    </tr>
    @if(!empty($facture['assurance']))
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Assurance</strong></td>
        <td style="border:none; padding:2px 4px;" colspan="3">{{ $facture['assurance'] }}</td>
    </tr>
    @endif
    @if(!empty($facture['demarcheur']))
    <tr>
        <td style="border:none; padding:2px 4px;"><strong>Démarcheur</strong></td>
        <td style="border:none; padding:2px 4px;" colspan="3">{{ $facture['demarcheur'] }}</td>
    </tr>
    @endif
</table>

{{-- ── TABLEAU DES ACTES ── --}}
{{--
    CORRECTION 4 :
    - Ligne consultation : affiche $montantConsultationBase (calculé ci-dessus)
    - Chaque autre ligne : affiche son propre montant (colonne 'montant')
    Plus aucun montant n'est vide ou à zéro par erreur.
--}}
<table class="lignes-table" style="margin-top: 10px;">
    <thead>
        <tr>
            <th class="col-type">Type</th>
            <th class="col-libelle">Description</th>
            <th class="col-medecin">Médecin / Infirmier(e)</th>
            <th class="col-montant">Montant (FCFA)</th>
        </tr>
    </thead>
    <tbody>

        {{-- ① Ligne consultation de base ── --}}
        <tr>
            <td class="col-type">Consultation</td>
            <td class="col-libelle">
                {{ $facture['motif'] ?? 'Consultation médicale' }}
                @if(!empty($facture['details_motif']))
                    <br><small style="color:#555;">{{ $facture['details_motif'] }}</small>
                @endif
            </td>
            <td class="col-medecin">{{ $facture['medecin_r'] ?? '—' }}</td>
            <td class="col-montant">
                {{ number_format($montantConsultationBase, 0, ',', ' ') }}
            </td>
        </tr>

        {{-- ② Lignes ajoutées (examens, soins, etc.) ── --}}
        @foreach($autresLignes as $ligne)
        <tr>
            <td class="col-type">
                {{ $typeLabels[$ligne['type_acte']] ?? ucfirst(str_replace('_', ' ', $ligne['type_acte'])) }}
            </td>
            <td class="col-libelle">{{ $ligne['libelle'] ?? '—' }}</td>
            <td class="col-medecin">
                {{ $ligne['medecin'] ?? $ligne['infirmiere'] ?? $ligne['infirmniere'] ?? '—' }}
            </td>
            <td class="col-montant">
                {{-- CORRECTION 4 : afficher le montant propre de chaque ligne --}}
                {{ number_format((int)($ligne['montant'] ?? 0), 0, ',', ' ') }}
            </td>
        </tr>
        @endforeach

    </tbody>

    {{-- ③ Totaux ── --}}
    <tfoot>
        {{-- Part assurance (uniquement si prise en charge > 0) --}}
        @if($priseEnCharge > 0 && !empty($facture['assurancec']) && $facture['assurancec'] > 0)
        <tr>
            <td colspan="3" style="text-align:right; font-style:italic; font-size:10px;">
                Part assurance ({{ $priseEnCharge }}%) :
            </td>
            <td style="text-align:right; font-size:10px;">
                {{ number_format((float)($facture['assurancec'] ?? 0), 0, ',', ' ') }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right; font-style:italic; font-size:10px;">
                Part patient :
            </td>
            <td style="text-align:right; font-size:10px;">
                {{ number_format((float)($facture['assurec'] ?? 0), 0, ',', ' ') }}
            </td>
        </tr>
        @endif

        {{-- Total général --}}
        <tr class="lignes-total">
            <td colspan="3" style="text-align:right;">TOTAL GÉNÉRAL</td>
            <td style="text-align:right;">
                {{ number_format((float)($facture['montant'] ?? 0), 0, ',', ' ') }} FCFA
            </td>
        </tr>

        {{-- Avance perçue --}}
        @if(($facture['avance'] ?? 0) > 0)
        <tr>
            <td colspan="3" style="text-align:right;">Avance perçue :</td>
            <td style="text-align:right;">
                − {{ number_format((float)($facture['avance'] ?? 0), 0, ',', ' ') }} FCFA
            </td>
        </tr>
        @endif

        {{-- Paiement en devise étrangère --}}
        @if($isPaiementDevise)
        <tr class="devise-row">
            <td colspan="3" style="text-align:right;">
                <span class="devise-label">Remis ({{ $devise }}) :</span>
            </td>
            <td style="text-align:right;">
                <span class="devise-detail">
                    {{ number_format((float)$montantDevise, 2, ',', ' ') }} {{ $devise }}
                    × {{ $tauxConversion }}
                    = {{ number_format((float)$montantDevise * (float)$tauxConversion, 0, ',', ' ') }} FCFA
                </span>
            </td>
        </tr>
        @if($renduFCFA > 0)
        <tr class="rendu-row">
            <td colspan="3" style="text-align:right;">
                <span class="rendu-label">Rendu :</span>
            </td>
            <td style="text-align:right;">
                {{ number_format((float)$renduFCFA, 0, ',', ' ') }} FCFA
            </td>
        </tr>
        @endif
        @endif

        {{-- Reste à payer --}}
        <tr style="background-color: {{ ($facture['reste'] ?? 0) > 0 ? '#fff0f0' : '#f0fff0' }};">
            <td colspan="3" style="text-align:right; font-weight:bold;">Reste à payer :</td>
            <td style="text-align:right; font-weight:bold; color: {{ ($facture['reste'] ?? 0) > 0 ? '#c00' : '#060' }};">
                {{ number_format((float)($facture['reste'] ?? 0), 0, ',', ' ') }} FCFA
            </td>
        </tr>

    </tfoot>
</table>

{{-- ── PIED DE FACTURE ── --}}
<div style="display:table; width:100%; margin-top:12px; font-size:10px;">
    <div style="display:table-cell; text-align:left;">
        Caissier(e) : <strong>{{ $caissier ?: '—' }}</strong>
    </div>
    <div style="display:table-cell; text-align:right;">
        Douala, le {{ isset($facture['created_at']) ? \Carbon\Carbon::parse($facture['created_at'])->format('d/m/Y') : '—' }}
    </div>
</div>

{{-- ── MENTION PROFORMA ── --}}
@if($is_proforma)
<div style="margin-top:10px; padding:6px 10px; border:1px solid #c8a000; background-color:#fffbe6; font-size:10px; text-align:center;">
    <strong>⚠ DOCUMENT PROFORMA</strong> — Cette facture n'est pas encore soldée.
</div>
@endif