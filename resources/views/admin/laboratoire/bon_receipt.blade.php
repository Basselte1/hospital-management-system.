<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon Labo — {{ $laboratoire->numero_bon }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            background: #fff;
            color: #111;
            width: 80mm;
            margin: 0 auto;
            padding: 4mm;
        }
        .center  { text-align: center; }
        .right   { text-align: right; }
        .bold    { font-weight: 700; }
        .divider { border-top: 1px dashed #555; margin: 5px 0; }
        .row     { display: flex; justify-content: space-between; margin: 2px 0; }
        .big     { font-size: 14px; font-weight: 900; letter-spacing: 1px; }
        ul       { list-style: none; padding-left: 8px; }
        ul li::before { content: "• "; }
        .footer  { font-size: 9px; color: #555; text-align: center; margin-top: 6px; }
        .payment-box {
            border: 1px solid #111;
            padding: 5px 6px;
            margin: 6px 0;
            border-radius: 3px;
        }
        .payment-total {
            font-size: 15px;
            font-weight: 900;
            text-align: right;
            letter-spacing: 0.5px;
        }
        .stamp-box {
            border: 2px solid #111;
            padding: 4px 6px;
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 6px 0;
            text-transform: uppercase;
        }
        @media print {
            body { width: 100%; }
            .no-print { display: none !important; }
            @page { margin: 4mm; size: 80mm auto; }
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="center bold" style="font-size:13px;">CMCU — LABORATOIRE</div>
    <div class="center" style="color:#555; font-size:9px;">Douala, Cameroun — Tél: +237 xxx xxx xxx</div>
    <div class="divider"></div>

    {{-- PAID stamp --}}
    <div class="stamp-box">✔ PAYÉ — BON DE LABORATOIRE</div>

    {{-- Bon number & date --}}
    <div class="center big">{{ $laboratoire->numero_bon }}</div>
    <div class="center" style="font-size:9px; margin-top:2px;">
        Émis le : {{ now()->format('d/m/Y') }} à {{ now()->format('H:i') }}
    </div>
    <div class="divider"></div>

    {{-- Patient --}}
    <div class="row">
        <span>Patient :</span>
        <span class="bold">{{ strtoupper($laboratoire->patient->name ?? '') }} {{ $laboratoire->patient->prenom ?? '' }}</span>
    </div>
    <div class="row">
        <span>Dossier :</span>
        <span>{{ $laboratoire->patient->numero_dossier ?? '—' }}</span>
    </div>

    {{-- Médecin traitant --}}
    @if($laboratoire->prescripteur)
    <div class="row">
        <span>Médecin :</span>
        <span class="bold">Dr {{ $laboratoire->prescripteur->prenom }} {{ $laboratoire->prescripteur->name }}</span>
    </div>
    @if($laboratoire->prescripteur->specialite)
    <div class="row">
        <span>Spécialité :</span>
        <span>{{ $laboratoire->prescripteur->specialite }}</span>
    </div>
    @endif
    @elseif($laboratoire->prescription_source)
    <div class="row">
        <span>Prescrit par :</span>
        <span>{{ $laboratoire->prescription_source }}</span>
    </div>
    @else
    <div class="row">
        <span>Médecin :</span>
        <span style="color:#888;">Médecin externe / non renseigné</span>
    </div>
    @endif

    {{-- Prescription source --}}
    @if($laboratoire->prescription_source)
    <div class="row">
        <span>Service :</span>
        <span>{{ $laboratoire->prescription_source }}</span>
    </div>
    @endif

    {{-- Date prescription --}}
    @if($laboratoire->date_prescription)
    <div class="row">
        <span>Date prescription :</span>
        <span>{{ \Carbon\Carbon::parse($laboratoire->date_prescription)->format('d/m/Y') }}</span>
    </div>
    @endif

    <div class="divider"></div>

    {{-- Sections prescribed --}}
    <div class="bold" style="margin-bottom:3px;">Examens prescrits :</div>
    @php
        $sectionLabels = [
            'hematologie'   => 'Hématologie',
            'hemostase'     => 'Hémostase',
            'biochimie'     => 'Biochimie',
            'hormonologie'  => 'Hormonologie',
            'marqueurs'     => 'Marqueurs tumoraux',
            'bacteriologie' => 'Bactériologie',
            'spermiologie'  => 'Spermiologie',
            'urines'        => 'Urines / ECBU',
            'serologie'     => 'Sérologie',
            'parasitologie' => 'Parasitologie',
        ];
        $hasExamens = false;
    @endphp
    <ul>
        @foreach($sectionLabels as $key => $label)
            @if(!empty($laboratoire->$key))
            @php $hasExamens = true; @endphp
            <li>{{ $label }}</li>
            @endif
        @endforeach
        @if(!$hasExamens)
        <li style="color:#888;">Aucun examen précisé</li>
        @endif
    </ul>

    <div class="divider"></div>

    {{-- Preparation --}}
    @if($laboratoire->preparation_requise)
    <div><span class="bold">⚠ Préparation :</span> {{ $laboratoire->preparation_requise }}</div>
    <div class="divider"></div>
    @endif

    {{-- ── PAYMENT BLOCK ──────────────────────────────── --}}
    <div class="payment-box">
        <div class="bold" style="margin-bottom:4px; font-size:10px; text-transform:uppercase; letter-spacing:0.5px;">
            Détail du paiement
        </div>

        <div class="row">
            <span>Mode :</span>
            <span class="bold">
                @switch($laboratoire->mode_paiement ?? 'espèces')
                    @case('espèces') Espèces @break
                    @case('mobile_money') Mobile Money @break
                    @case('carte') Carte bancaire @break
                    @case('cheque') Chèque @break
                    @case('virement') Virement @break
                    @case('assurance') Prise en charge @break
                    @default {{ ucfirst($laboratoire->mode_paiement ?? 'Espèces') }}
                @endswitch
            </span>
        </div>

        @if(!empty($laboratoire->reference_paiement))
        <div class="row">
            <span>Réf. :</span>
            <span>{{ $laboratoire->reference_paiement }}</span>
        </div>
        @endif

        <div class="row" style="margin-top:4px;">
            <span class="bold" style="font-size:12px;">MONTANT ENCAISSÉ :</span>
        </div>
        <div class="payment-total">
            {{ number_format($laboratoire->montant_paye ?? 0, 0, ',', ' ') }} FCFA
        </div>

        <div style="font-size:9px; color:#555; margin-top:3px; text-align:right;">
            Encaissé le {{ now()->format('d/m/Y') }} à {{ now()->format('H:i') }}
        </div>
    </div>

    {{-- Secrétaire signature --}}
    @if($laboratoire->laborantin ?? $laboratoire->createdBy ?? null)
    <div class="row" style="margin-top:4px;">
        <span>Caissier(e) :</span>
        <span>{{ ($laboratoire->laborantin->prenom ?? '') }} {{ ($laboratoire->laborantin->name ?? '') }}</span>
    </div>
    @endif

    <div class="divider"></div>

    {{-- Instructions for patient --}}
    <div style="font-size:10px; margin-bottom:4px;">
        <span class="bold">Instructions :</span><br>
        Présentez ce bon au comptoir du laboratoire.<br>
        Ce bon est valable <span class="bold">7 jours</span> à compter de la date d'émission.
    </div>

    {{-- Footer --}}
    <div class="footer">
        Ce bon est votre preuve de paiement.<br>
        Conservez-le précieusement.<br>
        Merci de votre confiance — CMCU Douala
    </div>

    {{-- Print trigger --}}
    <div class="no-print" style="text-align:center; margin-top:12px;">
        <button onclick="window.print()"
                style="background:#1D4ED8; color:#fff; border:none; padding:8px 20px;
                       border-radius:8px; font-size:13px; cursor:pointer; font-family:sans-serif;">
            🖨 Imprimer le bon
        </button>
        <br>
        <a href="{{ route('laboratoire.index') }}"
           style="display:inline-block; margin-top:8px; font-family:sans-serif; font-size:12px;
                  color:#555; text-decoration:underline;">
            ← Retour à la liste
        </a>
    </div>

</body>
<script>
    window.addEventListener('load', function () {
        // Auto-print only if opened directly (not embedded)
        if (window.location.pathname.includes('bon')) {
            window.print();
        }
    });
</script>
</html>