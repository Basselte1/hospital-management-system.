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
            font-size: 11px;
            line-height: 1.4;
            color: #000;
        }
        .container {
            border: 1px solid #000;
            padding: 12px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        /* ─── Filigrane PROFORMA ─── */
        .proforma-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 18px;
            font-weight: bold;
            color: rgba(200, 30, 30, 0.13);
            white-space: nowrap;
            pointer-events: none;
            z-index: 999;
            letter-spacing: 8px;
            padding: 5px 5px;
            border-radius: 6px;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        h6 { margin: 3px 0; font-size: 11px; }
        h5 { margin: 5px 0; font-size: 12px; }
        h4 { margin: 5px 0; font-size: 13px; }
        .bold { font-weight: bold; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }
        th, td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .invoice-id {
            color: #000;
            margin: 10px 0;
            font-size: 14px;
        }
        .notices {
            margin-top: 15px;
            padding-left: 6px;
        }
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

        /* ─── Ligne de traçabilité devise ───
           Visible uniquement si le paiement a été effectué en devise étrangère.
           Sobre : une seule ligne dans le tableau, fond légèrement teinté. */
        .devise-row td {
            background-color: #fafafa;
            font-size: 10px;
            color: #444;
            border-top: 1px dashed #ccc;
        }
        .devise-row .devise-label {
            font-weight: bold;
            color: #555;
        }
        .devise-row .devise-detail {
            font-family: 'DejaVu Sans Mono', monospace;
        }
        /* Rendu monnaie : affiché seulement si > 0 */
        .rendu-row td {
            background-color: #f0faf0;
            font-size: 10px;
            color: #276221;
            border-top: 1px dashed #ccc;
        }
        .rendu-row .rendu-label {
            font-weight: bold;
        }

        @media print {
            body { font-size: 11px !important; }
        }
    </style>
</head>
<body>

@php
    $devise         = $facture['devise']          ?? 'XAF';
    $montantDevise  = $facture['montant_devise']  ?? null;
    $tauxConversion = $facture['taux_conversion'] ?? null;
    $isPaiementDevise = ($devise !== 'XAF') && $montantDevise && $tauxConversion;

    // Calcul du rendu monnaie (informatif, non stocké).
    // dettePatientFCFA = part patient si assurance, sinon montant total.
    $dettePatientFCFA = !empty($facture['assurec']) && $facture['assurec'] > 0
        ? $facture['assurec']
        : $facture['montant'];

    $remisFCFA = $isPaiementDevise ? ($montantDevise * $tauxConversion) : ($facture['avance'] ?? 0);
    $renduFCFA = $isPaiementDevise ? max(0, $remisFCFA - $dettePatientFCFA) : 0;
@endphp

    {{-- Deux copies sur une même page --}}
    <div style="display: table; width: 100%;">

        {{-- ════════════ COPIE 1 ════════════ --}}
        <div style="display: table-cell; width: 100%; padding: 2%; vertical-align: top;">
            <div class="container">

                @if($is_proforma ?? false)
                    <div class="proforma-watermark">PROFORMA</div>
                @endif

                <div class="text-center">
                    @if(file_exists(public_path('admin/images/logo.jpg')))
                        <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="">
                    @endif
                    <h6 class="bold">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</h6>
                    <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
                    <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
                    <h6>www.cmcu-cm.com</h6>
                </div>

                <div class="text-center">
                    <h6 class="invoice-id">
                        @if($is_proforma ?? false)
                            FACTURE PROFORMA — {{ strtoupper($facture['details_motif'] ?? 'CONSULTATION') }}
                        @else
                            RECU {{ strtoupper($facture['details_motif'] ?? 'CONSULTATION') }}
                        @endif
                        N° {{ $patient['numero_dossier'] }}
                    </h6>
                </div>

                @if(!empty($facture['assurancec']))
                    <h6 class="text-center">ASSURANCE: {{ $facture['assurance'] ?? '' }}</h6>
                @endif

                @if(!empty($patient['demarcheur']))
                    <h6 class="text-center">{{ $patient['demarcheur'] }}</h6>
                @endif

                @if(!empty($facture['assurancec']))
                    <h6 class="text-center">
                        PART ASSURANCE: {{ number_format($facture['assurancec'], 0, ',', ' ') }} |
                        PART PATIENT: {{ number_format($patient['assurec'] ?? 0, 0, ',', ' ') }}
                    </h6>
                @endif

                <table>
                    <thead>
                        <tr>
                            <th class="text-left">NOM</th>
                            <th class="text-left">PRENOM</th>
                            <th class="text-left">MONTANT (FCFA)</th>
                            <th class="text-left">AVANCE</th>
                            <th class="text-left">RESTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left"><h5>{{ $patient['name'] }}</h5></td>
                            <td class="text-left"><h5>{{ $patient['prenom'] }}</h5></td>
                            <td class="text-left"><h4>{{ number_format($facture['montant'], 0, ',', ' ') }}</h4></td>
                            <td class="text-left"><h4>{{ number_format($facture['avance'] ?? 0, 0, ',', ' ') }}</h4></td>
                            <td class="text-left"><h4>{{ number_format($facture['reste'] ?? 0, 0, ',', ' ') }}</h4></td>
                        </tr>

                        {{-- ── Ligne devise : affichée uniquement si paiement en devise étrangère ──
                             Format sobre : "Paiement : 200,00 EUR × 655 = 131 000 FCFA"
                             Cela reste dans le tableau, aligné avec les données du patient. --}}
                        @if($isPaiementDevise)
                        <tr class="devise-row">
                            <td colspan="2" class="devise-label">Paiement en devise</td>
                            <td colspan="3" class="devise-detail">
                                {{ number_format($montantDevise, 2, ',', ' ') }} {{ $devise }}
                                &times; {{ number_format($tauxConversion, 4, ',', ' ') }}
                                = {{ number_format($montantDevise * $tauxConversion, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @endif

                        {{-- ── Rendu monnaie : affiché uniquement si le patient a rendu plus que sa dette ── --}}
                        @if($isPaiementDevise && $renduFCFA > 0)
                        <tr class="rendu-row">
                            <td colspan="2" class="rendu-label">Rendu monnaie</td>
                            <td colspan="3">{{ number_format($renduFCFA, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endif

                    </tbody>
                </table>

                <div class="notices">
                    <h6>LA CAISSE: {{ ($printer['prenom'] ?? '') . ' ' . ($printer['name'] ?? '') }}</h6>
                    <h6>Douala, {{ isset($patient['created_at']) ? \Carbon\Carbon::parse($patient['created_at'])->format('d/m/Y') : date('d/m/Y') }}</h6>
                </div>

            </div>
            <footer style="margin-top: 40px;">
                Centre Medico-chirurgical d'urologie situe a la Valle Douala Manga Bell Douala-Bali.<br>
                TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945<br>
                SITE WEB: http://www.cmcu-cm.com
            </footer>
        </div>

        {{-- ════════════ COPIE 2 ════════════ --}}
        <div style="display: table-cell; width: 100%; padding: 2%; vertical-align: top;">
            <div class="container">

                @if($is_proforma ?? false)
                    <div class="proforma-watermark">PROFORMA</div>
                @endif

                <div class="text-center">
                    @if(file_exists(public_path('admin/images/logo.jpg')))
                        <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="">
                    @endif
                    <h6 class="bold">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</h6>
                    <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
                    <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
                    <h6>www.cmcu-cm.com</h6>
                </div>

                <div class="text-center">
                    <h6 class="invoice-id">
                        @if($is_proforma ?? false)
                            FACTURE PROFORMA — {{ strtoupper($facture['details_motif'] ?? 'CONSULTATION') }}
                        @else
                            RECU {{ strtoupper($facture['details_motif'] ?? 'CONSULTATION') }}
                        @endif
                        N° {{ $patient['numero_dossier'] }}
                    </h6>
                </div>

                @if(!empty($facture['assurancec']))
                    <h6 class="text-center">ASSURANCE: {{ $facture['assurance'] ?? '' }}</h6>
                @endif

                @if(!empty($patient['demarcheur']))
                    <h6 class="text-center">{{ $patient['demarcheur'] }}</h6>
                @endif

                @if(!empty($facture['assurancec']))
                    <h6 class="text-center">
                        PART ASSURANCE: {{ number_format($facture['assurancec'], 0, ',', ' ') }} |
                        PART PATIENT: {{ number_format($patient['assurec'] ?? 0, 0, ',', ' ') }}
                    </h6>
                @endif

                <table>
                    <thead>
                        <tr>
                            <th class="text-left">NOM</th>
                            <th class="text-left">PRENOM</th>
                            <th class="text-left">MONTANT (FCFA)</th>
                            <th class="text-left">AVANCE</th>
                            <th class="text-left">RESTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left"><h5>{{ $patient['name'] }}</h5></td>
                            <td class="text-left"><h5>{{ $patient['prenom'] }}</h5></td>
                            <td class="text-left"><h4>{{ number_format($facture['montant'], 0, ',', ' ') }}</h4></td>
                            <td class="text-left"><h4>{{ number_format($facture['avance'] ?? 0, 0, ',', ' ') }}</h4></td>
                            <td class="text-left"><h4>{{ number_format($facture['reste'] ?? 0, 0, ',', ' ') }}</h4></td>
                        </tr>

                        {{-- Même logique devise sur la copie 2 --}}
                        @if($isPaiementDevise)
                        <tr class="devise-row">
                            <td colspan="2" class="devise-label">Paiement en devise</td>
                            <td colspan="3" class="devise-detail">
                                {{ number_format($montantDevise, 2, ',', ' ') }} {{ $devise }}
                                &times; {{ number_format($tauxConversion, 4, ',', ' ') }}
                                = {{ number_format($montantDevise * $tauxConversion, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @endif

                        @if($isPaiementDevise && $renduFCFA > 0)
                        <tr class="rendu-row">
                            <td colspan="2" class="rendu-label">Rendu monnaie</td>
                            <td colspan="3">{{ number_format($renduFCFA, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endif

                    </tbody>
                </table>

                <div class="notices">
                    <h6>LA CAISSE: {{ ($printer['prenom'] ?? '') . ' ' . ($printer['name'] ?? '') }}</h6>
                    <h6>Douala, {{ isset($patient['created_at']) ? \Carbon\Carbon::parse($patient['created_at'])->format('d/m/Y') : date('d/m/Y') }}</h6>
                </div>

            </div>
        </div>

    </div>
</body>
</html>