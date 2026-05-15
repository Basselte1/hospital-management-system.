<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Facture Acte / Soin Infirmier</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
        }
        .container {
            border: 1px solid #000;
            padding: 12px;
            margin-bottom: 20px;
        }
        .text-center { text-align: center; }
        .text-left   { text-align: left; }
        .text-right  { text-align: right; }
        h6 { margin: 3px 0; font-size: 11px; }
        h5 { margin: 5px 0; font-size: 12px; }
        h4 { margin: 5px 0; font-size: 13px; }
        .bold { font-weight: bold; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 6px 8px;
            border: 1px solid #000;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .invoice-id {
            color: #000;
            margin: 10px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .notices  { margin-top: 15px; padding-left: 6px; }
        .proforma {
            font-size: 36px;
            font-weight: 900;
            color: rgba(180, 10, 10, 0.12);
            text-align: center;
            letter-spacing: 10px;
            margin: 8px 0;
        }
        .tfoot-total td { font-weight: bold; font-size: 12px; }
        .tfoot-sub  td  { font-size: 10px; color: #444; }
        footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #aaa;
            text-align: center;
            font-size: 10px;
            color: #333;
        }
        .logo {
            width: 40px;
            display: block;
            margin: 0 auto 8px;
        }
    </style>
</head>
<body>

{{--
    ════════════════════════════════════════════════════════════
    FICHIER : resources/views/pdf/factures/acte.blade.php
    USAGE   : Appelé par FactureActeController::exportPdf()
              via la route facturation.acte_pdf

    VARIABLES attendues (tableau associatif depuis le contrôleur) :
      $facture  → instance FactureActe (avec lignes chargées)
      $isProforma → bool (reste > 0)

    LOGIQUE PDF :
      • Deux copies côte à côte sur la même page (comme consultation.blade.php)
      • Tableau de détail des lignes (libelle, infirmière, médecin, montant×quantité)
      • Bloc assurance conditionnel
      • Totaux (montant total, avance, reste)
      • Mention PROFORMA en filigrane si non soldé
    ════════════════════════════════════════════════════════════
--}}

@php
    // ── Données calculées une seule fois ───────────────────────────────────
    $lignes        = $facture->lignes ?? collect();
    $isProforma    = $facture->reste > 0;
    $montantTotal  = (int) ($facture->montant_total ?? 0);
    $avance        = (int) ($facture->avance        ?? 0);
    $assurancec    = (int) ($facture->assurancec     ?? 0);
    $assurec       = (int) ($facture->assurec        ?? 0);
    $reste         = (int) ($facture->reste          ?? 0);
    $patientName   = trim(($facture->patient->name ?? '') . ' ' . ($facture->patient->prenom ?? ''))
                     ?: ($facture->patient_name ?? '—');
    $numeroDossier = $facture->patient->numero_dossier
                     ?? $facture->patient_numero_dossier
                     ?? '—';
    $caissier      = trim(($facture->user->prenom ?? '') . ' ' . ($facture->user->name ?? ''))
                     ?: '—';
    $dateFacture   = $facture->created_at
                        ? \Carbon\Carbon::parse($facture->created_at)->format('d/m/Y')
                        : date('d/m/Y');
@endphp

{{-- ════════════ MACRO : UNE COPIE (réutilisée deux fois) ════════════ --}}
@php
    /**
     * On génère le contenu une fois dans une variable pour l'afficher deux fois.
     * Blade ne supporte pas de composants anonymes dans les vues PDF DomPDF/Browsershot,
     * donc on utilise une closure rendue via ob_start().
     * Alternative propre : un partial Blade @include.
     * → On utilise @include pour rester maintenable.
     */
@endphp

{{-- Bloc réutilisable via un sous-template : on intègre directement ici
     pour éviter un fichier partiel supplémentaire à déployer.
     Les deux copies sont identiques ; la seconde est la copie patient. --}}

<div style="display: table; width: 100%;">

    {{-- ══════════════ PREMIÈRE COPIE — Caisse ══════════════ --}}
    <div style="display: table-cell; width: 48%; padding: 2%; vertical-align: top;">
        <div class="container">

            {{-- En-tête établissement --}}
            <div class="text-center">
                @if(file_exists(public_path('admin/images/logo.jpg')))
                    <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="Logo CMCU">
                @endif
                <h6 class="bold">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</h6>
                <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
                <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
                <h6>www.cmcu-cm.com</h6>
            </div>

            {{-- Titre --}}
            <div class="text-center">
                @if($isProforma)
                    <div class="proforma">PROFORMA</div>
                @endif
                <h6 class="invoice-id">
                    {{ $isProforma ? 'PROFORMA ACTE' : 'RECU ACTE / SOIN INFIRMIER' }}
                    — N° {{ $facture->numero ?? $numeroDossier }}
                </h6>
                <h6>DOSSIER N° {{ $numeroDossier }}</h6>
            </div>

            {{-- Assurance --}}
            @if($assurancec > 0)
                <h6 class="text-center">
                    ASSURANCE — PART PRISE EN CHARGE : {{ number_format($assurancec, 0, ',', ' ') }} FCFA
                    | PART PATIENT : {{ number_format($assurec, 0, ',', ' ') }} FCFA
                </h6>
            @endif

            {{-- Tableau de détail des lignes actes --}}
            @if($lignes->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th style="width:38%">Acte / Soin</th>
                        <th style="width:20%">Intervenant</th>
                        <th style="width:12%; text-align:center;">Qté</th>
                        <th style="width:15%; text-align:right;">P.U.</th>
                        <th style="width:15%; text-align:right;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->libelle }}</td>
                        <td style="font-size:10px;">
                            {{ $ligne->medecin ?? $ligne->infirmiere ?? '—' }}
                        </td>
                        <td style="text-align:center;">{{ $ligne->quantite ?? 1 }}</td>
                        <td style="text-align:right;">{{ number_format((int)$ligne->montant, 0, ',', ' ') }}</td>
                        <td style="text-align:right; font-weight:600;">
                            {{ number_format((int)$ligne->montant * ($ligne->quantite ?? 1), 0, ',', ' ') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    {{-- Ligne assurance (conditionnelle) --}}
                    @if($assurancec > 0)
                    <tr class="tfoot-sub">
                        <td colspan="4" class="text-right">Part assurance ({{ $facture->prise_en_charge ?? 0 }}%) :</td>
                        <td class="text-right">{{ number_format($assurancec, 0, ',', ' ') }}</td>
                    </tr>
                    <tr class="tfoot-sub">
                        <td colspan="4" class="text-right">Part patient :</td>
                        <td class="text-right">{{ number_format($assurec, 0, ',', ' ') }}</td>
                    </tr>
                    @endif
                    <tr class="tfoot-total">
                        <td colspan="4" class="text-right">TOTAL :</td>
                        <td class="text-right">{{ number_format($montantTotal, 0, ',', ' ') }}</td>
                    </tr>
                    @if($avance > 0)
                    <tr class="tfoot-sub">
                        <td colspan="4" class="text-right">Avance perçue :</td>
                        <td class="text-right">{{ number_format($avance, 0, ',', ' ') }}</td>
                    </tr>
                    @endif
                    <tr class="tfoot-total">
                        <td colspan="4" class="text-right">RESTE A PAYER :</td>
                        <td class="text-right">{{ number_format($reste, 0, ',', ' ') }}</td>
                    </tr>
                </tfoot>
            </table>
            @else
            {{-- Fallback : pas de lignes (ancienne donnée sans détail) --}}
            <table>
                <thead>
                    <tr>
                        <th>NOM</th>
                        <th>PRENOM</th>
                        <th style="text-align:right;">MONTANT (FCFA)</th>
                        <th style="text-align:right;">AVANCE</th>
                        <th style="text-align:right;">RESTE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><h5>{{ $facture->patient->name ?? $facture->patient_name }}</h5></td>
                        <td><h5>{{ $facture->patient->prenom ?? '' }}</h5></td>
                        <td class="text-right"><h4>{{ number_format($montantTotal, 0, ',', ' ') }}</h4></td>
                        <td class="text-right"><h4>{{ number_format($avance, 0, ',', ' ') }}</h4></td>
                        <td class="text-right"><h4>{{ number_format($reste, 0, ',', ' ') }}</h4></td>
                    </tr>
                </tbody>
            </table>
            @endif

            <div class="notices">
                <h6>PATIENT : {{ $patientName }}</h6>
                <h6>LA CAISSE : {{ $caissier }}</h6>
                <h6>Douala, le {{ $dateFacture }}</h6>
            </div>

        </div>
        <footer>
            Centre Medico-chirurgical d'urologie situe a la Vallee Manga Bell Douala-Bali.<br>
            TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945 — www.cmcu-cm.com
        </footer>
    </div>

    {{-- ══════════════ DEUXIÈME COPIE — Patient ══════════════ --}}
    <div style="display: table-cell; width: 48%; padding: 2%; vertical-align: top;">
        <div class="container">

            <div class="text-center">
                @if(file_exists(public_path('admin/images/logo.jpg')))
                    <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="Logo CMCU">
                @endif
                <h6 class="bold">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</h6>
                <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
                <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
                <h6>www.cmcu-cm.com</h6>
            </div>

            <div class="text-center">
                @if($isProforma)
                    <div class="proforma">PROFORMA</div>
                @endif
                <h6 class="invoice-id">
                    {{ $isProforma ? 'PROFORMA ACTE' : 'RECU ACTE / SOIN INFIRMIER' }}
                    — N° {{ $facture->numero ?? $numeroDossier }}
                </h6>
                <h6>DOSSIER N° {{ $numeroDossier }}</h6>
            </div>

            @if($assurancec > 0)
                <h6 class="text-center">
                    ASSURANCE — PART PRISE EN CHARGE : {{ number_format($assurancec, 0, ',', ' ') }} FCFA
                    | PART PATIENT : {{ number_format($assurec, 0, ',', ' ') }} FCFA
                </h6>
            @endif

            @if($lignes->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th style="width:38%">Acte / Soin</th>
                        <th style="width:20%">Intervenant</th>
                        <th style="width:12%; text-align:center;">Qté</th>
                        <th style="width:15%; text-align:right;">P.U.</th>
                        <th style="width:15%; text-align:right;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->libelle }}</td>
                        <td style="font-size:10px;">
                            {{ $ligne->medecin ?? $ligne->infirmiere ?? '—' }}
                        </td>
                        <td style="text-align:center;">{{ $ligne->quantite ?? 1 }}</td>
                        <td style="text-align:right;">{{ number_format((int)$ligne->montant, 0, ',', ' ') }}</td>
                        <td style="text-align:right; font-weight:600;">
                            {{ number_format((int)$ligne->montant * ($ligne->quantite ?? 1), 0, ',', ' ') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if($assurancec > 0)
                    <tr class="tfoot-sub">
                        <td colspan="4" class="text-right">Part assurance ({{ $facture->prise_en_charge ?? 0 }}%) :</td>
                        <td class="text-right">{{ number_format($assurancec, 0, ',', ' ') }}</td>
                    </tr>
                    <tr class="tfoot-sub">
                        <td colspan="4" class="text-right">Part patient :</td>
                        <td class="text-right">{{ number_format($assurec, 0, ',', ' ') }}</td>
                    </tr>
                    @endif
                    <tr class="tfoot-total">
                        <td colspan="4" class="text-right">TOTAL :</td>
                        <td class="text-right">{{ number_format($montantTotal, 0, ',', ' ') }}</td>
                    </tr>
                    @if($avance > 0)
                    <tr class="tfoot-sub">
                        <td colspan="4" class="text-right">Avance perçue :</td>
                        <td class="text-right">{{ number_format($avance, 0, ',', ' ') }}</td>
                    </tr>
                    @endif
                    <tr class="tfoot-total">
                        <td colspan="4" class="text-right">RESTE A PAYER :</td>
                        <td class="text-right">{{ number_format($reste, 0, ',', ' ') }}</td>
                    </tr>
                </tfoot>
            </table>
            @else
            <table>
                <thead>
                    <tr>
                        <th>NOM</th><th>PRENOM</th>
                        <th style="text-align:right;">MONTANT</th>
                        <th style="text-align:right;">AVANCE</th>
                        <th style="text-align:right;">RESTE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><h5>{{ $facture->patient->name ?? $facture->patient_name }}</h5></td>
                        <td><h5>{{ $facture->patient->prenom ?? '' }}</h5></td>
                        <td class="text-right"><h4>{{ number_format($montantTotal, 0, ',', ' ') }}</h4></td>
                        <td class="text-right"><h4>{{ number_format($avance, 0, ',', ' ') }}</h4></td>
                        <td class="text-right"><h4>{{ number_format($reste, 0, ',', ' ') }}</h4></td>
                    </tr>
                </tbody>
            </table>
            @endif

            <div class="notices">
                <h6>PATIENT : {{ $patientName }}</h6>
                <h6>LA CAISSE : {{ $caissier }}</h6>
                <h6>Douala, le {{ $dateFacture }}</h6>
            </div>

        </div>
    </div>

</div>{{-- fin table --}}

</body>
</html>