{{--
    admin/laboratoire/rapport.blade.php
    ─────────────────────────────────────────────────────────────────────
    Compte-rendu d'Examen de Laboratoire — ISO 15189 / SLMTA
    Used by:  LaboratoireController@printReport
    Printed via: route('laboratoire.rapport', $laboratoire->id)
    ─────────────────────────────────────────────────────────────────────
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Laboratoire — {{ $laboratoire->numero_bon }}</title>
    <style>
        /* ── Reset & base ─────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            padding: 0;
        }

        /* ── Page layout ──────────────────────────────────── */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 12mm 14mm 14mm;
            background: #fff;
        }

        /* ── Header / Letterhead ──────────────────────────── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #1D4ED8;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header-left h1 {
            font-size: 16px;
            font-weight: 700;
            color: #1D4ED8;
            letter-spacing: 0.5px;
        }
        .header-left p {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .header-right {
            text-align: right;
        }
        .header-right .bon-number {
            font-size: 13px;
            font-weight: 700;
            color: #1D4ED8;
        }
        .header-right p {
            font-size: 10px;
            color: #64748b;
        }

        /* ── Section titles ───────────────────────────────── */
        .section-title {
            background: #1D4ED8;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 10px;
            margin: 10px 0 6px;
            border-radius: 3px;
        }

        /* ── Info grid (patient + specimen) ──────────────── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px 12px;
            margin-bottom: 6px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 9px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .info-value {
            font-size: 11px;
            color: #1e293b;
            font-weight: 500;
            margin-top: 1px;
        }

        /* ── Status badge ─────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-valide  { background: #dcfce7; color: #15803d; }
        .badge-remis   { background: #ccfbf1; color: #0f766e; }
        .badge-en_cours { background: #dbeafe; color: #1d4ed8; }
        .badge-en_attente { background: #fef9c3; color: #854d0e; }

        /* ── Results table ────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10.5px;
        }
        table thead tr {
            background: #e0e7ff;
        }
        table thead th {
            padding: 5px 8px;
            text-align: left;
            font-size: 9.5px;
            font-weight: 700;
            color: #1d4ed8;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid #c7d2fe;
        }
        table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        table tbody tr:hover {
            background: #eff6ff;
        }
        table tbody td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        /* ── Flag colours ─────────────────────────────────── */
        .flag-normal   { color: #15803d; font-weight: 600; }
        .flag-bas      { color: #1d4ed8; font-weight: 700; }
        .flag-eleve    { color: #d97706; font-weight: 700; }
        .flag-critique { color: #dc2626; font-weight: 700; }

        /* ── Critical value alert box ─────────────────────── */
        .critical-box {
            border: 1.5px solid #fca5a5;
            background: #fff1f2;
            border-radius: 5px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }
        .critical-box .critical-title {
            color: #b91c1c;
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .critical-box ul {
            margin: 0;
            padding-left: 16px;
        }
        .critical-box ul li {
            font-size: 10px;
            color: #b91c1c;
            margin-bottom: 2px;
        }

        /* ── QCI / Quality control box ────────────────────── */
        .qci-box {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 6px 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qci-conforme     { border-color: #bbf7d0; background: #f0fdf4; }
        .qci-non_conforme { border-color: #fecdd3; background: #fff1f2; }
        .qci-non_effectue { border-color: #e2e8f0; background: #f8fafc; }

        /* ── Observations ─────────────────────────────────── */
        .observations-box {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px 12px;
            background: #f8fafc;
            font-size: 10.5px;
            color: #334155;
            line-height: 1.6;
            margin-bottom: 8px;
            white-space: pre-line;
        }

        /* ── Validation / signature block ─────────────────── */
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 14px;
        }
        .signature-block {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px 12px;
            background: #f8fafc;
        }
        .signature-block .sig-label {
            font-size: 9px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .signature-block .sig-value {
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
            margin-top: 2px;
        }
        .signature-block .sig-date {
            font-size: 9.5px;
            color: #64748b;
            margin-top: 1px;
        }
        .stamp-box {
            height: 50px;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 9px;
            font-style: italic;
        }

        /* ── Footer ───────────────────────────────────────── */
        .footer {
            margin-top: 16px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .footer p {
            font-size: 8.5px;
            color: #94a3b8;
        }
        .footer .confidential {
            font-size: 8px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Print styles ─────────────────────────────────── */
        @media print {
            body { padding: 0; }
            .page { padding: 10mm 12mm; }
            .no-print { display: none !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ══ HEADER / LETTERHEAD ══════════════════════════════════════ --}}
    <div class="header">
        <div class="header-left">
            <h1>LABORATOIRE D'ANALYSES MÉDICALES</h1>
            <p>Clinique / Centre Médical — Douala, Cameroun</p>
            <p>Tél : +237 XXX XXX XXX &nbsp;|&nbsp; Accrédité SLMTA/SLIPTA</p>
        </div>
        <div class="header-right">
            <p class="bon-number">Bon n° {{ $laboratoire->numero_bon }}</p>
            <p>Date d'émission : {{ now()->format('d/m/Y') }}</p>
            <p>Statut :
                <span class="badge badge-{{ $laboratoire->statut }}">
                    {{ $laboratoire->statut_label }}
                </span>
            </p>
        </div>
    </div>

    {{-- ══ PATIENT INFORMATION ═══════════════════════════════════════ --}}
    <div class="section-title">▸ Informations patient</div>
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Nom complet</span>
            <span class="info-value">
                {{ strtoupper($laboratoire->patient->name ?? '—') }}
                {{ $laboratoire->patient->prenom ?? '' }}
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">N° Dossier</span>
            <span class="info-value">{{ $laboratoire->patient->numero_dossier ?? '—' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Service prescripteur</span>
            <span class="info-value">{{ $laboratoire->prescription_source ?? '—' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Médecin prescripteur</span>
            <span class="info-value">
                @if($laboratoire->prescripteur)
                    Dr {{ $laboratoire->prescripteur->name }} {{ $laboratoire->prescripteur->prenom }}
                    @if($laboratoire->prescripteur->specialite)
                        — {{ $laboratoire->prescripteur->specialite }}
                    @endif
                @else
                    —
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Date de prescription</span>
            <span class="info-value">
                {{ $laboratoire->date_prescription
                    ? $laboratoire->date_prescription->format('d/m/Y')
                    : '—' }}
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Préparation requise</span>
            <span class="info-value">{{ $laboratoire->preparation_requise ?? '—' }}</span>
        </div>
    </div>

    {{-- ══ SPECIMEN INFORMATION ══════════════════════════════════════ --}}
    <div class="section-title">▸ Spécimen / Prélèvement (Phase pré-analytique)</div>
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Date & heure prélèvement</span>
            <span class="info-value">
                @if($laboratoire->date_prelevement)
                    {{ $laboratoire->date_prelevement->format('d/m/Y') }}
                    à {{ $laboratoire->heure_prelevement ?? $laboratoire->date_prelevement->format('H:i') }}
                @else
                    —
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Technicien préleveur</span>
            <span class="info-value">{{ $laboratoire->technicien_prelevement ?? '—' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Type de tube / contenant</span>
            <span class="info-value">{{ $laboratoire->tube_type ?? '—' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Site de prélèvement</span>
            <span class="info-value">{{ $laboratoire->site_prelevement ?? '—' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Statut du spécimen</span>
            <span class="info-value">
                @if($laboratoire->statut_specimen === 'rejeté')
                    <span style="color:#dc2626; font-weight:700;">
                        ✗ Rejeté — {{ $laboratoire->motif_rejet ?? '' }}
                    </span>
                @else
                    <span style="color:#15803d; font-weight:600;">✓ Accepté</span>
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Instrument utilisé</span>
            <span class="info-value">{{ $laboratoire->instrument_utilise ?? '—' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Lot réactif</span>
            <span class="info-value">{{ $laboratoire->lot_reactif ?? '—' }}</span>
        </div>
        @if($laboratoire->tat_minutes !== null)
        <div class="info-item">
            <span class="info-label">Délai d'exécution (TAT)</span>
            <span class="info-value">
                {{ floor($laboratoire->tat_minutes / 60) }}h
                {{ $laboratoire->tat_minutes % 60 }}min
            </span>
        </div>
        @endif
    </div>

    {{-- ══ CRITICAL VALUES ALERT ═════════════════════════════════════ --}}
    @if($laboratoire->has_critical_values ?? $laboratoire->hasCriticalValues())
    <div class="critical-box">
        <div class="critical-title">
            ⚠ VALEURS CRITIQUES — Notification immédiate requise
        </div>
        <ul>
            @foreach($laboratoire->valeurs_critiques as $vc)
            <li>
                <strong>{{ $vc['section'] ?? '' }} — {{ $vc['test'] ?? '' }} :</strong>
                {{ $vc['valeur'] ?? '' }} {{ $vc['unite'] ?? '' }}
            </li>
            @endforeach
        </ul>
        @if($laboratoire->clinicien_notifie)
        <p style="margin-top:4px; font-size:9.5px; color:#b91c1c;">
            Clinicien notifié : <strong>{{ $laboratoire->clinicien_notifie }}</strong>
            @if($laboratoire->date_notification)
                le {{ $laboratoire->date_notification->format('d/m/Y à H:i') }}
            @endif
        </p>
        @endif
    </div>
    @endif

    {{-- ══ QCI STATUS ════════════════════════════════════════════════ --}}
    <div class="qci-box qci-{{ $laboratoire->cqi_status ?? 'non_effectue' }}">
        <span style="font-size:10px; font-weight:600;">
            Contrôle Qualité Interne (CQI) :
        </span>
        <span style="font-size:10px; margin-left:4px;">
            @if($laboratoire->cqi_status === 'conforme')
                ✓ Conforme
            @elseif($laboratoire->cqi_status === 'non_conforme')
                ✗ Non conforme — {{ $laboratoire->cqi_note ?? '' }}
            @else
                Non effectué
            @endif
        </span>
    </div>

    {{-- ══ RESULTS BY SECTION ════════════════════════════════════════ --}}
    <div class="section-title">▸ Résultats des analyses (Phase analytique)</div>

    @forelse($sectionsActives as $sectionKey)
        @php
            $resultats = $laboratoire->getAttribute("{$sectionKey}_resultats") ?? [];
            $label     = $sections[$sectionKey] ?? ucfirst($sectionKey);
        @endphp

        @if(!empty($resultats))
        {{-- Section sub-header --}}
        <p style="font-size:10px; font-weight:700; color:#1D4ED8; margin:8px 0 4px;
                  border-left:3px solid #1D4ED8; padding-left:6px;">
            {{ strtoupper($label) }}
        </p>

        <table>
            <thead>
                <tr>
                    <th style="width:35%;">Examen / Test</th>
                    <th style="width:18%;">Résultat</th>
                    <th style="width:12%;">Unité</th>
                    <th style="width:22%;">Valeurs de référence</th>
                    <th style="width:13%; text-align:center;">Interprétation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultats as $row)
                @php
                    $flag      = $row['flag'] ?? 'normal';
                    $flagClass = match($flag) {
                        'bas'      => 'flag-bas',
                        'élevé', 'eleve' => 'flag-eleve',
                        'critique' => 'flag-critique',
                        default    => 'flag-normal',
                    };
                    $flagLabel = match($flag) {
                        'bas'      => '▼ BAS',
                        'élevé', 'eleve' => '▲ ÉLEVÉ',
                        'critique' => '⚠ CRITIQUE',
                        default    => 'Normal',
                    };
                @endphp
                <tr>
                    <td style="font-weight:{{ $flag !== 'normal' ? '600' : '400' }};">
                        {{ $row['test'] ?? '—' }}
                    </td>
                    <td style="font-weight:700; color:{{ $flag === 'critique' ? '#dc2626' : '#1e293b' }};">
                        {{ $row['valeur'] ?? '—' }}
                    </td>
                    <td style="color:#64748b;">{{ $row['unite'] ?? '—' }}</td>
                    <td style="color:#475569; font-size:9.5px;">
                        @if(!empty($row['ref_min']) || !empty($row['ref_max']))
                            {{ $row['ref_min'] ?? '' }} – {{ $row['ref_max'] ?? '' }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="{{ $flagClass }}" style="text-align:center; font-size:9.5px;">
                        {{ $flagLabel }}
                    </td>
                </tr>
                @endforeach

                {{-- Antibiogramme (bactériologie only) --}}
                @if($sectionKey === 'bacteriologie' && !empty($laboratoire->antibiogramme))
                <tr>
                    <td colspan="5" style="background:#fffbeb; font-size:10px; color:#78350f; padding:6px 8px;">
                        <strong>Antibiogramme :</strong>
                        {{ $laboratoire->antibiogramme }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif

    @empty
        <p style="font-size:10.5px; color:#64748b; padding:6px 0;">
            Aucun résultat enregistré pour ce bon.
        </p>
    @endforelse

    {{-- ══ OBSERVATIONS / INTERPRETATION ════════════════════════════ --}}
    @if($laboratoire->observations)
    <div class="section-title">▸ Observations &amp; interprétation</div>
    <div class="observations-box">{{ $laboratoire->observations }}</div>
    @endif

    {{-- ══ VALIDATION / SIGNATURE ════════════════════════════════════ --}}
    <div class="section-title">▸ Validation (Phase post-analytique)</div>

    <div class="signature-grid">
        {{-- Laborantin --}}
        <div class="signature-block">
            <p class="sig-label">Analysé et validé techniquement par</p>
            <p class="sig-value">
                {{ $laboratoire->laborantin->name ?? '—' }}
                {{ $laboratoire->laborantin->prenom ?? '' }}
            </p>
            @if($laboratoire->date_validation)
            <p class="sig-date">
                Le {{ $laboratoire->date_validation->format('d/m/Y à H:i') }}
            </p>
            @endif
            <div class="stamp-box">Signature / Cachet</div>
        </div>

        {{-- Biologiste --}}
        <div class="signature-block">
            <p class="sig-label">Validé médicalement (biologiste)</p>
            <p class="sig-value">{{ $laboratoire->valide_par ?? '—' }}</p>
            @if($laboratoire->date_validation)
            <p class="sig-date">
                Le {{ $laboratoire->date_validation->format('d/m/Y à H:i') }}
            </p>
            @endif
            <div class="stamp-box">Signature / Cachet</div>
        </div>
    </div>

    {{-- Result handoff info --}}
    @if($laboratoire->date_remise_resultat)
    <p style="margin-top:8px; font-size:10px; color:#475569;">
        Résultats remis le :
        <strong>{{ $laboratoire->date_remise_resultat->format('d/m/Y à H:i') }}</strong>
    </p>
    @endif

    {{-- ══ FOOTER ═════════════════════════════════════════════════════ --}}
    <div class="footer">
        <div>
            <p>Bon n° {{ $laboratoire->numero_bon }}
               &nbsp;|&nbsp; Patient : {{ strtoupper($laboratoire->patient->name ?? '') }}
               {{ $laboratoire->patient->prenom ?? '' }}
               &nbsp;|&nbsp; Dossier : {{ $laboratoire->patient->numero_dossier ?? '—' }}
            </p>
            <p>Imprimé le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
        <p class="confidential">Document médical confidentiel</p>
    </div>

</div>{{-- /.page --}}

{{-- Print button (hidden when actually printing) --}}
<div class="no-print"
     style="position:fixed; bottom:20px; right:20px; display:flex; gap:10px;">
    <button onclick="window.print()"
            style="background:#1D4ED8; color:#fff; border:none; padding:10px 20px;
                   border-radius:8px; font-size:13px; cursor:pointer; font-weight:600;">
        🖨 Imprimer
    </button>
    <a href="{{ route('laboratoire.show', $laboratoire->id) }}"
       style="background:#e2e8f0; color:#334155; border:none; padding:10px 20px;
              border-radius:8px; font-size:13px; cursor:pointer; font-weight:600;
              text-decoration:none; display:inline-flex; align-items:center;">
        ← Retour
    </a>
</div>

</body>
</html>