<?php \Carbon\Carbon::setLocale('fr'); ?>
<link href="{{ public_path('vendor/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all" />

<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #333; background-color: #fff; }
    p, small { line-height: 1.5; margin: 0; }
    .table { border-collapse: collapse; border-spacing: 0; margin-bottom: 2rem; width: 100%; }
    .table thead th { background-color: #4463dc; color: #fff; text-align: center; font-weight: 600; font-size: 14px; padding: 14px; border: 1px solid #dee2e6; }
    .table tbody td { vertical-align: middle; font-size: 13px; padding: 12px 14px; border: 1px solid #dee2e6; }
    .table tbody tr:nth-child(odd):not(.table-secondary):not(.table-primary):not(.section-header) { background-color: #f9f9f9; }
    .section-header td { background-color: #e3f2fd !important; font-weight: 700; text-transform: uppercase; font-size: 14px; padding: 10px 14px !important; }
    .table-secondary td { background-color: #e9ecef !important; font-weight: 600; text-transform: uppercase; border-top: 2px solid #dee2e6; }
    .table-primary td { background-color: #dbeafe !important; font-weight: 700; border-top: 2px solid #4463dc; }
    .table-primary h5 { margin: 0; font-size: 1rem; font-weight: 700; color: #4463dc; }
    .text-right { text-align: right !important; }
    .text-center { text-align: center !important; }
    h4 { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
    .text-primary { color: #0d6efd !important; }
    u { font-weight: 500; font-size: 14px; }
    .cmcu-footer {
        position: fixed; bottom: 0; left: 0; right: 0;
        background-color: #4463dc; color: #fff;
        text-align: center; font-size: 8px; padding: 5px 10px; line-height: 1.5;
    }
    .doc-footer-note {
        position: fixed; bottom: 70; left: 0; right: 0;
        font-size: 11px; color: #555; font-style: italic;
        border-top: 1px solid #ccc; padding-top: 8px; margin-top: 16px;
    }
</style>

<div class="container-fluid">

    {{-- LETTERHEAD HEADER --}}
    <table width="100%" style="border-collapse: collapse; margin-bottom: 0;">
        <tr>
            <td width="18%" style="vertical-align: middle; padding-right: 8px;">
                <img src="{{ public_path('admin/images/logo.jpg') }}" style="width: 80px; height: auto;" alt="Logo CMCU">
            </td>
            <td width="82%" style="text-align: center; vertical-align: middle;">
                <div style="font-weight: bold; font-size: 14px; color: #4463dc; text-transform: uppercase; letter-spacing: 0.5px;">CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE</div>
                <div style="font-size: 9px; margin-top: 1px;">ONMC : N°5531 DÉCISION N°007/10/D/ONMC/P/SG/MM</div>
                <div style="font-size: 9px;">Arrêté N° 3203/A/MINSANTE/SG/DOSTS/SDOS/SFSP</div>
                <div style="font-size: 9px;">Tél : +237 233 42 33 89 / +237 698 87 39 45 / +237 674 06 89 88</div>
                <div style="font-size: 9px;">Site internet : www.cmcu-cm.com | Email : cmcu_cmcu@yahoo.fr</div>
                <div style="font-size: 9px;">Situé à la vallée Manga Bell Douala - Bali</div>
                <div style="font-size: 9px; color: #4463dc;">Consultation sur rendez-vous</div>
                <div style="font-size: 9px;">N° de contribuable : P016400474386D</div>
            </td>
        </tr>
    </table>
    <div style="border-top: 3px solid #4463dc; margin: 6px 0 14px 0;"></div>

    <!-- Patient & Date -->
    <table width="100%" style="border-collapse: collapse; margin-bottom: 12px;">
        <tr>
            <td>
                @php
                    $patientName = isset($nomPatient) ? $nomPatient : '';
                    if (!empty($nomPatient)) {
                        if (is_numeric($nomPatient)) {
                            $patient = \App\Models\Patient::find((int) $nomPatient);
                            if ($patient) { $patientName = ($patient->name ?? '') . ' ' . ($patient->prenom ?? ''); }
                        } elseif (is_object($nomPatient)) {
                            $patientName = ($nomPatient->name ?? '') . ' ' . ($nomPatient->prenom ?? '');
                        }
                    }
                @endphp
                <p><b style="color: #012c6dff">Patient : {{ $patientName }}</b></p>
            </td>
            <td style="text-align: right;">
                <p><b>Douala, {{ now()->translatedFormat('d F Y') }}</b></p>
            </td>
        </tr>
    </table>

    <!-- Devis Title -->
    <div class="text-center text-primary">
        <h4><u>DEVIS : {{ $devis->nom }}</u> (N°{{ $devis->code }})</h4>
    </div>
    <br>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ÉLÉMENTS</th>
                <th>QTES</th>
                <th>PRIX UNIT.</th>
                <th>MONTANT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $proceduresTotal = 0; $productsTotal = 0; $procedures = []; $products = [];
                foreach($ligneDevis as $ligne) {
                    if ($ligne->type === 'procedure') { $procedures[] = $ligne; $proceduresTotal += $ligne->prix; }
                    else { $products[] = $ligne; $productsTotal += $ligne->prix; }
                }
            @endphp

            @if(count($procedures) > 0)
                <tr class="section-header"><td colspan="4"><b>PROCÉDURES MÉDICALES</b></td></tr>
                @foreach($procedures as $ligne)
                    <tr>
                        <td>{{ $ligne->element }}</td>
                        <td class="text-center">{{ $ligne->quantite }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_u, 0, ',', ' ') }}</td>
                        <td class="text-right">{{ number_format($ligne->quantite * $ligne->prix_u, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
                <tr class="table-secondary">
                    <td colspan="3" class="text-center"><b>SOUS-TOTAL ÉLÉMENTS</b></td>
                    <td class="text-right"><b>{{ number_format($proceduresTotal, 0, ',', ' ') }}</b></td>
                </tr>
            @endif

            @if(count($products) > 0)
                <tr class="section-header"><td colspan="4"><b>PRODUITS ET MATÉRIELS</b></td></tr>
                @foreach($products as $ligne)
                    <tr>
                        <td>
                            {{ $ligne->element }}
                            @if($ligne->type === 'medication') <small class="text-muted">(Médicament)</small>
                            @elseif($ligne->type === 'anesthesie') <small class="text-muted">(Anesthésie)</small>
                            @elseif($ligne->type === 'material') <small class="text-muted">(Matériel)</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $ligne->quantite }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_u, 0, ',', ' ') }}</td>
                        <td class="text-right">{{ number_format($ligne->quantite * $ligne->prix_u, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
                <tr class="table-secondary">
                    <td colspan="3" class="text-center"><b>SOUS-TOTAL PRODUITS</b></td>
                    <td class="text-right"><b>{{ number_format($productsTotal, 0, ',', ' ') }}</b></td>
                </tr>
            @endif

            <tr class="table-secondary">
                <td colspan="3" class="text-center"><b>TOTAL 1 (PROCÉDURES + PRODUITS)</b></td>
                <td class="text-right"><b>{{ number_format($devis->total1, 0, ',', ' ') }}</b></td>
            </tr>

            <tr class="section-header"><td colspan="4"><b>HOSPITALISATION {{ $devis->nbr_chambre }} JOUR(S)</b></td></tr>
            <tr>
                <td>CHAMBRE</td>
                <td class="text-center">{{ $devis->nbr_chambre }}</td>
                <td class="text-right">{{ number_format($devis->pu_chambre, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($devis->nbr_chambre * $devis->pu_chambre, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>AMI-JOUR (750x12)</td>
                <td class="text-center">{{ $devis->nbr_ami_jour }}</td>
                <td class="text-right">{{ number_format($devis->pu_ami_jour, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($devis->nbr_ami_jour * $devis->pu_ami_jour, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>VISITE</td>
                <td class="text-center">{{ $devis->nbr_visite }}</td>
                <td class="text-right">{{ number_format($devis->pu_visite, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($devis->nbr_visite * $devis->pu_visite, 0, ',', ' ') }}</td>
            </tr>
            <tr class="table-secondary">
                <td colspan="3" class="text-center"><b>TOTAL 2 (HOSPITALISATION)</b></td>
                <td class="text-right"><b>{{ number_format($devis->total2, 0, ',', ' ') }}</b></td>
            </tr>
            <tr></tr>
            <tr class="table-primary">
                <td colspan="3" class="text-center"><h5><b>TOTAL GÉNÉRAL</b></h5></td>
                <td class="text-right"><h5><b>{{ number_format($devis->total1 + $devis->total2, 0, ',', ' ') }}</b></h5></td>
            </tr>
        </tbody>
    </table>

    <p>Arrêté le présent devis à la somme de : <b>{{ $devis->total }}</b> Francs CFA</p>
    <br>

    <!-- Signatures -->
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td style="width: 50%;"><u>LE MÉDECIN TRAITANT :</u></td>
            <td style="width: 50%; text-align: center;"><u>LA DIRECTION :</u></td>
        </tr>
    </table>
    <br>
    <br>
    <br>

    

</div>

{{-- FIXED FOOTER BAND --}}
<!-- Disclaimer note -->
    <div class="doc-footer-note">
        <b>N.B :</b> <i>Il est à noter que ceci n'est qu'une estimation du coût de l'intervention chirurgicale et de l'hospitalisation.
        Nous ne sommes pas tenus responsables des imprévus, ni des examens de laboratoire que vous pourriez effectuer éventuellement. Merci.</i>
    </div>
<div class="cmcu-footer">
    Urgences Urologiques - Cancérologie - Centre de la Prostate - Coelioscopie - Calcul Urinaire<br>
    Incontinence Urinaire - Stérilité Masculine - Dysfonctionnement érectile - Lithotritie Extracorporelle<br>
    Explorations Endoscopiques - Échographie - Débimétrie - Biopsies de la Prostate<br>
    Médecine Générale - Médecine du Travail
</div>