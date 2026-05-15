<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $vente->numero_vente }}</title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .hospital-info {
            font-size: 14pt;
            font-weight: bold;
        }
        .invoice-title {
            font-size: 18pt;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            background-color: #f0f0f0;
            padding: 10px;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-row {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 13pt;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-paid {
            background-color: #28a745;
            color: #fff;
        }
        .copy-label {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 16pt;
            font-weight: bold;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="copy-label">ORIGINAL</div>
    
    <div class="header">
        <div class="hospital-info">
            CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE<br>
            CMCU
        </div>
        <div style="font-size: 9pt; margin-top: 10px;">
            Adresse de l'hôpital • Téléphone • Email
        </div>
    </div>

    <div class="invoice-title">
        FACTURE PHARMACIE
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">N° Facture:</span>
            <strong>{{ $vente->numero_vente }}</strong>
        </div>
        <div class="info-row">
            <span class="info-label">Date:</span>
            {{ $vente->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="info-row">
            <span class="info-label">Statut:</span>
            @if($vente->isSoldee())
            <span class="status-badge status-paid">SOLDÉE</span>
            @else
            <span class="status-badge status-pending">EN ATTENTE</span>
            @endif
        </div>
    </div>

    <div class="info-section">
        <strong style="font-size: 12pt;">CLIENT:</strong><br>
        @if($vente->isPatientSale())
            <strong>{{ $vente->patient->name }} {{ $vente->patient->prenom }}</strong><br>
            Dossier N°: {{ $vente->patient->numero_dossier }}<br>
            @if($vente->ordonance)
            Ordonnance du {{ $vente->ordonance->created_at->format('d/m/Y') }}
            @endif
        @else
            <strong>{{ $vente->client->nom ?? 'N/A' }} {{ $vente->client->prenom ?? '' }}</strong><br>
            {{ $vente->client->motif ?? '' }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">PRODUIT</th>
                <th class="text-center" style="width: 15%;">QTÉ</th>
                <th class="text-right" style="width: 17.5%;">PRIX UNIT.</th>
                <th class="text-right" style="width: 17.5%;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vente->items as $item)
            <tr>
                <td>{{ $item->designation }}</td>
                <td class="text-center">{{ $item->quantite }}</td>
                <td class="text-right">{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($item->montant_ligne, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL À PAYER:</td>
                <td class="text-right">{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tfoot>
    </table>

    @if($vente->notes)
    <div class="info-section">
        <strong>Notes:</strong><br>
        {{ $vente->notes }}
    </div>
    @endif

    @if(!$vente->isSoldee())
    <div style="background-color: #fff3cd; padding: 15px; margin: 20px 0; border: 2px solid #ffc107;">
        <strong>IMPORTANT:</strong> Cette facture doit être payée à la caisse/secrétaire.<br>
        Après paiement, un reçu vous sera délivré et les médicaments seront remis.
    </div>
    @endif

    <div style="margin-top: 60px;">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 50%;">
                    <strong>Pharmacien:</strong><br>
                    {{ $vente->pharmacien->name ?? 'N/A' }}<br><br>
                    Signature: _________________
                </td>
                <td style="border: none; width: 50%;">
                    @if($vente->isSoldee())
                    <strong>Caissier:</strong><br>
                    {{ $vente->caissier->name ?? 'N/A' }}<br><br>
                    Signature: _________________
                    @else
                    <strong>Patient/Client:</strong><br><br><br>
                    Signature: _________________
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- <div class="footer">
        <hr style="border-top: 1px solid #ddd; margin: 30px 0 10px 0;">
        Ce document a été généré le {{ now()->format('d/m/Y à H:i') }}<br>
        CMCU - Centre Médico-Chirurgical d'Urologie
    </div> -->
</body>
</html>

<!-- SECOND COPY (FOR DUPLICATING) -->
<div style="page-break-before: always;"></div>

<div class="copy-label">COPIE</div>
    
<div class="header">
    <div class="hospital-info">
        CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE<br>
        CMCU
    </div>
    <div style="font-size: 9pt; margin-top: 10px;">
        Adresse de l'hôpital • Téléphone • Email
    </div>
</div>

<div class="invoice-title">
    FACTURE PHARMACIE
</div>

<div class="info-section">
    <div class="info-row">
        <span class="info-label">N° Facture:</span>
        <strong>{{ $vente->numero_vente }}</strong>
    </div>
    <div class="info-row">
        <span class="info-label">Date:</span>
        {{ $vente->created_at->format('d/m/Y H:i') }}
    </div>
    <div class="info-row">
        <span class="info-label">Statut:</span>
        @if($vente->isSoldee())
        <span class="status-badge status-paid">SOLDÉE</span>
        @else
        <span class="status-badge status-pending">EN ATTENTE</span>
        @endif
    </div>
</div>

<div class="info-section">
    <strong style="font-size: 12pt;">CLIENT:</strong><br>
    @if($vente->isPatientSale())
        <strong>{{ $vente->patient->name }} {{ $vente->patient->prenom }}</strong><br>
        Dossier N°: {{ $vente->patient->numero_dossier }}<br>
        @if($vente->ordonance)
        Ordonnance du {{ $vente->ordonance->created_at->format('d/m/Y') }}
        @endif
    @else
        <strong>{{ $vente->client->nom ?? 'N/A' }} {{ $vente->client->prenom ?? '' }}</strong><br>
        {{ $vente->client->motif ?? '' }}
    @endif
</div>

<table>
    <thead>
        <tr>
            <th style="width: 50%;">PRODUIT</th>
            <th class="text-center" style="width: 15%;">QTÉ</th>
            <th class="text-right" style="width: 17.5%;">PRIX UNIT.</th>
            <th class="text-right" style="width: 17.5%;">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vente->items as $item)
        <tr>
            <td>{{ $item->designation }}</td>
            <td class="text-center">{{ $item->quantite }}</td>
            <td class="text-right">{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
            <td class="text-right">{{ number_format($item->montant_ligne, 0, ',', ' ') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="3" class="text-right">TOTAL À PAYER:</td>
            <td class="text-right">{{ number_format($vente->montant_total, 0, ',', ' ') }} FCFA</td>
        </tr>
    </tfoot>
</table>

<!-- <div class="footer">
    <hr style="border-top: 1px solid #ddd; margin: 30px 0 10px 0;">
    Ce document a été généré le {{ now()->format('d/m/Y à H:i') }}<br>
    CMCU - Centre Médico-Chirurgical d'Urologie
</div> -->
</html>