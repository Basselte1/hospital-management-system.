{{--
 * Vue : admin/factures/pdf/bilan_finale_pdf.blade.php
 *
 * Générée par : FactureFinaleController::exportBilanPdf()
 * Route       : POST /admin/facturation/finale/bilan-pdf
 *
 * Variables reçues du controller :
 *   - Collection  $lignesBilan  — chaque item est le retour de aggregerPatient()
 *                                 + clé 'patient' (objet Patient)
 *   - array       $totaux       — { total, avance, reste, assurancec,
 *                                    consultations, examens, soins }
 *   - Carbon      $date         — journée du bilan
 *   - string      $service      — filtre service ('', 'consultation', 'examen', 'soins')
 *
 * Structure d'un item $lignesBilan :
 *   patient, consultations, examens, soins, pharmacie, autres,
 *   total, assurancec, assurec, avance, reste, is_solde, lignesDetail
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bilan Journalier — {{ $date->translatedFormat('d F Y') }}</title>
    <style>
        /* ── Reset & base ───────────────────────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #1a1a2e;
            background: #fff;
            padding: 16px 20px;
        }

        /* ── En-tête clinique ───────────────────────────────────────────── */
        .header-clinique {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            border-bottom: 3px solid #4527A0;
            padding-bottom: 10px;
        }
        .header-left  { display: table-cell; width: 55%; vertical-align: middle; }
        .header-right { display: table-cell; width: 45%; vertical-align: middle; text-align: right; }

        .clinique-nom  { font-size: 15px; font-weight: 700; color: #4527A0; text-transform: uppercase; letter-spacing: 1px; }
        .clinique-sous { font-size: 9px; color: #607D8B; margin-top: 2px; }

        /* ── Titre document ─────────────────────────────────────────────── */
        .doc-title {
            background: linear-gradient(135deg, #4527A0, #5E35B1);
            color: #fff;
            text-align: center;
            padding: 9px 0;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .doc-title .doc-sub {
            font-size: 9.5px;
            font-weight: 400;
            opacity: .85;
            margin-top: 2px;
        }

        /* ── KPIs en ligne ──────────────────────────────────────────────── */
        .kpi-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            border: 1px solid #E8EAF6;
            border-radius: 6px;
            overflow: hidden;
        }
        .kpi-cell {
            display: table-cell;
            text-align: center;
            padding: 8px 4px;
            vertical-align: middle;
            border-right: 1px solid #E8EAF6;
        }
        .kpi-cell:last-child { border-right: none; }
        .kpi-label { font-size: 8.5px; color: #607D8B; font-weight: 600; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 3px; }
        .kpi-val   { font-size: 12px; font-weight: 700; }
        .kpi-fcfa  { font-size: 8px; color: #90A4AE; }

        .kpi-purple { background: #EDE7F6; }
        .kpi-purple .kpi-val { color: #4527A0; }

        .kpi-blue { background: #E3F2FD; }
        .kpi-blue .kpi-val { color: #1565C0; }

        .kpi-green { background: #E8F5E9; }
        .kpi-green .kpi-val { color: #2E7D32; }

        .kpi-red { background: #FFEBEE; }
        .kpi-red .kpi-val { color: #B71C1C; }

        .kpi-teal { background: #E0F2F1; }
        .kpi-teal .kpi-val { color: #00695C; }

        .kpi-amber { background: #FFF8E1; }
        .kpi-amber .kpi-val { color: #E65100; }

        /* ── Filtre service badge ────────────────────────────────────────── */
        .service-badge {
            display: inline-block;
            background: #EDE7F6;
            color: #4527A0;
            border-radius: 3px;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* ── Tableau principal ──────────────────────────────────────────── */
        table.main {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }
        table.main thead tr {
            background: #4527A0;
            color: #fff;
        }
        table.main thead th {
            padding: 6px 6px;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .3px;
            white-space: nowrap;
        }
        table.main thead th.right { text-align: right; }
        table.main thead th.center { text-align: center; }

        table.main tbody tr:nth-child(even) { background: #F5F0FF; }
        table.main tbody tr:nth-child(odd)  { background: #FAFAFA; }

        table.main tbody td {
            padding: 5px 6px;
            border-bottom: 1px solid #EDE7F6;
            vertical-align: middle;
        }
        table.main tbody td.right  { text-align: right; }
        table.main tbody td.center { text-align: center; }

        /* Statut badge */
        .badge-solde   { background: #E8F5E9; color: #1B5E20; border-radius: 3px; padding: 1px 5px; font-size: 8.5px; font-weight: 700; }
        .badge-proforma{ background: #FFF3E0; color: #E65100; border-radius: 3px; padding: 1px 5px; font-size: 8.5px; font-weight: 700; }

        /* Valeurs colorées */
        .val-consult  { color: #1565C0; font-weight: 600; }
        .val-examen   { color: #00695C; font-weight: 600; }
        .val-soin     { color: #BF360C; font-weight: 600; }
        .val-pharma   { color: #4A148C; font-weight: 600; }
        .val-total    { color: #263238; font-weight: 700; font-size: 11px; }
        .val-assur    { color: #00695C; }
        .val-avance   { color: #2E7D32; font-weight: 600; }
        .val-reste-0  { color: #1B5E20; font-weight: 700; }
        .val-reste-p  { color: #B71C1C; font-weight: 700; }

        /* ── Ligne totaux pied de tableau ───────────────────────────────── */
        table.main tfoot tr {
            background: #37474F;
            color: #fff;
        }
        table.main tfoot td {
            padding: 6px 6px;
            font-weight: 700;
            font-size: 10px;
            border-top: 2px solid #4527A0;
        }
        table.main tfoot td.right  { text-align: right; }
        table.main tfoot td.center { text-align: center; }

        /* ── Breakdown services ─────────────────────────────────────────── */
        .breakdown-row {
            display: table;
            width: 55%;
            margin-left: auto;
            margin-top: 14px;
            border: 1px solid #CFD8DC;
            border-radius: 5px;
            overflow: hidden;
        }
        .bd-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #ECEFF1;
        }
        .bd-row:last-child { border-bottom: none; }
        .bd-lbl { display: table-cell; padding: 5px 10px; color: #546E7A; font-size: 10px; }
        .bd-val { display: table-cell; padding: 5px 10px; text-align: right; font-weight: 700; font-size: 10px; }

        .bd-total-row .bd-lbl { background: #4527A0; color: #fff; font-size: 11px; font-weight: 700; }
        .bd-total-row .bd-val { background: #4527A0; color: #fff; font-size: 13px; font-weight: 700; }

        /* ── Pied de page ───────────────────────────────────────────────── */
        .footer {
            margin-top: 16px;
            border-top: 1px solid #CFD8DC;
            padding-top: 8px;
            font-size: 8.5px;
            color: #90A4AE;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; width: 60%; }
        .footer-right { display: table-cell; width: 40%; text-align: right; }

        /* ── Signature ──────────────────────────────────────────────────── */
        .signature-zone {
            margin-top: 20px;
            display: table;
            width: 100%;
        }
        .sig-cell { display: table-cell; width: 50%; text-align: center; padding: 8px; }
        .sig-label {
            font-size: 9.5px;
            font-weight: 600;
            color: #4527A0;
            text-transform: uppercase;
            border-top: 1px dashed #B0BEC5;
            padding-top: 5px;
            margin-top: 22px;
        }

        /* ── Aucune donnée ──────────────────────────────────────────────── */
        .empty-row td {
            text-align: center;
            padding: 24px;
            color: #90A4AE;
            font-style: italic;
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
        <div class="clinique-sous">Clinique Médicale — Soins &amp; Spécialités</div>
        <div class="clinique-sous" style="margin-top:2px;">Tél : +237 000 000 000 &nbsp;|&nbsp; BP 000, Yaoundé, Cameroun</div>
    </div>
    <div class="header-right">
        <div style="font-size:10px; color:#607D8B;">Édité le {{ now()->format('d/m/Y à H:i') }}</div>
        <div style="font-size:10px; color:#4527A0; font-weight:700; margin-top:3px;">
            Bilan du {{ $date->translatedFormat('d F Y') }}
        </div>
        @if($service)
        <div style="margin-top:4px;">
            <span class="service-badge">{{ ucfirst($service) }}</span>
        </div>
        @else
        <div style="font-size:9px; color:#90A4AE; margin-top:4px;">Tous les services</div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     TITRE DOCUMENT
══════════════════════════════════════════════════════════ --}}
<div class="doc-title">
    BILAN JOURNALIER — {{ strtoupper($date->translatedFormat('d F Y')) }}
    <div class="doc-sub">
        Récapitulatif financier · Toutes catégories de soins
        @if($service) · Service : {{ ucfirst($service) }} @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     KPIs GLOBAUX
══════════════════════════════════════════════════════════ --}}
<div class="kpi-row">
    <div class="kpi-cell kpi-purple">
        <div class="kpi-label">Patients</div>
        <div class="kpi-val">{{ $lignesBilan->count() }}</div>
        <div class="kpi-fcfa">du jour</div>
    </div>
    <div class="kpi-cell kpi-blue">
        <div class="kpi-label">Total brut</div>
        <div class="kpi-val">{{ number_format($totaux['total'] ?? 0, 0, ',', ' ') }}</div>
        <div class="kpi-fcfa">FCFA</div>
    </div>
    <div class="kpi-cell kpi-teal">
        <div class="kpi-label">Part assurance</div>
        <div class="kpi-val">{{ number_format($totaux['assurancec'] ?? 0, 0, ',', ' ') }}</div>
        <div class="kpi-fcfa">FCFA</div>
    </div>
    <div class="kpi-cell kpi-green">
        <div class="kpi-label">Encaissé</div>
        <div class="kpi-val">{{ number_format($totaux['avance'] ?? 0, 0, ',', ' ') }}</div>
        <div class="kpi-fcfa">FCFA</div>
    </div>
    <div class="kpi-cell kpi-red">
        <div class="kpi-label">Reste dû</div>
        <div class="kpi-val">{{ number_format($totaux['reste'] ?? 0, 0, ',', ' ') }}</div>
        <div class="kpi-fcfa">FCFA</div>
    </div>
    <div class="kpi-cell kpi-amber">
        <div class="kpi-label">Soldés</div>
        <div class="kpi-val">{{ $lignesBilan->where('is_solde', true)->count() }}</div>
        <div class="kpi-fcfa">patients</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     TABLEAU PRINCIPAL
══════════════════════════════════════════════════════════ --}}
<table class="main">
    <thead>
        <tr>
            <th style="width:6%;">N° Dos.</th>
            <th style="width:18%;">Patient</th>
            <th class="center" style="width:11%;">Consult.</th>
            <th class="center" style="width:11%;">Examens</th>
            <th class="center" style="width:11%;">Soins inf.</th>
            <th class="center" style="width:8%;">Pharma.</th>
            <th class="right"  style="width:10%;">Total</th>
            <th class="right"  style="width:9%;">P. Ass.</th>
            <th class="right"  style="width:9%;">Encaissé</th>
            <th class="right"  style="width:8%;">Reste</th>
            <th class="center" style="width:9%;">Statut</th>
        </tr>
    </thead>
    <tbody>
        @forelse($lignesBilan as $donnees)
        @php
            $patient = $donnees['patient'];
            $reste   = $donnees['reste'] ?? 0;
            $isSolde = $donnees['is_solde'] ?? false;
        @endphp
        <tr>
            <td style="font-size:8.5px; color:#607D8B;">
                {{ $patient->numero_dossier ?? '—' }}
            </td>
            <td>
                <strong>{{ strtoupper($patient->name ?? '') }} {{ $patient->prenom ?? '' }}</strong>
                @if($patient->assurance)
                <div style="font-size:8px; color:#00695C;">&#x26CF; {{ $patient->assurance }}</div>
                @endif
            </td>
            <td class="center">
                <span class="val-consult">
                    {{ $donnees['consultations'] > 0 ? number_format($donnees['consultations'], 0, ',', ' ') : '—' }}
                </span>
            </td>
            <td class="center">
                <span class="val-examen">
                    {{ $donnees['examens'] > 0 ? number_format($donnees['examens'], 0, ',', ' ') : '—' }}
                </span>
            </td>
            <td class="center">
                <span class="val-soin">
                    {{ $donnees['soins'] > 0 ? number_format($donnees['soins'], 0, ',', ' ') : '—' }}
                </span>
            </td>
            <td class="center">
                <span class="val-pharma">
                    {{ ($donnees['pharmacie'] ?? 0) > 0 ? number_format($donnees['pharmacie'], 0, ',', ' ') : '—' }}
                </span>
            </td>
            <td class="right val-total">
                {{ number_format($donnees['total'], 0, ',', ' ') }}
            </td>
            <td class="right val-assur">
                {{ $donnees['assurancec'] > 0 ? number_format($donnees['assurancec'], 0, ',', ' ') : '—' }}
            </td>
            <td class="right val-avance">
                {{ number_format($donnees['avance'], 0, ',', ' ') }}
            </td>
            <td class="right {{ $reste == 0 ? 'val-reste-0' : 'val-reste-p' }}">
                {{ number_format($reste, 0, ',', ' ') }}
            </td>
            <td class="center">
                @if($isSolde)
                    <span class="badge-solde">Soldé</span>
                @else
                    <span class="badge-proforma">Proforma</span>
                @endif
            </td>
        </tr>
        @empty
        <tr class="empty-row">
            <td colspan="11">Aucun patient facturé ce jour</td>
        </tr>
        @endforelse
    </tbody>

    {{-- ── Totaux ── --}}
    <tfoot>
        <tr>
            <td colspan="2">TOTAUX</td>
            <td class="center" style="color:#BBDEFB;">{{ number_format($totaux['consultations'] ?? 0, 0, ',', ' ') }}</td>
            <td class="center" style="color:#B2DFDB;">{{ number_format($totaux['examens'] ?? 0, 0, ',', ' ') }}</td>
            <td class="center" style="color:#FFE0B2;">{{ number_format($totaux['soins'] ?? 0, 0, ',', ' ') }}</td>
            <td class="center" style="color:#E1BEE7;">—</td>
            <td class="right" style="font-size:11px;">{{ number_format($totaux['total'] ?? 0, 0, ',', ' ') }}</td>
            <td class="right" style="color:#B2DFDB;">{{ number_format($totaux['assurancec'] ?? 0, 0, ',', ' ') }}</td>
            <td class="right" style="color:#C8E6C9;">{{ number_format($totaux['avance'] ?? 0, 0, ',', ' ') }}</td>
            <td class="right" style="color:#FFCDD2;">{{ number_format($totaux['reste'] ?? 0, 0, ',', ' ') }}</td>
            <td class="center">{{ $lignesBilan->count() }} pat.</td>
        </tr>
    </tfoot>
</table>

{{-- ══════════════════════════════════════════════════════════
     BREAKDOWN PAR SERVICE
══════════════════════════════════════════════════════════ --}}
<div class="breakdown-row">
    <div class="bd-row bd-total-row">
        <div class="bd-lbl">TOTAL JOURNÉE</div>
        <div class="bd-val">{{ number_format($totaux['total'] ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="bd-row">
        <div class="bd-lbl">Consultations</div>
        <div class="bd-val val-consult">{{ number_format($totaux['consultations'] ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="bd-row">
        <div class="bd-lbl">Examens biologiques / imagerie</div>
        <div class="bd-val val-examen">{{ number_format($totaux['examens'] ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="bd-row">
        <div class="bd-lbl">Soins infirmiers / Actes</div>
        <div class="bd-val val-soin">{{ number_format($totaux['soins'] ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="bd-row" style="background:#F5F5F5;">
        <div class="bd-lbl" style="color:#00695C;">Part assurances</div>
        <div class="bd-val val-assur">{{ number_format($totaux['assurancec'] ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="bd-row" style="background:#F1F8E9;">
        <div class="bd-lbl" style="color:#2E7D32;">Total encaissé</div>
        <div class="bd-val val-avance">{{ number_format($totaux['avance'] ?? 0, 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="bd-row" style="background:#FFEBEE;">
        <div class="bd-lbl" style="color:#B71C1C;">Reste à recouvrer</div>
        <div class="bd-val {{ ($totaux['reste'] ?? 0) == 0 ? 'val-reste-0' : 'val-reste-p' }}">
            {{ number_format($totaux['reste'] ?? 0, 0, ',', ' ') }} FCFA
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SIGNATURES
══════════════════════════════════════════════════════════ --}}
<div class="signature-zone">
    <div class="sig-cell">
        <div class="sig-label">Le Responsable Financier</div>
    </div>
    <div class="sig-cell">
        <div class="sig-label">Cachet &amp; Signature Direction</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     PIED DE PAGE
══════════════════════════════════════════════════════════ --}}
<div class="footer">
    <div class="footer-left">
        Document généré le {{ now()->format('d/m/Y à H:i') }} —
        Bilan journalier du {{ $date->translatedFormat('d F Y') }}
        @if($service) — Service : {{ ucfirst($service) }} @endif
    </div>
    <div class="footer-right">
        Centre Médical CMCU · Yaoundé · Cameroun
    </div>
</div>

</body>
</html>