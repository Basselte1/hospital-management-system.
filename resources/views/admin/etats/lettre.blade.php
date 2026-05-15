<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>LETTRE DE CONSULTATION - {{ $patient->name }} {{ $patient->prenom }}</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; line-height: 1.5; color: #333; margin: 0; padding: 20px 40px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header-logo { width: 18%; vertical-align: middle; padding-right: 15px; }
        .header-info { width: 82%; text-align: center; font-size: 9pt; line-height: 1.4; }
        .header-title { font-weight: bold; font-size: 13pt; color: #4463dc; text-transform: uppercase; margin-bottom: 3px; }
        .divider { border-top: 3px solid #4463dc; margin: 8px 0 15px 0; }
        .meta-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 10pt; }
        .subject { text-align: center; margin: 25px 0; font-weight: bold; text-decoration: underline; font-size: 12pt; }
        .patient-id { text-align: center; margin-bottom: 20px; font-style: italic; }
        .closing { margin-top: 30px; }
        .signature-block { margin-top: 40px; text-align: right; }
        .signature-name { font-weight: bold; margin-top: 25px; }
        .footer-band { position: fixed; bottom: 0; left: 0; right: 0; background-color: #4463dc; color: #fff; text-align: center; font-size: 7pt; padding: 4px 15px; line-height: 1.4; }
        @media print { body { padding: 15mm 25mm; } }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('admin/images/logo.jpg') }}" style="width: 75px; height: auto;" alt="Logo CMCU">
            </td>
            <td class="header-info">
                <div class="header-title">Centre Médico-Chirurgical d'Urologie</div>
                <div>ONMC : N°5531 - Décision N°007/10/D/ONMC/P/SG/MM</div>
                <div>Arrêté N° 3203/A/MINSANTE/SG/DOSTS/SDOS/SFSP</div>
                <div>Tél : +237 233 42 33 89 / +237 698 87 39 45 / +237 674 06 89 88</div>
                <div>www.cmcu-cm.com | cmcu_cmcu@yahoo.fr</div>
                <div>Vallée Manga Bell Douala - Bali | Consultation sur rendez-vous</div>
                <div>N° Contribuable : P016400474386D</div>
            </td>
        </tr>
    </table>
    <div class="divider"></div>

    <div class="meta-row">
        <div>
            <strong>Dr {{ $consultations->user->name }} {{ $consultations->user->prenom }}</strong><br>
            {{ $consultations->user->specialite }}
            @if($consultations->user->onmc) - ONMC : {{ $consultations->user->onmc }} @endif
        </div>
        <div>
            Douala, le {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            @if($consultations->medecin_r)
                À l'attention du Dr {{ $consultations->medecin_r }}
            @endif
        </div>
    </div>

    <div class="subject">LETTRE DE LIAISON - CONSULTATION D'UROLOGIE</div>

    @php $civilite = ($dossier && $dossier->sexe === 'Masculin') ? 'M.' : 'Mme'; @endphp

    <div class="patient-id">
        Concernant : {{ $civilite }}
        <strong>{{ $patient->name }} {{ $patient->prenom }}</strong>
        @if($dossier && $dossier->date_naissance)
            <br>Né{{ $civilite === 'Mme' ? 'e' : '' }} le {{ \Carbon\Carbon::parse($dossier->date_naissance)->translatedFormat('d F Y') }}
            @if($dossier->age) • Âge : {{ $dossier->age }} ans @endif
        @endif
    </div>

    <p>Cher Confrère,
    J'ai l'honneur de vous adresser ce courrier suite à la consultation d'urologie effectuée le
    <strong>{{ \Carbon\Carbon::parse($consultations->created_at ?? now())->translatedFormat('d F Y') }}</strong>
    concernant <strong>{{ $civilite }} {{ $patient->name }} {{ $patient->prenom }}</strong>.
    @if($consultations->motif_c)
        Le motif principal était : <strong>{{ $consultations->motif_c }}</strong>.
    @endif
    @if($consultations->interrogatoire || $consultations->antecedent_m || $consultations->antecedent_c || $consultations->allergie)
        L'anamnèse a révélé
        @if($consultations->interrogatoire) {{ $consultations->interrogatoire }} @endif
        @if($consultations->antecedent_m) avec antécédents médicaux {{ $consultations->antecedent_m }}, @endif
        @if($consultations->antecedent_c) chirurgicaux {{ $consultations->antecedent_c }}, @endif
        @if($consultations->allergie) et allergies connues : {{ $consultations->allergie }}. @endif
    @endif
    @if($consultations->groupe)
        Les données biologiques incluent le groupe sanguin : {{ $consultations->groupe }}.
    @endif
    </p>

    <p>
    @if($consultations->examen_p)
        L'examen physique a montré : {{ $consultations->examen_p }}.
    @endif
    @if($consultations->examen_c)
        Les examens complémentaires réalisés étaient : {!! nl2br(e($consultations->examen_c)) !!}.
    @endif
    @if($consultations->diagnostic)
        Le diagnostic retenu est : <strong>{{ $consultations->diagnostic }}</strong>.
    @endif
    @if($consultations->proposition_therapeutique)
        Sur cette base, le traitement proposé est le suivant : <strong>{{ $consultations->proposition_therapeutique }}</strong>.
    @endif
    @if($consultations->proposition)
        Les modalités de suivi incluent :
        @if($consultations->proposition == 'Hospitalisation')
            une hospitalisation pour suivi médical
        @endif
        @if($consultations->proposition == 'Intervention chirurgicale')
            une intervention chirurgicale
            @if($consultations->type_intervention) (type : <strong>{{ $consultations->type_intervention }}</strong>@if($consultations->date_intervention), prévue le <strong>{{ $consultations->date_intervention }}</strong>@endif) @endif
        @endif
        @if($consultations->proposition == 'Consultation')
            une consultation de contrôle
            @if($consultations->date_consultation) prévue le {{ $consultations->date_consultation }} @endif
        @endif
        @if($consultations->proposition == "Consultation d'anesthésiste")
            une consultation pré-anesthésique
            @if($consultations->date_consultation_anesthesiste) prévue le {{ $consultations->date_consultation_anesthesiste }} @endif
        @endif
        @if($consultations->proposition == 'Actes à réaliser' && $consultations->acte)
            des actes complémentaires : {!! nl2br(e($consultations->acte)) !!}
        @endif
        .
    @endif
    </p>

    <p>Je reste à votre entière disposition pour tout complément d'information concernant la prise en charge de ce patient.
        Veuillez agréer, Cher Confrère, l'expression de mes salutations les plus confraternelles.</p>

    <div class="signature-block">
        <div class="signature-name">Dr {{ $consultations->user->name }} {{ $consultations->user->prenom }}</div>
        <div>{{ $consultations->user->specialite }}</div>
        @if($consultations->user->onmc)
            <div style="font-size: 9pt;">ONMC : {{ $consultations->user->onmc }}</div>
        @endif
    </div>

    <div class="footer-band">
        Urgences Urologiques • Cancérologie • Centre de la Prostate • Cœlioscopie • Lithiase urinaire •
        Incontinence • Stérilité masculine • Dysfonction érectile • Lithotritie extracorporelle •
        Endoscopie • Échographie • Débitmétrie • Biopsies prostatiques • Médecine générale • Médecine du travail
    </div>

</body>
</html>