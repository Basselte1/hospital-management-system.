<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bilan Consultation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            padding: 15px;
            color: #2c3e50;
            background-color: #ffffff;
        }

        .header-container {
            margin-bottom: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px;
            border-radius: 8px;
        }
        .header-flex   { display: table; width: 100%; margin-bottom: 10px; }
        .header-logo   { display: table-cell; width: 100px; vertical-align: middle; }
        .logo          { width: 85px; height: auto; border-radius: 6px; }
        .header-info   { display: table-cell; text-align: center; vertical-align: middle; padding-left: 15px; }
        .header-info .company-name    { font-size: 13px; font-weight: bold; margin: 3px 0; color: #1a5490; text-transform: uppercase; }
        .header-info .company-details { font-size: 10px; margin: 2px 0; color: #495057; }
        .divider { border: none; height: 3px; background: linear-gradient(to right, #1a5490, #3498db, #1a5490); margin: 10px 0; border-radius: 2px; }

        .document-title {
            text-align: center; font-size: 14px; font-weight: bold;
            margin: 20px 0; text-transform: uppercase; padding: 12px;
            background: linear-gradient(135deg, #1a5490 0%, #2980b9 100%);
            color: white; border-radius: 6px; letter-spacing: 1px;
        }

        .main-table {
            width: 100%; border-collapse: separate; border-spacing: 0;
            margin: 15px 0; border: 2px solid #dee2e6; font-size: 9px;
            border-radius: 8px; overflow: hidden;
        }
        .main-table th, .main-table td {
            border: 1px solid #dee2e6; padding: 8px 5px;
            text-align: left; vertical-align: middle;
        }
        .main-table thead th {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white; font-weight: bold; text-align: center;
            text-transform: uppercase; border: 1px solid #2c3e50; font-size: 8px;
        }
        .main-table tbody tr:nth-child(even) { background-color: #f8f9fa; }

        /* Largeurs colonnes */
        .col-sn         { width: 3%;  text-align: center; font-weight: 600; }
        .col-patient    { width: 13%; color: #2c3e50; font-weight: 500; }
        .col-montant, .col-avance, .col-reste,
        .col-part-patient, .col-part-ass { width: 8%; text-align: right; font-weight: 600; color: #27ae60; }
        .col-demarcheur { width: 9%;  font-size: 8px; color: #7f8c8d; }
        .col-medecin    { width: 10%; font-size: 8px; color: #7f8c8d; font-weight: 500; }
        .col-numero     { width: 5%;  text-align: center; font-weight: 600; color: #3498db; }

        /* ── Colonne devise ── */
        .col-devise     { width: 10%; font-size: 8px; color: #555; text-align: left; }

        /* Badge devise étrangère */
        .badge-devise {
            display: inline-block;
            background-color: #e8f4fd;
            color: #1a5490;
            border: 1px solid #b8d9f0;
            border-radius: 3px;
            padding: 1px 4px;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .total-row { background: linear-gradient(135deg, #1a5490 0%, #2980b9 100%); color: white; font-weight: bold; }
        .total-row th { text-align: left; padding-left: 10px; font-size: 10px; text-transform: uppercase; }
        .total-row td { font-size: 10px; font-weight: bold; }

        .payment-section th {
            font-size: 10px; text-transform: uppercase; font-weight: bold; padding: 8px;
            background: linear-gradient(135deg, #ecf0f1 0%, #bdc3c7 100%); color: #2c3e50;
        }
        .payment-table { width: 100%; border-collapse: collapse; }
        .payment-table td { border: 1px solid #dee2e6; padding: 8px; text-align: center; font-weight: bold; font-size: 9px; }
        .payment-table .payment-header { background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; text-transform: uppercase; font-size: 9px; }
        .payment-table .payment-value  { font-size: 11px; background-color: #fff; color: #27ae60; font-weight: bold; }

        .signatures-container { margin-top: 40px; display: table; width: 100%; border-top: 2px solid #dee2e6; padding-top: 20px; }
        .signature-box { display: table-cell; text-align: center; width: 33.33%; padding: 10px; }
        .signature-box .signature-label {
            font-weight: bold; margin-bottom: 50px; font-size: 10px; color: #2c3e50;
            text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 5px;
            border-bottom: 2px solid #3498db; display: inline-block;
        }

        .amount { font-family: 'DejaVu Sans Mono', 'Courier New', monospace; white-space: nowrap; font-weight: 600; }

        @media print {
            body { padding: 10px; }
            .main-table { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

<div class="section">

    <!-- En-tête -->
    <div class="header-container">
        <div class="header-flex">
            <div class="header-logo">
                @if(file_exists(public_path('admin/images/logo.jpg')))
                    <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="Logo CMCU">
                @endif
            </div>
            <div class="header-info">
                <div class="company-name">Centre Médico-Chirurgical d'Urologie</div>
                <div class="company-details">Vallée Manga Bell Douala-Bali</div>
                <div class="company-details">Tél: (+237) 233 423 389 / 674 068 988 / 698 873 945</div>
                <div class="company-details">www.cmcu-cm.com</div>
            </div>
        </div>
        <hr class="divider">
    </div>

    <!-- Titre -->
    <div class="document-title">
        Fiche de Suivi des Encaissements Journaliers — {{ $service ?: 'Tous services' }}
    </div>

    <!-- Tableau principal -->
    <table class="main-table">
        <thead>
            <tr>
                <th class="col-sn">N°</th>
                <th class="col-patient">Patient</th>
                <th class="col-montant">Montant</th>
                <th class="col-avance">Avance</th>
                <th class="col-reste">Reste</th>
                <th class="col-part-patient">Part Patient</th>
                <th class="col-part-ass">Part Ass.</th>
                <!--th class="col-devise">Devise</th-->
                <th class="col-demarcheur">Démarcheur</th>
                <th class="col-medecin">Médecin</th>
                <th class="col-numero">Facture</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tFactures as $index => $facture)
            @php
                $devise         = $facture->devise          ?? 'XAF';
                $montantDevise  = $facture->montant_devise  ?? null;
                $tauxConversion = $facture->taux_conversion ?? null;
                $isPaiementDevise = ($devise !== 'XAF') && $montantDevise && $tauxConversion;
            @endphp
            <tr>
                <td class="col-sn">{{ $index + 1 }}</td>
                <td class="col-patient">{{ $facture->name }}</td>
                <td class="col-montant"><span class="amount">{{ number_format($facture->montant, 0, ',', ' ') }}</span></td>
                <td class="col-avance"><span class="amount">{{ number_format($facture->percu, 0, ',', ' ') }}</span></td>
                <td class="col-reste"><span class="amount">{{ number_format($facture->reste, 0, ',', ' ') }}</span></td>
                <td class="col-part-patient"><span class="amount">{{ number_format($facture->partPatient, 0, ',', ' ') }}</span></td>
                <td class="col-part-ass"><span class="amount">{{ number_format($facture->partAssurance, 0, ',', ' ') }}</span></td>

                {{-- ── Colonne devise ── --}}
                <!--td class="col-devise">
                    @if($isPaiementDevise)
                        <span class="badge-devise">{{ $devise }}</span><br>
                        {{ number_format($montantDevise, 0, ',', ' ') }}<br>
                        <span style="color:#999;">×{{ number_format($tauxConversion, 0, ',', ' ') }}</span>
                    @else
                        <span style="color:#aaa;">FCFA</span>
                    @endif
                </td-->

                <td class="col-demarcheur">{{ $facture->demarcheur ?? '-' }}</td>
                <td class="col-medecin">{{ $facture->medecin ?? '-' }}</td>
                <td class="col-numero">{{ $facture->numero }}</td>
            </tr>
            @endforeach

            <!-- Ligne de total -->
            <tr class="total-row">
                <th colspan="2">Total en FCFA :</th>
                <td class="col-montant"><span class="amount">{{ number_format($totalMontant, 0, ',', ' ') }}</span></td>
                <td class="col-avance"><span class="amount">{{ number_format($totalPercu, 0, ',', ' ') }}</span></td>
                <td class="col-reste"><span class="amount">{{ number_format($totalReste, 0, ',', ' ') }}</span></td>
                <td class="col-part-patient"><span class="amount">{{ number_format($totalPartPatient, 0, ',', ' ') }}</span></td>
                <td class="col-part-ass"><span class="amount">{{ number_format($totalPartAssurance, 0, ',', ' ') }}</span></td>
                <td class="col-devise"></td>
                <td colspan="3"></td>
            </tr>

            <!-- Section mode de paiement -->
            <tr class="payment-section">
                <th colspan="11">
                    <table class="payment-table">
                        <tr>
                            <td colspan="{{ $mode_paiement && $mode_paiement->count() > 0 ? $mode_paiement->count() : 1 }}"
                                style="text-align: left; padding-left: 10px; background-color: transparent; border: none; font-weight: bold; color: #2c3e50;">
                                Mode de Paiement :
                            </td>
                        </tr>
                        <tr>
                            @if($mode_paiement && $mode_paiement->count() > 0)
                                @foreach($mode_paiement as $mp)
                                    <td class="payment-header">{{ $mp->name }}</td>
                                @endforeach
                            @else
                                <td class="payment-header">Aucun</td>
                            @endif
                        </tr>
                        <tr>
                            @if($mode_paiement && $mode_paiement->count() > 0)
                                @foreach($mode_paiement as $mp)
                                    <td class="payment-value">{{ number_format($mp->val, 0, ',', ' ') }}</td>
                                @endforeach
                            @else
                                <td class="payment-value">0</td>
                            @endif
                        </tr>
                    </table>
                </th>
            </tr>
        </tbody>
    </table>

</div>

<!-- Signatures -->
<div class="signatures-container">
    <div class="signature-box"><div class="signature-label">Gestionnaire</div></div>
    <div class="signature-box"><div class="signature-label">Comptable</div></div>
    <div class="signature-box"><div class="signature-label">Assistante</div></div>
</div>

</body>
</html>