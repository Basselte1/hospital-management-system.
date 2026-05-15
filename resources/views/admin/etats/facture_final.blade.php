{{--
 * Vue : admin/factures/pdf/facture_finale_pdf.blade.php
 *
 * Générée par : FactureFinaleController::exportPdf()
 * Route       : GET /admin/facturation/final/pdf?patient=X&start=Y&end=Z
 *
 * Variables reçues du controller :
 *   - Patient        $patient      — modèle Patient avec factures chargées
 *   - array          $donnees      — agrégat issu de aggregerPatient()
 *   - Carbon         $startDate    — début de période
 *   - Carbon         $endDate      — fin de période
 *   - bool           $isProforma   — vrai si reste > 0
 *
 * Structure de $donnees :
 *   consultations, examens, soins, pharmacie, autres,
 *   total, assurancec, assurec, avance, reste, is_solde,
 *   lignesDetail (Collection de ['type','libelle','montant','source'])
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Facture Finale — {{ $patient->name }} {{ $patient->prenom }}</title>
    <style>
        /* ── Reset & base ───────────────────────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
            padding: 20px 24px;
        }

        /* ── En-tête clinique ───────────────────────────────────────────── */
        .header-clinique {
            display: table;
            width: 100%;
            margin-bottom: 18px;
            border-bottom: 3px solid #4527A0;
            padding-bottom: 12px;
        }
        .header-left  { display: table-cell; width: 60%; vertical-align: middle; }
        .header-right { display: table-cell; width: 40%; vertical-align: middle; text-align: right; }

        .clinique-nom {
            font-size: 16px;
            font-weight: 700;
            color: #4527A0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .clinique-sous {
            font-size: 10px;
            color: #607D8B;
            margin-top: 2px;
        }

        .badge-proforma {
            display: inline-block;
            background: #FFF3E0;
            color: #E65100;
            border: 1.5px solid #E65100;
            border-radius: 4px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
        }
        .badge-finale {
            display: inline-block;
            background: #E8F5E9;
            color: #1B5E20;
            border: 1.5px solid #2E7D32;
            border-radius: 4px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        /* ── Titre document ─────────────────────────────────────────────── */
        .doc-title {
            background: linear-gradient(135deg, #4527A0, #5E35B1);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            border-radius: 4px;
            margin-bottom: 16px;
        }
        .doc-title .doc-subtitle {
            font-size: 10px;
            font-weight: 400;
            opacity: .85;
            margin-top: 3px;
        }

        /* ── Bloc patient ───────────────────────────────────────────────── */
        .patient-bloc {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            border: 1px solid #CFD8DC;
            border-radius: 6px;
            padding: 10px 12px;
            background: #F8F9FF;
        }
        .patient-col { display: table-cell; width: 50%; vertical-align: top; }
        .patient-row { margin-bottom: 4px; font-size: 11px; }
        .patient-row .lbl { color: #607D8B; font-weight: 600; width: 110px; display: inline-block; }
        .patient-row .val { color: #263238; font-weight: 500; }

        /* ── Section titre ──────────────────────────────────────────────── */
        .section-title {
            background: #EDE7F6;
            color: #4527A0;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 6px 10px;
            border-left: 4px solid #4527A0;
            margin-bottom: 8px;
            margin-top: 14px;
        }

        /* ── Tableau détail lignes ──────────────────────────────────────── */
        table.detail {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 6px;
        }
        table.detail thead tr {
            background: #4527A0;
            color: #fff;
        }
        table.detail thead th {
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        table.detail thead th.right { text-align: right; }

        table.detail tbody tr:nth-child(even) { background: #F3E5F5; }
        table.detail tbody tr:nth-child(odd)  { background: #FAF7FF; }

        table.detail tbody td {
            padding: 5px 8px;
            border-bottom: 1px solid #EDE7F6;
            vertical-align: top;
        }
        table.detail tbody td.right { text-align: right; font-weight: 600; }

        /* Badge type de ligne */
        .type-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            border-radius: 3px;
            padding: 1px 5px;
            text-transform: uppercase;
        }
        .type-consultation { background: #BBDEFB; color: #1565C0; }
        .type-examen       { background: #C8E6C9; color: #1B5E20; }
        .type-soin         { background: #FFE0B2; color: #BF360C; }
        .type-pharmacie    { background: #E1BEE7; color: #4A148C; }
        .type-autre        { background: #ECEFF1; color: #37474F; }

        /* ── Bloc récapitulatif par service ─────────────────────────────── */
        .recap-grid {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }
        .recap-cell {
            display: table-cell;
            width: 20%;
            padding: 6px 5px;
            text-align: center;
            vertical-align: middle;
        }
        .recap-inner {
            border-radius: 5px;
            padding: 7px 4px;
        }
        .recap-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 3px; }
        .recap-val   { font-size: 13px; font-weight: 700; }
        .recap-fcfa  { font-size: 9px; font-weight: 400; }

        .rc-consultation { background: #E3F2FD; }
        .rc-consultation .recap-label { color: #1565C0; }
        .rc-consultation .recap-val   { color: #1565C0; }

        .rc-examen { background: #E0F2F1; }
        .rc-examen .recap-label { color: #00695C; }
        .rc-examen .recap-val   { color: #00695C; }

        .rc-soin { background: #FFF3E0; }
        .rc-soin .recap-label { color: #BF360C; }
        .rc-soin .recap-val   { color: #BF360C; }

        .rc-pharmacie { background: #F3E5F5; }
        .rc-pharmacie .recap-label { color: #4A148C; }
        .rc-pharmacie .recap-val   { color: #4A148C; }

        .rc-autre { background: #ECEFF1; }
        .rc-autre .recap-label { color: #37474F; }
        .rc-autre .recap-val   { color: #37474F; }

        /* ── Tableau financier final ─────────────────────────────────────── */
        table.financier {
            width: 55%;
            margin-left: auto;
            border-collapse: collapse;
            font-size: 11px;
        }
        table.financier tr td { padding: 5px 10px; }
        table.financier tr:nth-child(odd)  { background: #FAFAFA; }
        table.financier tr:nth-child(even) { background: #F5F5F5; }
        table.financier .lbl-col { color: #546E7A; width: 55%; }
        table.financier .val-col { text-align: right; font-weight: 600; }

        .total-row { background: #4527A0 !important; }
        .total-row td { color: #fff !important; font-size: 13px; font-weight: 700; padding: 7px 10px; }

        .reste-zero    { color: #1B5E20; font-weight: 700; }
        .reste-nonzero { color: #B71C1C; font-weight: 700; }

        .assurancec-val { color: #00695C; font-weight: 600; }
        .encaisse-val   { color: #1B5E20; font-weight: 600; }

        /* ── Pied de page ───────────────────────────────────────────────── */
        .footer-note {
            margin-top: 20px;
            border-top: 1px solid #CFD8DC;
            padding-top: 10px;
            font-size: 9px;
            color: #90A4AE;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; width: 60%; vertical-align: bottom; }
        .footer-right { display: table-cell; width: 40%; text-align: right; vertical-align: bottom; }

        .signature-zone {
            margin-top: 24px;
            display: table;
            width: 100%;
        }
        .sig-cell {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 8px;
        }
        .sig-label {
            font-size: 10px;
            font-weight: 600;
            color: #4527A0;
            text-transform: uppercase;
            border-top: 1px dashed #B0BEC5;
            padding-top: 6px;
            margin-top: 28px;
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════════════════
     EN-TÊTE CLINIQUE
══════════════════════════════════════════════════════════ --}}
<div class="header-clinique">
    <div class="header-left">
        <div class="clinique-nom">Centre Médical CMCU</div>
        <div class="clinique-sous">Clinique Médicale — Soins & Spécialités</div>
        <div class="clinique-sous" style="margin-top:3px;">Tél : +237 000 000 000 &nbsp;|&nbsp; BP 000, Yaoundé, Cameroun</div>
    </div>
    <div class="header-right">
        @if($isProforma)
            <div class="badge-proforma">PROFORMA</div>
        @else
            <div class="badge-finale">FACTURE FINALE</div>
        @endif
        <div style="font-size:10px; color:#607D8B; margin-top:6px;">
            Date d'émission : {{ now()->format('d/m/Y à H:i') }}
        </div>
        <div style="font-size:10px; color:#607D8B; margin-top:2px;">
            Période : {{ $startDate->format('d/m/Y') }} — {{ $endDate->format('d/m/Y') }}
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     TITRE DOCUMENT
══════════════════════════════════════════════════════════ --}}
<div class="doc-title">
    {{ $isProforma ? 'FACTURE PROFORMA — RÉCAPITULATIF CONSOLIDÉ' : 'FACTURE FINALE — RÉCAPITULATIF CONSOLIDÉ' }}
    <div class="doc-subtitle">Consultations · Examens · Soins infirmiers · Pharmacie</div>
</div>

{{-- ══════════════════════════════════════════════════════════
     BLOC PATIENT
══════════════════════════════════════════════════════════ --}}
<div class="patient-bloc">
    <div class="patient-col">
        <div class="patient-row">
            <span class="lbl">N° Dossier :</span>
            <span class="val">{{ $patient->numero_dossier ?? '—' }}</span>
        </div>
        <div class="patient-row">
            <span class="lbl">Patient :</span>
            <span class="val" style="font-weight:700; font-size:12px;">
                {{ strtoupper($patient->name ?? '') }} {{ $patient->prenom ?? '' }}
            </span>
        </div>
        <div class="patient-row">
            <span class="lbl">Médecin réf. :</span>
            <span class="val">{{ $patient->medecin_r ?? '—' }}</span>
        </div>
    </div>
    <div class="patient-col">
        @if($patient->assurance)
        <div class="patient-row">
            <span class="lbl">Assurance :</span>
            <span class="val" style="color:#00695C; font-weight:600;">{{ $patient->assurance }}</span>
        </div>
        <div class="patient-row">
            <span class="lbl">N° Assurance :</span>
            <span class="val">{{ $patient->numero_assurance ?? '—' }}</span>
        </div>
        <div class="patient-row">
            <span class="lbl">Prise en charge :</span>
            <span class="val">{{ $patient->prise_en_charge ?? 0 }} %</span>
        </div>
        @else
        <div class="patient-row">
            <span class="lbl">Mode paiement :</span>
            <span class="val">{{ ucfirst($patient->mode_paiement ?? 'espèce') }}</span>
        </div>
        @endif
        @if($patient->demarcheur)
        <div class="patient-row">
            <span class="lbl">Démarcheur :</span>
            <span class="val">{{ $patient->demarcheur }}</span>
        </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     RÉCAPITULATIF PAR SERVICE
══════════════════════════════════════════════════════════ --}}
<div class="section-title">Récapitulatif par service</div>

<div class="recap-grid">
    <div class="recap-cell">
        <div class="recap-inner rc-consultation">
            <div class="recap-label">Consultations</div>
            <div class="recap-val">{{ number_format($donnees['consultations'], 0, ',', ' ') }}</div>
            <div class="recap-fcfa" style="color:#1565C0;">FCFA</div>
        </div>
    </div>
    <div class="recap-cell">
        <div class="recap-inner rc-examen">
            <div class="recap-label">Examens</div>
            <div class="recap-val">{{ number_format($donnees['examens'], 0, ',', ' ') }}</div>
            <div class="recap-fcfa" style="color:#00695C;">FCFA</div>
        </div>
    </div>
    <div class="recap-cell">
        <div class="recap-inner rc-soin">
            <div class="recap-label">Soins inf.</div>
            <div class="recap-val">{{ number_format($donnees['soins'], 0, ',', ' ') }}</div>
            <div class="recap-fcfa" style="color:#BF360C;">FCFA</div>
        </div>
    </div>
    <div class="recap-cell">
        <div class="recap-inner rc-pharmacie">
            <div class="recap-label">Pharmacie</div>
            <div class="recap-val">{{ number_format($donnees['pharmacie'] ?? 0, 0, ',', ' ') }}</div>
            <div class="recap-fcfa" style="color:#4A148C;">FCFA</div>
        </div>
    </div>
    <div class="recap-cell">
        <div class="recap-inner rc-autre">
            <div class="recap-label">Autres</div>
            <div class="recap-val">{{ number_format($donnees['autres'] ?? 0, 0, ',', ' ') }}</div>
            <div class="recap-fcfa" style="color:#37474F;">FCFA</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     TABLEAU DÉTAIL DES LIGNES
══════════════════════════════════════════════════════════ --}}
@if($donnees['lignesDetail']->isNotEmpty())
<div class="section-title">Détail des prestations</div>

<table class="detail">
    <thead>
        <tr>
            <th style="width:12%;">Réf.</th>
            <th style="width:15%;">Catégorie</th>
            <th>Prestation / Libellé</th>
            <th class="right" style="width:18%;">Montant (FCFA)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($donnees['lignesDetail'] as $ligne)
        @php
            $typeClass = match($ligne['type'] ?? '') {
                'consultation' => 'type-consultation',
                'examen'       => 'type-examen',
                'soin'         => 'type-soin',
                'pharmacie'    => 'type-pharmacie',
                default        => 'type-autre',
            };
            $typeLabel = match($ligne['type'] ?? '') {
                'consultation' => 'Consultation',
                'examen'       => 'Examen',
                'soin'         => 'Soin inf.',
                'pharmacie'    => 'Pharmacie',
                default        => 'Autre',
            };
        @endphp
        <tr>
            <td style="font-size:9px; color:#607D8B;">{{ $ligne['source'] ?? '—' }}</td>
            <td><span class="type-badge {{ $typeClass }}">{{ $typeLabel }}</span></td>
            <td>{{ $ligne['libelle'] ?? '—' }}</td>
            <td class="right">{{ number_format($ligne['montant'] ?? 0, 0, ',', ' ') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- ══════════════════════════════════════════════════════════
     TABLEAU FINANCIER
══════════════════════════════════════════════════════════ --}}
<div class="section-title">Synthèse financière</div>

<table class="financier">
    {{-- Total brut --}}
    <tr class="total-row">
        <td class="lbl-col">TOTAL GÉNÉRAL</td>
        <td class="val-col">{{ number_format($donnees['total'], 0, ',', ' ') }} FCFA</td>
    </tr>

    @if($patient->assurance)
    {{-- Part assurance --}}
    <tr>
        <td class="lbl-col">Part prise en charge ({{ $patient->prise_en_charge ?? 0 }} %)</td>
        <td class="val-col assurancec-val">{{ number_format($donnees['assurancec'], 0, ',', ' ') }} FCFA</td>
    </tr>
    {{-- Part patient --}}
    <tr>
        <td class="lbl-col">Part à la charge du patient</td>
        <td class="val-col">{{ number_format($donnees['assurec'], 0, ',', ' ') }} FCFA</td>
    </tr>
    @endif

    {{-- Avance / Encaissé --}}
    <tr>
        <td class="lbl-col">Montant encaissé</td>
        <td class="val-col encaisse-val">{{ number_format($donnees['avance'], 0, ',', ' ') }} FCFA</td>
    </tr>

    {{-- Reste --}}
    <tr style="background:#FFF9C4 !important;">
        <td class="lbl-col" style="font-weight:700; color:#37474F;">Reste à payer</td>
        <td class="val-col {{ $donnees['reste'] == 0 ? 'reste-zero' : 'reste-nonzero' }}" style="font-size:14px;">
            {{ number_format($donnees['reste'], 0, ',', ' ') }} FCFA
            @if($donnees['reste'] == 0)
                &nbsp;&#10003;
            @endif
        </td>
    </tr>
</table>

{{-- ══════════════════════════════════════════════════════════
     ZONES DE SIGNATURE
══════════════════════════════════════════════════════════ --}}
<div class="signature-zone">
    <div class="sig-cell">
        <div class="sig-label">Le Caissier / Responsable</div>
    </div>
    <div class="sig-cell">
        <div class="sig-label">Cachet & Signature Direction</div>
    </div>
    <div class="sig-cell">
        <div class="sig-label">Le Patient / Représentant</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     PIED DE PAGE
══════════════════════════════════════════════════════════ --}}
<div class="footer-note">
    <div class="footer-left">
        Document généré le {{ now()->format('d/m/Y à H:i') }} —
        {{ $isProforma ? 'DOCUMENT NON DÉFINITIF — Solde restant dû' : 'DOCUMENT DÉFINITIF — Reçu de paiement intégral' }}
    </div>
    <div class="footer-right">
        Centre Médical CMCU · Yaoundé · Cameroun
    </div>
</div>

</body>
</html>