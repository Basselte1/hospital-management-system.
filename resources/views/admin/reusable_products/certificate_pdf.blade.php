<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificat de Stérilisation - {{ $sterilization->numero_lot }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0056b3;
        }
        .header h1 {
            color: #0056b3;
            font-size: 24pt;
            margin: 0 0 10px 0;
        }
        .header .hospital-name {
            font-size: 18pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 10pt;
            color: #666;
        }
        .certificate-info {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .certificate-info h2 {
            color: #28a745;
            font-size: 14pt;
            margin-top: 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #e9ecef;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12pt;
            color: #0056b3;
            margin-bottom: 10px;
            border-left: 4px solid #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            text-align: left;
            padding: 8px;
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            width: 35%;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 3px;
            font-weight: bold;
            color: white;
        }
        .status-success {
            background: #28a745;
        }
        .status-danger {
            background: #dc3545;
        }
        .parameters-grid {
            display: table;
            width: 100%;
        }
        .param-row {
            display: table-row;
        }
        .param-label, .param-value {
            display: table-cell;
            padding: 6px;
            border-bottom: 1px solid #dee2e6;
        }
        .param-label {
            font-weight: bold;
            width: 40%;
            background: #f8f9fa;
        }
        .signatures {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 45%;
            float: left;
            text-align: center;
            margin-top: 60px;
        }
        .signature-box.right {
            float: right;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #666;
            padding: 10px;
            border-top: 1px solid #dee2e6;
        }
        .qr-placeholder {
            width: 100px;
            height: 100px;
            border: 2px dashed #ccc;
            display: inline-block;
            text-align: center;
            line-height: 100px;
            color: #999;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="hospital-name">CMCU - Centre Médico-Chirurgical d'Urgence</div>
        <div class="subtitle">Service de Stérilisation</div>
        <h1>CERTIFICAT DE STÉRILISATION</h1>
    </div>

    <!-- Certificate Info Box -->
    <div class="certificate-info">
        <h2>N° Lot: {{ $sterilization->numero_lot }}</h2>
        <p style="margin: 5px 0;">
            <strong>Date d'émission:</strong> {{ now()->format('d/m/Y à H:i') }}<br>
            <strong>Statut:</strong> 
            <span class="status-badge status-{{ $sterilization->isRetourne() ? 'success' : 'danger' }}">
                {{ $sterilization->getStatutLabel() }}
            </span>
        </p>
    </div>

    <!-- Product Information -->
    <div class="section">
        <div class="section-title">1. PRODUIT STÉRILISÉ</div>
        <table>
            <tr>
                <th>Désignation</th>
                <td><strong>{{ $sterilization->produit->designation }}</strong></td>
            </tr>
            <tr>
                <th>Catégorie</th>
                <td>{{ $sterilization->produit->categorie }}</td>
            </tr>
            <tr>
                <th>Quantité Stérilisée</th>
                <td><strong>{{ $sterilization->quantite }} unité(s)</strong></td>
            </tr>
        </table>
    </div>

    <!-- Sterilization Parameters -->
    <div class="section">
        <div class="section-title">2. PARAMÈTRES DE STÉRILISATION</div>
        <div class="parameters-grid">
            <div class="param-row">
                <div class="param-label">Méthode</div>
                <div class="param-value"><strong>{{ $sterilization->getMethodeLabel() }}</strong></div>
            </div>
            <div class="param-row">
                <div class="param-label">Date de Stérilisation</div>
                <div class="param-value">{{ $sterilization->date_sterilisation->format('d/m/Y') }}</div>
            </div>
            @if($sterilization->heure_debut)
            <div class="param-row">
                <div class="param-label">Heure de Début</div>
                <div class="param-value">{{ $sterilization->heure_debut }}</div>
            </div>
            @endif
            @if($sterilization->heure_fin)
            <div class="param-row">
                <div class="param-label">Heure de Fin</div>
                <div class="param-value">{{ $sterilization->heure_fin }}</div>
            </div>
            @endif
            @if($sterilization->temperature)
            <div class="param-row">
                <div class="param-label">Température</div>
                <div class="param-value"><strong>{{ $sterilization->temperature }}°C</strong></div>
            </div>
            @endif
            @if($sterilization->duree_minutes)
            <div class="param-row">
                <div class="param-label">Durée</div>
                <div class="param-value"><strong>{{ $sterilization->duree_minutes }} minutes</strong></div>
            </div>
            @endif
            @if($sterilization->type_indicateur)
            <div class="param-row">
                <div class="param-label">Indicateur Utilisé</div>
                <div class="param-value">{{ $sterilization->type_indicateur }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quality Control -->
    <div class="section">
        <div class="section-title">3. CONTRÔLE QUALITÉ</div>
        
        @if($sterilization->resultat_test === 'conforme')
        <div class="alert alert-success">
            <strong>✓ RÉSULTAT: CONFORME</strong><br>
            La stérilisation a été réalisée avec succès. Les indicateurs de qualité confirment 
            l'efficacité du processus de stérilisation.
        </div>
        @else
        <div class="alert alert-danger">
            <strong>✗ RÉSULTAT: NON CONFORME</strong><br>
            La stérilisation n'a pas satisfait aux critères de qualité requis.
        </div>
        @endif

        @if($sterilization->observations)
        <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-left: 3px solid #6c757d;">
            <strong>Observations:</strong><br>
            {{ $sterilization->observations }}
        </div>
        @endif
    </div>

    <!-- Personnel -->
    <div class="section">
        <div class="section-title">4. PERSONNEL</div>
        <table>
            <tr>
                <th>Stérilisé par</th>
                <td>{{ $sterilization->sterilisePar->name }}</td>
            </tr>
            @if($sterilization->verifie_par)
            <tr>
                <th>Vérifié par (Pharmacien)</th>
                <td>{{ $sterilization->verifiePar->name }}</td>
            </tr>
            @endif
            @if($sterilization->retourne_par)
            <tr>
                <th>Retourné au stock par</th>
                <td>{{ $sterilization->retournePar->name }}</td>
            </tr>
            <tr>
                <th>Date de retour au stock</th>
                <td>{{ $sterilization->retourne_at->format('d/m/Y à H:i') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Traceability -->
    <div class="section">
        <div class="section-title">5. TRAÇABILITÉ</div>
        <table>
            <tr>
                <th>N° Lot de Stérilisation</th>
                <td><strong>{{ $sterilization->numero_lot }}</strong></td>
            </tr>
            <tr>
                <th>Date de Création du Lot</th>
                <td>{{ $sterilization->created_at->format('d/m/Y à H:i') }}</td>
            </tr>
            <tr>
                <th>Référence Produit</th>
                <td>ID #{{ $sterilization->produit->id }}</td>
            </tr>
        </table>
    </div>

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature-box">
            <strong>Opérateur de Stérilisation</strong>
            <div class="signature-line">
                {{ $sterilization->sterilisePar->name }}
            </div>
            <small>{{ $sterilization->date_sterilisation->format('d/m/Y') }}</small>
        </div>

        @if($sterilization->verifie_par)
        <div class="signature-box right">
            <strong>Pharmacien Validateur</strong>
            <div class="signature-line">
                {{ $sterilization->verifiePar->name }}
            </div>
            <small>{{ now()->format('d/m/Y') }}</small>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        CMCU - Centre Médico-Chirurgical d'Urgence | Service de Stérilisation<br>
        Certificat généré le {{ now()->format('d/m/Y à H:i') }} | Document confidentiel
    </div>

</body>
</html>