<link href="{{ public_path('vendor/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all" />

@php
    \Carbon\Carbon::setLocale('fr');
    $printDate = \Carbon\Carbon::now()->translatedFormat('d F Y');
@endphp

<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #333; background-color: #fff; }
    .section-title { font-size: 16px; font-weight: 600; color: #4463dc; margin-top: 20px; margin-bottom: 10px; text-transform: uppercase; }
    .card { border: 1px solid #e0e6ed; border-radius: 8px; margin-bottom: 20px; padding: 12px; }
    .card-body p { margin-bottom: 8px; }
    .footer-sig { margin-top: 40px; text-align: right; font-style: italic; color: #555; }
    .footer-sig strong { color: #000; }
    h3 { font-size: 20px; font-weight: 700; color: #222; text-transform: uppercase; }
    h5 { font-size: 15px; font-weight: 600; color: #4463dc; }
    .exam-block { margin-bottom: 15px; padding: 10px; background: #f2f6fc; border-radius: 6px; }
    .exam-list { list-style-type: disc; padding-left: 20px; margin: 0; }
    .exam-list li { margin-bottom: 4px; }
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

    {{-- DOCTOR & DATE --}}
    <table width="100%" style="border-collapse: collapse; margin-bottom: 16px;">
        <tr>
            <td style="vertical-align: top;">
                <p><strong>Dr {{ $prescription->user->name }} {{ $prescription->user->prenom }}</strong></p>
                <p><small>{{ $prescription->user->specialite ?? '' }}</small></p>
                <p><small>ONMC: {{ $prescription->user->onmc ?? '' }}</small></p>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <p><small>Douala, le {{ $printDate }}</small></p>
            </td>
        </tr>
    </table>

    {{-- PATIENT --}}
    <div class="card">
        <div class="card-body">
            <div class="section-title">Patient</div>
            <p><strong>Nom :</strong> {{ $prescription->patient->name }} {{ $prescription->patient->prenom }}</p>
        </div>
    </div>

    {{-- PRESCRIPTION TITLE --}}
    <div style="text-align: center; margin-bottom: 16px;">
        <h3>Bulletin d'Examens</h3>
    </div>

    {{-- EXAM SECTIONS --}}
    @foreach([
        'hematologie'   => 'Hématologie',
        'hemostase'     => 'Hémostase',
        'biochimie'     => 'Biochimie',
        'serologie'     => 'Sérologie',
        'hormonologie'  => 'Hormonologie',
        'marqueurs'     => 'Marqueurs',
        'bacteriologie' => 'Bactériologie',
        'spermiologie'  => 'Spermiologie',
        'urines'        => 'Urines',
    ] as $field => $label)
        @if($prescription->$field)
            <div class="exam-block">
                <h5>{{ $label }}</h5>
                <ul class="exam-list">
                    @foreach((array) $prescription->$field as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach

    {{-- SIGNATURE --}}
    <div class="footer-sig">
        <p><strong>Signature & Cachet du Médecin</strong></p>
    </div>

</div>

{{-- FIXED FOOTER BAND --}}
<div class="cmcu-footer">
    Urgences Urologiques - Cancérologie - Centre de la Prostate - Coelioscopie - Calcul Urinaire<br>
    Incontinence Urinaire - Stérilité Masculine - Dysfonctionnement érectile - Lithotritie Extracorporelle<br>
    Explorations Endoscopiques - Échographie - Débimétrie - Biopsies de la Prostate<br>
    Médecine Générale - Médecine du Travail
</div>