<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bon de Commande {{ $bonCommande->numero_bon }}</title>
    <style>
        /* SIMPLIFIED CSS FOR mPDF COMPATIBILITY */
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            color: #333;
            margin: 20px;
            line-height: 1.4;
        }

        .logo {
            width: 100px;
            height: auto;
        }


        /* Header */
        .document-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 15px;
        }

        .header-table {
            width: 100%;
        }

        .clinic-name {
            font-weight: bold;
            font-size: 12pt;
            color: #0066cc;
        }

        .clinic-info {
            text-align: right;
            font-size: 9pt;
        }

        /* Title */
        .doc-title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            color: #0066cc;
            margin: 20px 0 10px 0;
        }

        .doc-number {
            text-align: center;
            font-size: 12pt;
            color: #666;
            margin-bottom: 30px;
        }

        .status-info {
            text-align: right;
            margin-bottom: 20px;
        }

        /* Info sections */
        .info-section {
            margin-bottom: 20px;
        }

        .info-title {
            background-color: #0066cc;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-label {
            font-weight: bold;
            width: 30%;
            color: #555;
        }

        /* Products table */
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .products-table th {
            background-color: #0066cc;
            color: white;
            font-weight: bold;
            font-size: 10pt;
        }

        .products-table .even-row {
            background-color: #f9f9f9;
        }

        .products-table .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-amount {
            font-size: 14pt;
            color: #0066cc;
        }

        /* Notes */
        .notes-section {
            margin-top: 20px;
            padding: 10px;
            background-color: #fffbcc;
            border-left: 4px solid #ffcc00;
        }

        /* Signatures */
        .signature-section {
            margin-top: 40px;
        }

        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        /* Status badges */
        .status-badge {
            padding: 5px 15px;
            font-weight: bold;
            font-size: 10pt;
        }

        .status-brouillon {
            background-color: #6c757d;
            color: white;
        }

        .status-envoye {
            background-color: #ffc107;
            color: #000;
        }

        .status-valide {
            background-color: #17a2b8;
            color: white;
        }

        .status-receptionne {
            background-color: #28a745;
            color: white;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="document-header">
        <table class="header-table">
            <tr>
                <!-- Left column: Logo -->
                <td style="width: 20%; vertical-align: top;">
                    <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" style="height:80px; width:auto;">
                </td>

                <!-- Right column: Clinic info -->
                <td class="clinic-info" style="width: 80%; text-align: right;">
                    <div class="clinic-name">CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</div>
                    <div>007/10/D/ONMC</div>
                    <div>VALLEE MANGA BELL DOUALA-BALI</div>
                    <div>Arrêté N° 3203/A/MINSANTE/SG/DOSTS/SDOS/SFSP</div>
                    <div>TEL: (+237) 33 42 33 89 / 674 068 988 / 698 873 945</div>
                    <div>Email: cmcu_@yahoo.fr</div>
                    <div>www.cmcu-cm.com / cmcu_cmcu@yahoo.fr</div>
                </td>
            </tr>
        </table>
    </div>


    <!-- Status Info -->
    
    <!-- Title -->
    <div class="doc-title">BON DE COMMANDE</div>
    <div class="doc-number">N° {{ $bonCommande->numero_bon }}</div>

    <!-- Supplier Information -->
    <div class="info-section">
        <div class="info-title">FOURNISSEUR</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Nom:</td>
                <td><strong>{{ $bonCommande->fournisseur_nom }}</strong></td>
            </tr>
            @if($bonCommande->fournisseur_email)
            <tr>
                <td class="info-label">Email:</td>
                <td>{{ $bonCommande->fournisseur_email }}</td>
            </tr>
            @endif
            @if($bonCommande->fournisseur_telephone)
            <tr>
                <td class="info-label">Téléphone:</td>
                <td>{{ $bonCommande->fournisseur_telephone }}</td>
            </tr>
            @endif
            @if($bonCommande->fournisseur_adresse)
            <tr>
                <td class="info-label">Adresse:</td>
                <td>{{ $bonCommande->fournisseur_adresse }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Order Information -->
    <div class="info-section">
        <div class="info-title">INFORMATIONS COMMANDE</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Date de Commande:</td>
                <td>{{ $bonCommande->date_commande->format('d/m/Y') }}</td>
            </tr>
            @if($bonCommande->date_livraison_souhaitee)
            <tr>
                <td class="info-label">Date de Livraison Souhaitée:</td>
                <td>{{ $bonCommande->date_livraison_souhaitee->format('d/m/Y') }}</td>
            </tr>
            @endif
            <tr>
                <td class="info-label">Créé par:</td>
                <td>{{ $bonCommande->createdBy->name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Products List -->
    <div class="info-section">
        <div class="info-title">DÉTAIL DES PRODUITS</div>
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Désignation</th>
                    <th style="width: 15%;">Catégorie</th>
                    <th style="width: 10%;" class="text-center">Quantité</th>
                    <th style="width: 15%;" class="text-right">Prix Unit.</th>
                    <th style="width: 20%;" class="text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonCommande->items as $index => $item)
                <tr class="{{ $index % 2 == 1 ? 'even-row' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->designation }}</td>
                    <td>{{ $item->categorie }}</td>
                    <td class="text-center">{{ $item->quantite_commandee }}</td>
                    <td class="text-right">{{ number_format($item->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td class="text-right">{{ number_format($item->montant_ligne, 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-right">MONTANT TOTAL:</td>
                    <td class="text-right total-amount">{{ number_format($bonCommande->montant_total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Notes -->
    @if($bonCommande->notes)
    <div class="notes-section">
        <strong>Notes / Remarques:</strong><br>
        {{ $bonCommande->notes }}
    </div>
    @endif

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <strong>Nom et Signature du Commis de Pharmacie</strong>
            <div class="signature-line">
                {{ ($bonCommande->createdBy->name ?? '') . ' ' . ($bonCommande->createdBy->prenom ?? '')}}<br>
                <!-- Date: {{ $bonCommande->created_at->format('d/m/Y') }} -->
            </div>
        </div>

        <div class="signature-box" style="float: right;">
            <strong>Nom et Signature du Gestionaire Gestionnaire</strong>
            <div class="signature-line">
                @if($bonCommande->validated_at)
                    {{ ($bonCommande->validatedBy->name ?? '') . ' ' . ($bonCommande->validatedBy->prenom ?? '')}}<br>
                    <!-- Date: {{ $bonCommande->validated_at->format('d/m/Y') }} -->
                @else
                    <br><br>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Centre Medico-chirurgical d'urologie situé à la Vallée Douala Manga Bell Douala-Bali.<br>
        TEL: (+237) 233 423 389 / 674 068 988 / 698 873 945 | www.cmcu-cm.com
    </div>

</body>
</html>