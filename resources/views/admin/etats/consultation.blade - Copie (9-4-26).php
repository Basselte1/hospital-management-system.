<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Facture Consultation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            line-height: 1.45;
            color: #111;
        }

        /* ── Conteneur principal d'une copie ── */
        .facture-copy {
            border: 1.5px solid #222;
            padding: 14px 16px;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
        }

        /* ── Séparateur entre les 2 copies ── */
        .separateur {
            border: none;
            border-top: 2px dashed #aaa;
            margin: 12px 0;
        }

        /* ── Filigrane PROFORMA ── */
        .proforma-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 52px;
            font-weight: 900;
            color: rgba(200, 30, 30, 0.08);
            white-space: nowrap;
            pointer-events: none;
            z-index: 999;
            letter-spacing: 10px;
        }

        /* ── En-tête établissement ── */
        .entete {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .entete h3 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .entete p {
            font-size: 9.5px;
            color: #333;
            margin-top: 2px;
        }

        /* ── Titre du document ── */
        .titre-document {
            text-align: center;
            margin: 8px 0 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 6px;
        }
        .titre-document h4 {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .titre-document .num-dossier {
            font-size: 10px;
            color: #444;
            margin-top: 2px;
        }

        /* ── Badge PROFORMA / REÇU ── */
        .badge-statut {
            display: inline-block;
            padding: 2px 10px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
            margin-top: 3px;
            text-transform: uppercase;
        }
        .badge-proforma { background: #fff3cd; border: 1px solid #e6a800; color: #7a5800; }
        .badge-solde    { background: #d4edda; border: 1px solid #28a745; color: #155724; }

        /* ── Grille infos patient / facture ── */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
        }
        .info-grid td {
            padding: 3px 5px;
            border: none;
            vertical-align: top;
        }
        .info-grid .label {
            font-weight: bold;
            color: #444;
            width: 90px;
            white-space: nowrap;
        }
        .info-grid .value {
            color: #111;
        }
        .info-grid .col-sep {
            width: 20px;
        }

        /* ── Section motif ── */
        .section-motif {
            background: #f5f7fa;
            border-left: 3px solid #555;
            padding: 5px 8px;
            margin-bottom: 10px;
            font-size: 10px;
        }
        .section-motif .motif-titre {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            color: #555;
            margin-bottom: 2px;
        }
        .section-motif .motif-valeur {
            font-weight: bold;
            color: #111;
        }
        .section-motif .detail-valeur {
            color: #333;
            font-style: italic;
            margin-top: 1px;
        }

        /* ── Tableau des actes ── */
        .table-actes {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 10px;
        }
        .table-actes th {
            background: #e8e8e8;
            font-weight: bold;
            padding: 5px 6px;
            border: 1px solid #bbb;
            text-align: center;
            font-size: 9.5px;
        }
        .table-actes td {
            padding: 5px 6px;
            border: 1px solid #ccc;
            vertical-align: top;
        }
        .col-type    { width: 18%; font-style: italic; color: #444; }
        .col-libelle { width: 40%; }
        .col-medecin { width: 22%; color: #555; }
        .col-montant { width: 20%; text-align: right; font-weight: bold; }

        /* ── Lignes totaux ── */
        .tfoot-total td {
            padding: 5px 6px;
            border: 1px solid #bbb;
            font-size: 10.5px;
        }
        .row-total-general td  { background: #222; color: #fff; font-weight: bold; }
        .row-assurance td      { background: #f0f0f0; color: #555; font-size: 9.5px; }
        .row-avance td         { background: #e8f5e9; color: #1b5e20; }
        .row-reste-zero td     { background: #d4edda; color: #155724; font-weight: bold; }
        .row-reste-nonzero td  { background: #fff3cd; color: #7a5800; font-weight: bold; }

        /* ── Pied de facture ── */
        .pied-facture {
            display: table;
            width: 100%;
            margin-top: 10px;
            font-size: 10px;
        }
        .pied-facture .col-caissier {
            display: table-cell;
            width: 60%;
            vertical-align: bottom;
        }
        .pied-facture .col-date {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: bottom;
        }
        .signature-zone {
            margin-top: 20px;
            border-top: 1px dashed #aaa;
            padding-top: 4px;
            font-size: 9.5px;
            color: #555;
            text-align: center;
        }

        /* ── Notice impression ── */
        .notice-impression {
            margin-top: 6px;
            font-size: 9px;
            color: #555;
            font-style: italic;
            text-align: center;
        }

        /* ── Devise étrangère ── */
        .row-devise td {
            background: #fafafa;
            font-size: 9.5px;
            color: #444;
            border: 1px solid #ddd;
            font-style: italic;
        }

        /* ── Pied de page bas de document ── */
        footer {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #aaa;
            text-align: center;
            font-size: 9px;
            color: #555;
        }

        /* ── Titre de section (actes) ── */
        .section-titre-actes {
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #444;
            margin-bottom: 4px;
            padding: 3px 5px;
            background: #efefef;
            border-left: 2px solid #888;
        }

        /* ── Assurance info ── */
        .assurance-badge {
            display: inline-block;
            background: #e3f2fd;
            border: 1px solid #90caf9;
            color: #1565c0;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        @media print { body { font-size: 10.5px !important; } }
    </style>
</head>
<body>

@php
    /*
     * ── Variables calculées une seule fois ──
     *
     * $facture est un array (toArray() dans le controller).
     * On accède aux données avec $facture['cle'] ?? fallback.
     */

    // Devise et paiement en devise étrangère
    $devise         = $facture['devise']          ?? 'XAF';
    $montantDevise  = $facture['montant_devise']  ?? null;
    $tauxConversion = $facture['taux_conversion'] ?? null;
    $isPaiementDevise = ($devise !== 'XAF') && $montantDevise && $tauxConversion;

    // Montant dû par le patient (assurec si assurance, sinon montant total)
    $dettePatient = (!empty($facture['assurec']) && $facture['assurec'] > 0)
        ? $facture['assurec']
        : $facture['montant'];

    // Avance reçue et rendu monnaie (devise étrangère uniquement)
    $avanceFCFA = (float) ($facture['avance'] ?? 0);
    $remisFCFA  = $isPaiementDevise
        ? ((float)$montantDevise * (float)$tauxConversion)
        : $avanceFCFA;
    $renduFCFA  = $isPaiementDevise ? max(0, $remisFCFA - $dettePatient) : 0;

    // Lignes d'actes
    $lignes = $facture['lignes'] ?? [];

    /*
     * Labels lisibles par type d'acte.
     * Pour ajouter un nouveau type : ajoute juste une ligne ici.
     */
    $typeLabels = [
        'consultation'   => 'Consultation',
        'examen_labo'    => 'Examen labo',
        'soin_infirmier' => 'Soin infirmier',
        'imagerie'       => 'Imagerie',
        'acte_chirurgical' => 'Acte chirurgical',
    ];

    // Total des lignes d'actes
    $totalLignes = collect($lignes)->sum('montant');

    // Infos patient (array depuis le controller)
    $nomPatient    = trim(($patient['name'] ?? '') . ' ' . ($patient['prenom'] ?? ''));
    $numeroDossier = $patient['numero_dossier'] ?? $facture['numero'] ?? '—';
    $assurance     = $patient['assurance'] ?? $facture['assurance'] ?? null;
    $priseEnCharge = (float) ($patient['prise_en_charge'] ?? 0);

    // Motif et détails — c'est ici le cœur du correctif
    // Ces champs sont copiés depuis le patient lors de generate_consultation()
    $motif        = $facture['motif']        ?? $patient['motif']        ?? 'Consultation médicale';
    $detailsMotif = $facture['details_motif'] ?? $patient['details_motif'] ?? '';

    // Médecin, mode paiement
    $medecin      = $facture['medecin_r']    ?? '—';
    $modePaiement = $facture['mode_paiement'] ?? '—';

    // Caissier (user qui a créé la facture)
    $caissier = '';
    if (!empty($patient['user'])) {
        $caissier = trim(($patient['user']['prenom'] ?? '') . ' ' . ($patient['user']['name'] ?? ''));
    }

    // Date de la facture
    $dateFacture = !empty($facture['date_insertion'])
        ? \Carbon\Carbon::parse($facture['date_insertion'])->format('d/m/Y')
        : \Carbon\Carbon::parse($facture['created_at'])->format('d/m/Y');

    // Imprimée par
    $nomPrinter = '';
    if (!empty($printer)) {
        $nomPrinter = trim(($printer['name'] ?? ''));
    }
@endphp

{{--
    ════════════════════════════════════════════════════════════
    ARCHITECTURE : 2 copies côte à côte avec display:table.
    Chaque copie appelle le même bloc @include.

    POURQUOI PAS ob_start() ?
    Blade compile ses directives (@if, @foreach…) AVANT l'exécution PHP.
    ob_start() ne capture que ce qui est echo()'d après lui — mais Blade
    a déjà produit le HTML. Résultat : variable vide ou incomplète.

    SOLUTION retenue : macro Blade @include avec les variables explicites.
    Une seule source de vérité → zéro risque de désynchronisation.
    ════════════════════════════════════════════════════════════
--}}

{{-- ════════════ COPIE 1 — Original ════════════ --}
}
<div class="facture-copy">

    @if($is_proforma ?? false)
        <div class="proforma-watermark">PROFORMA</div>
    @endif

    {{-- ── EN-TÊTE ÉTABLISSEMENT ── --}}
    <div class="entete">
        <h3>Centre Médico-Chirurgical d'Urologie</h3>
        <p>Vallée Manga Bell — Douala-Bali</p>
        <p>Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945 &nbsp;|&nbsp; www.cmcu-cm.com</p>
    </div>

    {{-- ── TITRE DU DOCUMENT ── --}}
    <div class="titre-document">
        <h4>
            @if($is_proforma ?? false)
                Facture Proforma — {{ strtoupper($motif) }}
            @else
                Reçu — {{ strtoupper($motif) }}
            @endif
        </h4>
        <div class="num-dossier">N° Dossier : {{ $numeroDossier }}</div>
        <span class="{{ ($is_proforma ?? false) ? 'badge-statut badge-proforma' : 'badge-statut badge-solde' }}">
            {{ ($is_proforma ?? false) ? 'Proforma — Non soldé' : 'Soldé' }}
        </span>
    </div>

    {{-- ── INFORMATIONS PATIENT / FACTURE (2 colonnes) ── --}}
    <table class="info-grid">
        <tr>
            <td class="label">Patient</td>
            <td class="value">{{ $nomPatient }}</td>
            <td class="col-sep"></td>
            <td class="label">N° Facture</td>
            <td class="value">{{ $facture['numero'] ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ $dateFacture }}</td>
            <td class="col-sep"></td>
            <td class="label">Mode paiement</td>
            <td class="value">{{ ucfirst($modePaiement) }}</td>
        </tr>
        <tr>
            <td class="label">Médecin</td>
            <td class="value">{{ $medecin }}</td>
            <td class="col-sep"></td>
            @if($assurance)
                <td class="label">Assurance</td>
                <td class="value">
                    <span class="assurance-badge">{{ $assurance }}</span>
                    @if($priseEnCharge > 0)
                        <span style="font-size:9px; color:#555;"> ({{ $priseEnCharge }}%)</span>
                    @endif
                </td>
            @else
                <td class="label">Assurance</td>
                <td class="value" style="color:#999;">Aucune</td>
            @endif
        </tr>
    </table>

    {{-- ── MOTIF DE CONSULTATION ── --}}
    <div class="section-motif">
        <div class="motif-titre">Motif</div>
        <div class="motif-valeur">{{ $motif }}</div>
        @if($detailsMotif)
            <div class="detail-valeur">{{ $detailsMotif }}</div>
        @endif
    </div>

    {{-- ── TABLEAU DES ACTES ── --}}
    <div class="section-titre-actes">Détail des actes</div>

    <table class="table-actes">
        <thead>
            <tr>
                <th class="col-type">Type</th>
                <th class="col-libelle">Description/Detail</th>
                <th class="col-medecin">Médecin</th>
                <th class="col-montant">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @if(count($lignes) > 0)
                {{-- Cas avec lignes détaillées --}}
                @foreach($lignes as $ligne)
                    @php
                        $typeLabel = $typeLabels[$ligne['type_acte'] ?? ''] ?? ($ligne['type_acte'] ?? 'Acte');
                        $praticien = $ligne['medecin'] ?? $ligne['infirmiere'] ?? '—';
                    @endphp
                    <tr>
                        <td class="col-type">{{ $typeLabel }}</td>
                        <td class="col-libelle">
                            {{ $ligne['libelle'] ?? '—' }}
                        </td>
                        <td class="col-medecin">{{ $praticien }}</td>
                        <td class="col-montant">
                            {{ number_format((int)($ligne['montant'] ?? 0), 0, ',', ' ') }}
                        </td>
                    </tr>
                @endforeach
            @else
                {{-- Cas sans lignes : afficher la consultation de base --}}
                <tr>
                    <td class="col-type">{{ $motif }}</td>
                    <td class="col-libelle">
                        {{ $motif }}
                        @if($detailsMotif)
                            <br><span style="font-size:9px; color:#666;">{{ $detailsMotif }}</span>
                        @endif
                    </td>
                    <td class="col-medecin">{{ $medecin }}</td>
                    <td class="col-montant">
                        {{ number_format((int)($facture['montant'] ?? 0), 0, ',', ' ') }}
                    </td>
                </tr>
            @endif
        </tbody>

        {{-- ── TOTAUX ── --}}
        <tfoot class="tfoot-total">

            {{-- Total général --}}
            <tr class="row-total-general">
                <td colspan="3" style="text-align:right; font-weight:bold;">TOTAL GÉNÉRAL</td>
                <td style="text-align:right;">
                    {{ number_format((int)($facture['montant'] ?? 0), 0, ',', ' ') }} 
                </td>
            </tr>

            {{-- Part assurance (si applicable) --}}
            @if(!empty($facture['assurancec']) && $facture['assurancec'] > 0)
                <tr class="row-assurance">
                    <td colspan="3" style="text-align:right;">
                        Part assurance ({{ $priseEnCharge }}%) :
                    </td>
                    <td style="text-align:right;">
                        − {{ number_format((int)$facture['assurancec'], 0, ',', ' ') }} 
                    </td>
                </tr>
                <tr class="row-assurance">
                    <td colspan="3" style="text-align:right; font-weight:bold;">
                        Part patient :
                    </td>
                    <td style="text-align:right; font-weight:bold;">
                        {{ number_format((int)($facture['assurec'] ?? $facture['montant']), 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif

            {{-- Paiement en devise étrangère --}}
            <!--@if($isPaiementDevise)
                <tr class="row-devise">
                    <td colspan="3" style="text-align:right;">
                        Paiement en {{ $devise }} :
                        {{ number_format($montantDevise, 2, ',', ' ') }} {{ $devise }}
                        × {{ $tauxConversion }} =
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($remisFCFA, 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif-->

            {{-- Avance perçue --}}
            @if($avanceFCFA > 0)
                <tr style="background:#e8f5e9;">
                    <td colspan="3" style="text-align:right;">Avance perçue :</td>
                    <td style="text-align:right; color:#1b5e20; font-weight:bold;">
                        − {{ number_format((int)$avanceFCFA, 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif

            {{-- Rendu monnaie (devise étrangère seulement) --}}
            @if($renduFCFA > 0)
                <tr style="background:#e3f2fd;">
                    <td colspan="3" style="text-align:right;">Rendu monnaie :</td>
                    <td style="text-align:right; color:#1565c0; font-weight:bold;">
                        {{ number_format($renduFCFA, 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif

            {{-- Reste à payer --}}
            @php $resteFacture = (int)($facture['reste'] ?? 0); @endphp
            <tr class="{{ $resteFacture > 0 ? 'row-reste-nonzero' : 'row-reste-zero' }}">
                <td colspan="3" style="text-align:right; font-weight:bold;">Reste à payer :</td>
                <td style="text-align:right; font-weight:bold;">
                    {{ $resteFacture > 0 ? number_format($resteFacture, 0, ',', ' ') . ' FCFA' : '0 FCFA' }}
                </td>
            </tr>

        </tfoot>
    </table>

    {{-- ── PIED DE FACTURE ── --}}
    <div class="pied-facture">
        <div class="col-caissier">
            Caissier(e) : <strong>{{ $caissier ?: '—' }}</strong>
            @if(!empty($nomPrinter) && !($is_proforma ?? false))
                <br><span style="font-size:9px; color:#555;">Imprimé par : {{ $nomPrinter }}</span>
            @endif
        </div>
        <div class="col-date">
            Douala, le {{ $dateFacture }}
        </div>
    </div>

    {{-- Zone signature --}}
    <div class="signature-zone">Signature et cachet</div>

    {{-- Notice proforma --}}
    @if($is_proforma ?? false)
        <p class="notice-impression">
            ⚠ Document PROFORMA — Paiement en attente. Cette facture deviendra un reçu définitif après complet règlement.
        </p>
    @endif

</div>

{{-- ════════════ SÉPARATEUR ════════════ --}}
<hr class="separateur">

{{-- ════════════ COPIE 2 — Duplicata ════════════ --}}
<div class="facture-copy">

    @if($is_proforma ?? false)
        <div class="proforma-watermark">PROFORMA</div>
    @endif

    {{-- ── EN-TÊTE ÉTABLISSEMENT ── --}}
    <div class="entete">
        <h3>Centre Médico-Chirurgical d'Urologie</h3>
        <p>Vallée Manga Bell — Douala-Bali</p>
        <p>Tél : (+237) 233 423 389 / 674 068 988 / 698 873 945 &nbsp;|&nbsp; www.cmcu-cm.com</p>
    </div>

    {{-- ── TITRE DU DOCUMENT ── --}}
    <div class="titre-document">
        <h4>
            @if($is_proforma ?? false)
                Facture Proforma — {{ strtoupper($motif) }} 
            @else
                Reçu — {{ strtoupper($motif) }} 
            @endif
        </h4>
        <div class="num-dossier">N° Dossier : {{ $numeroDossier }}</div>
        <span class="{{ ($is_proforma ?? false) ? 'badge-statut badge-proforma' : 'badge-statut badge-solde' }}">
            {{ ($is_proforma ?? false) ? 'Proforma — Non soldé' : 'Soldé' }}
        </span>
    </div>

    {{-- ── INFORMATIONS PATIENT / FACTURE ── --}}
    <table class="info-grid">
        <tr>
            <td class="label">Patient</td>
            <td class="value">{{ $nomPatient }}</td>
            <td class="col-sep"></td>
            <td class="label">N° Facture</td>
            <td class="value">{{ $facture['numero'] ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ $dateFacture }}</td>
            <td class="col-sep"></td>
            <td class="label">Mode paiement</td>
            <td class="value">{{ ucfirst($modePaiement) }}</td>
        </tr>
        <tr>
            <td class="label">Médecin</td>
            <td class="value">{{ $medecin }}</td>
            <td class="col-sep"></td>
            @if($assurance)
                <td class="label">Assurance</td>
                <td class="value">
                    <span class="assurance-badge">{{ $assurance }}</span>
                    @if($priseEnCharge > 0)
                        <span style="font-size:9px; color:#555;"> ({{ $priseEnCharge }}%)</span>
                    @endif
                </td>
            @else
                <td class="label">Assurance</td>
                <td class="value" style="color:#999;">Aucune</td>
            @endif
        </tr>
    </table>

    {{-- ── MOTIF DE CONSULTATION ── --}}
    <div class="section-motif">
        <div class="motif-titre">Motif </div>
        <div class="motif-valeur">{{ $motif }}</div>
        @if($detailsMotif)
            <div class="detail-valeur">{{ $detailsMotif }}</div>
        @endif
    </div>

    {{-- ── TABLEAU DES ACTES ── --}}
    <div class="section-titre-actes">Détail des actes</div>

    <table class="table-actes">
        <thead>
            <tr>
                <th class="col-type">Type</th>
                <th class="col-libelle">Description</th>
                <th class="col-medecin">Médecin</th>
                <th class="col-montant">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @if(count($lignes) > 0)
                @foreach($lignes as $ligne)
                    @php
                        $typeLabel = $typeLabels[$ligne['type_acte'] ?? ''] ?? ($ligne['type_acte'] ?? 'Acte');
                        $praticien = $ligne['medecin'] ?? $ligne['infirmiere'] ?? '—';
                    @endphp
                    <tr>
                        <td class="col-type">{{ $typeLabel }}</td>
                        <td class="col-libelle">{{ $ligne['libelle'] ?? '—' }}</td>
                        <td class="col-medecin">{{ $praticien }}</td>
                        <td class="col-montant">
                            {{ number_format((int)($ligne['montant'] ?? 0), 0, ',', ' ') }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="col-type">{{ $motif }}</td>
                    <td class="col-libelle">
                      
                        @if($detailsMotif)
                            <br><span style="font-size:9px; color:#666;">{{ $detailsMotif }}</span>
                        @endif
                    </td>
                    <td class="col-medecin">{{ $medecin }}</td>
                    <td class="col-montant">
                        {{ number_format((int)($facture['montant'] ?? 0), 0, ',', ' ') }}
                    </td>
                </tr>
            @endif
        </tbody>
        <tfoot class="tfoot-total">
            <tr class="row-total-general">
                <td colspan="3" style="text-align:right; font-weight:bold;">TOTAL GÉNÉRAL</td>
                <td style="text-align:right;">
                    {{ number_format((int)($facture['montant'] ?? 0), 0, ',', ' ') }} 
                </td>
            </tr>
            @if(!empty($facture['assurancec']) && $facture['assurancec'] > 0)
                <tr class="row-assurance">
                    <td colspan="3" style="text-align:right;">Part assurance ({{ $priseEnCharge }}%) :</td>
                    <td style="text-align:right;">
                        − {{ number_format((int)$facture['assurancec'], 0, ',', ' ') }} 
                    </td>
                </tr>
                <tr class="row-assurance">
                    <td colspan="3" style="text-align:right; font-weight:bold;">Part patient :</td>
                    <td style="text-align:right; font-weight:bold;">
                        {{ number_format((int)($facture['assurec'] ?? $facture['montant']), 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif
            <!--@if($isPaiementDevise)
                <tr class="row-devise">
                    <td colspan="3" style="text-align:right;">
                        Paiement en {{ $devise }} :
                        {{ number_format($montantDevise, 2, ',', ' ') }} {{ $devise }}
                        × {{ $tauxConversion }} =
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($remisFCFA, 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif-->
            @if($avanceFCFA > 0)
                <tr style="background:#e8f5e9;">
                    <td colspan="3" style="text-align:right;">Avance perçue :</td>
                    <td style="text-align:right; color:#1b5e20; font-weight:bold;">
                        − {{ number_format((int)$avanceFCFA, 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif
            @if($renduFCFA > 0)
                <tr style="background:#e3f2fd;">
                    <td colspan="3" style="text-align:right;">Rendu monnaie :</td>
                    <td style="text-align:right; color:#1565c0; font-weight:bold;">
                        {{ number_format($renduFCFA, 0, ',', ' ') }} 
                    </td>
                </tr>
            @endif
            <tr class="{{ $resteFacture > 0 ? 'row-reste-nonzero' : 'row-reste-zero' }}">
                <td colspan="3" style="text-align:right; font-weight:bold;">Reste à payer :</td>
                <td style="text-align:right; font-weight:bold;">
                    {{ $resteFacture > 0 ? number_format($resteFacture, 0, ',', ' ') . ' FCFA' : '0 FCFA' }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="pied-facture">
        <div class="col-caissier">
            Caissier(e) : <strong>{{ $caissier ?: '—' }}</strong>
        </div>
        <div class="col-date">
            Douala, le {{ $dateFacture }}
        </div>
    </div>
    <div class="signature-zone">Signature et cachet</div>

    @if($is_proforma ?? false)
        <p class="notice-impression">
            ⚠ Document PROFORMA — Paiement en attente. Cette facture deviendra un reçu définitif après complet règlement.
        </p>
    @endif

</div>

{{-- ── PIED DE PAGE GLOBAL ── --}}
<footer>
    Centre Médico-Chirurgical d'Urologie situé à la Vallée Douala Manga Bell — Douala-Bali.<br>
    TEL : (+237) 233 423 389 / 674 068 988 / 698 873 945 &nbsp;|&nbsp; SITE WEB : http://www.cmcu-cm.com
</footer>

</body>
</html>