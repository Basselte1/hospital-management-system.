<link href="{{ public_path('vendor/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all" />

<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #333; background-color: #fff; }
    .section-title { font-size: 16px; font-weight: 600; color: #4463dc; margin-top: 20px; margin-bottom: 10px; text-transform: uppercase; }
    .table th { width: 30%; font-weight: 600; color: #222; padding: 6px 8px; }
    .table td { color: #444; padding: 6px 8px; }
    .card { border: 1px solid #e0e6ed; border-radius: 8px; margin-bottom: 20px; padding: 12px; }
    .footer-sig { margin-top: 40px; text-align: right; font-style: italic; color: #555; }
    .footer-sig strong { color: #000; }
    .cmcu-footer {
        position: fixed; bottom: 0; left: 0; right: 0;
        background-color: #4463dc; color: #fff;
        text-align: center; font-size: 8px; padding: 5px 10px; line-height: 1.5;
    }
</style>

<div class="container">

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

    {{-- PATIENT SECTION --}}
    <div class="card">
        <div class="card-body">
            <div class="section-title">Patient</div>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th>Nom du patient :</th>
                        <td>{{ $fiche_intervention->nom_patient }} {{ $fiche_intervention->prenom_patient }}</td>
                    </tr>
                    <tr>
                        <th>Sexe :</th>
                        <td>{{ $fiche_intervention->sexe_patient }}</td>
                    </tr>
                    <tr>
                        <th>Date de naissance :</th>
                        <td>{{ $fiche_intervention->date_naiss_patient }}</td>
                    </tr>
                    <tr>
                        <th>Téléphone :</th>
                        <td>{{ $fiche_intervention->portable_patient }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- INTERVENTION SECTION --}}
    <div class="card">
        <div class="card-body">
            <div class="section-title">Intervention</div>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th>Chirurgien :</th>
                        <td>{{ $fiche_intervention->medecin }}</td>
                    </tr>
                    <tr>
                        <th>Aide opératoire :</th>
                        <td>{{ $fiche_intervention->aide_op }}</td>
                    </tr>
                    <tr>
                        <th>Type :</th>
                        <td>{{ $fiche_intervention->type_intervention }}</td>
                    </tr>
                    <tr>
                        <th>Durée :</th>
                        <td>{{ $fiche_intervention->dure_intervention }}</td>
                    </tr>
                    <tr>
                        <th>Position du patient :</th>
                        <td>
                            {{ $fiche_intervention->position_patient }}
                            @if(!empty($fiche_intervention->decubitus)) {{ $fiche_intervention->decubitus }} @endif
                            @if(!empty($fiche_intervention->laterale)) {{ $fiche_intervention->laterale }} @endif
                            @if(!empty($fiche_intervention->lombotomie)) {{ $fiche_intervention->lombotomie }} @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date intervention :</th>
                        <td>{{ $fiche_intervention->date_intervention }}</td>
                    </tr>
                    @if(!empty($fiche_intervention->hospitalisation))
                    <tr>
                        <th>Hospitalisation :</th>
                        <td>{{ $fiche_intervention->hospitalisation }}</td>
                    </tr>
                    @endif
                    @if(!empty($fiche_intervention->ambulatoire))
                    <tr>
                        <th>Ambulatoire :</th>
                        <td>{{ $fiche_intervention->ambulatoire }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Anesthésie :</th>
                        <td>{{ $fiche_intervention->anesthesie }}</td>
                    </tr>
                    <tr>
                        <th>Recommendation(s) :</th>
                        <td>{{ $fiche_intervention->recommendation }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- DOCTOR SIGNATURE --}}
    <div class="footer-sig">
        <p><strong>Dr {{ auth()->user()->name }}</strong></p>
    </div>

</div>

{{-- FIXED FOOTER BAND --}}
<div class="cmcu-footer">
    Urgences Urologiques - Cancérologie - Centre de la Prostate - Coelioscopie - Calcul Urinaire<br>
    Incontinence Urinaire - Stérilité Masculine - Dysfonctionnement érectile - Lithotritie Extracorporelle<br>
    Explorations Endoscopiques - Échographie - Débimétrie - Biopsies de la Prostate<br>
    Médecine Générale - Médecine du Travail
</div>