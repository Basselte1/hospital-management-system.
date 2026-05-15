<link href="{{ public_path('vendor/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all" />
<style>
    body { font-size: 13px; color: #333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fff; }
    p { line-height: 1.4; margin: 0; }
    h4 { text-align: center; }
    .cmcu-footer {
        position: fixed; bottom: 0; left: 0; right: 0;
        background-color: #4463dc; color: #fff;
        text-align: center; font-size: 8px; padding: 5px 10px; line-height: 1.5;
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

    <h4><u>COMPTE-RENDU OPÉRATOIRE</u></h4>

    <div style="text-align: center; margin: 8px 0;">
        <p>CONCERNANT LE PATIENT : <b>{{ $patient->name }} {{ $patient->prenom }}</b></p>
    </div>

    <table width="100%" style="border-collapse: collapse; margin-bottom: 12px;">
        <tr>
            <td style="vertical-align: top; width: 50%;">
                <small><b>CHIRURGIEN :</b> Dr. {{ $crbo->chirurgien ?? 'N/A' }}</small><br>
                <small><b>AIDE OPÉRATOIRE :</b> Dr. {{ $crbo->aide_op ?? 'N/A' }}</small><br>
                <small><b>ANESTHÉSISTE :</b> Dr. {{ $crbo->anesthesiste ?? 'N/A' }}</small><br>
                <small><b>INFIRMIER ANESTHÉSISTE :</b> {{ $crbo->infirmier_anesthesiste ?? 'N/A' }}</small>
            </td>
            <td style="vertical-align: top; text-align: right; width: 50%;">
                <small><b>DATE D'ENTRÉE :</b> {{ $crbo->date_e ?? 'N/A' }}</small><br>
                <small><b>TYPE D'ENTRÉE :</b> {{ $crbo->type_e ?? 'N/A' }}</small><br>
                <small><b>DATE DE SORTIE :</b> {{ $crbo->date_s ?? 'N/A' }}</small><br>
                <small><b>TYPE DE SORTIE :</b> {{ $crbo->type_s ?? 'N/A' }}</small>
            </td>
        </tr>
    </table>

    <p><u><b>TYPE D'INTERVENTION :</b></u></p>
    <p><b>{!! nl2br(e($consultation->type_intervention ?? 'N/A')) !!}</b></p>
    <br>

    <p><u><b>DATE D'INTERVENTION :</b></u> {{ $crbo->date_intervention ?? 'N/A' }}</p>
    <br>

    <p><u><b>INDICATIONS OPÉRATOIRES :</b></u></p>
    <p>{!! nl2br(e($crbo->indication_operatoire ?? '')) !!}</p>
    <br>

    <p><u><b>COMPTE-RENDU OPÉRATOIRE :</b></u></p>
    <p>{!! nl2br(e($crbo->compte_rendu_o ?? '')) !!}</p>
    <br>

    <p><u><b>SUITES OPÉRATOIRES :</b></u></p>
    <p>{!! nl2br(e($crbo->suite_operatoire ?? '')) !!}</p>
    <br>

    <p><u><b>CONCLUSIONS :</b></u></p>
    <p>{!! nl2br(e($crbo->conclusion ?? '')) !!}</p>
    <br>

    <p><u><b>PROPOSITION DE SUIVI :</b></u></p>
    <p>{!! nl2br(e($crbo->proposition_suivi ?? '')) !!}</p>
    <br>

    <div style="text-align: right; margin-top: 30px;">
        <p><b>Dr {{ auth()->user()->name ?? '' }}</b></p>
    </div>

</div>

{{-- FIXED FOOTER BAND --}}
<div class="cmcu-footer">
    Urgences Urologiques - Cancérologie - Centre de la Prostate - Coelioscopie - Calcul Urinaire<br>
    Incontinence Urinaire - Stérilité Masculine - Dysfonctionnement érectile - Lithotritie Extracorporelle<br>
    Explorations Endoscopiques - Échographie - Débimétrie - Biopsies de la Prostate<br>
    Médecine Générale - Médecine du Travail
</div>