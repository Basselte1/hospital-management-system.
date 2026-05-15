<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- <title>Facture de {{ strtolower($patient->details_motif) ?? 'consultation'}}</title> -->
  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facture de {{ $facture->details_motif ? strtolower($facture->details_motif) : 'consultation'}}</title>
    <style>
        body { font-size: 3px }
        thead > tr > th {
            text-align: center;
            padding: 5px;
        }
        td {
            vertical-align: middle;
        }
        .container{
            border: 1px solid #000;
        }
        .logo{
            width: 40px;
            padding-top: 10px;
        }
        #inventory-invoice{
            padding: 20px;
        }
        #inventory-invoice a{text-decoration:none ! important;}
        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 480px;
            padding: 12px
        }
        .invoice header {
            padding: 10px 0;
            margin-bottom: 8px;
            border-bottom: 1px solid #3989c6
        }
        .invoice .company-details {
            text-align: right
        }
        .invoice .company-details .name {
            margin-top: 0;
            margin-bottom: 0
        }
        .invoice .contacts {
            margin-bottom: 10px;
            text-align: center
        }
        .invoice .invoice-to {
            text-align: left
        }
        .invoice .invoice-to .to {
            margin-top: 0;
            margin-bottom: 0
        }
        .invoice .invoice-details {
            text-align: right
        }
        .invoice .invoice-details .invoice-id {
            margin-top: 0;
            text-align: center;
            color: #3989c6
        }
        .invoice main {
            padding-bottom: 30px
        }
        .invoice main .thanks {
            margin-top: -50px;
            font-size: 2em;
            margin-bottom: 50px
        }
        .invoice main .notices {
            padding-left: 6px;
            border-left: 6px solid #3989c6
        }
        .invoice main .notices .notice {
            font-size: 1.2em
        }
        .invoice table {
            width: 90%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px
        }
        .invoice table td,.invoice table th {
            padding: 10px;
            background: #eee;
            border-bottom: 1px solid #fff
        }
        .invoice table th{
            white-space: nowrap;
            font-weight: 300;
            font-size: 14px;
            border:1px solid #fff;
        }
        .invoice table td{
            border:1px solid #fff;
        }
        .invoice table td h3 {
            margin: 0;
            font-weight: 300;
            color: #3989c6;
            font-size: 1.2em
        }
        .invoice table .tax,.invoice table .total,.invoice table .unit {
            text-align: right;
            font-size: 1.2em
        }
        .invoice table .no {
            color: #fff;
            font-size: 1.6em;
            background: #17a2b8
        }
        .invoice table .unit {
            background: #ddd
        }
        .invoice table .total {
            background: #17a2b8;
            color: #fff
        }
        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            font-size: 1.2em;
            border-top: 1px solid #aaa
        }
        .invoice table tfoot tr:first-child td {
            border-top: none
        }
        .invoice table tfoot tr:last-child td {
            color: #3989c6;
            font-size: 1.4em;
            border-top: 1px solid #3989c6
        }
        .invoice table tfoot tr td:first-child {
            border: none
        }
        .invoice footer {
            width: 90%;
            text-align: center;
            color: #777;
            font-size: 6px;
            border-top: 1px solid #aaa;
            padding: 8px 0
        }
        @media print {
            .invoice {
                font-size: 11px!important;
                overflow: hidden!important
            }
            .invoice footer {
                position: absolute;
                bottom: 8px;
                page-break-after: always
            }
            .invoice>div:last-child {
                page-break-before: always
            }
        }

        #watermark {
                position: fixed;
                top: 1%;
                width: 100%;
                text-align: center;
                opacity: .2;
                transform: rotate(10deg);
                transform-origin: 50% 50%;
            }
        
    </style>
</head>
<body>

<div id="watermark">
            <img src="{{ public_path('admin/images/watermake.JPG') }}" height="100%" width="100%" />
        </div>
<div class="container">
    <div class="row text-center">
        <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="logo">
        <h6><strong>CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</strong></h6>
        <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
        <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
        <p><h6>www.cmcu-cm.com</h6></p>
    </div>

<div id="inventory-invoice">

    {{--<div class="toolbar hidden-print">--}}
       {{--<hr>--}}
    {{--</div>--}}
    <div class="invoice overflow-auto">
        <div style="min-width: 300px">
            <main>
                <div class="row contacts">

                    <div  class="col invoice-details ">
                        <h6 class="invoice-id">RECU {{ strtoupper($facture->details_motif) ?? 'CONSULTATION'}} N°{{ $patient->numero_dossier }}</h6>
                        <br>
                    </div>
                </div>
                @if($patient->assurancec)
                <h6 class="text-center">ASSURANCE:{{ $facture->assurance }}</h6>
                @else
                @endif
                <h6 class="text-center">{{ $patient->demarcheur }}</h6>
                @if($facture->assurancec)
                  <h6>PART ASSURANCE: {{ $facture->assurancec }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PART PATIENT: {{ $patient->assurec }}</h6>
                  @else
               @endif  
                 <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                        <tr>
                            <th class="text-left">NOM</th>
                            <th class="text-left">PRENOM</th>
                            <th class="text-left"> MONTANT (FCFA)</th>
                            <th class="text-left"> AVANCE </th>
                            <th class="text-left"> RESTE </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-left" ><h5> {{ $patient->name }}</h5></td>
                            <td class="text-left" ><h5> {{ $patient->prenom }}</h5></td>
                            <td class="text-left"><h4> {{ $facture->montant }}</h4></td>
                            @if($patient->avance)
                            <td class="text-left"><h4>{{ $facture->avance }}</h4></td>
                            @else
                            <td class="text-left"><h4>0</h4></td>
                            @endif
                            @if($patient->avance)
                            <td class="text-left"><h4>{{ $facture->reste }}</h4></td>
                            @else
                                <td class="text-left"><h4>0</h4></td>
                            @endif
                        </tr>
                    <tr>
                        <div class="notices">
                           <H6><div>LA CAISSE:{{ $patient->user->prenom }} {{ $patient->user->name }}</div></H6>
                           <H6><div class="notice">Douala,{{ $patient->created_at->toFormattedDateString() }}</div></H6>
                        </div>
                    </tr>
                    </tbody>
                  </table>
                </main>
            <footer>
                Centre Medico-churirgical d'urologie situé a la Vallée Douala Manga Bell Douala-Bali.
                              TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945.
                              SITE WEB: http://www.cmcu-cm.com
            </footer>
        </div>
     </div>
</div>
 <div class="row text-center">
        <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="">
        <h6><strong>CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</strong></h6>
        <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
        <h6>TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945</h6>
        <p><h6>www.cmcu-cm.com</h6></p>
    </div>
    <div id="inventory-invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 300px">
                <main>
                    <div class="row contacts">

                        <div  class="col invoice-details ">
                            <h6 class="invoice-id">RECU {{ strtoupper($facture->details_motif) ?? 'CONSULTATION'}} N°{{ $patient->numero_dossier }}</h6>
                            <br>
                        </div>
                    </div>
                    @if($facture->assurancec)
                    <h6 class="text-center">ASSURANCE:{{ $facture->assurance }}</h6>
                    @else
                    @endif
                    <h6 class="text-center">{{ $patient->demarcheur }}</h6>
                    @if($facture->assurancec)
                      <h6>PART ASSURANCE: {{ $facture->assurancec }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PART PATIENT: {{ $patient->assurec }}</h6>
                     @else
                     
                   @endif
                        <table border="0" cellspacing="0" cellpadding="0">
                        <thead>
                        <tr>
                        <th class="text-left">NOM</th>
                            <th class="text-left">PRENOM</th>
                            <th class="text-left"> MONTANT (FCFA) </th>
                            <th class="text-left"> AVANCE </th>
                            <th class="text-left"> RESTE </th>
                         </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td class="text-left" ><h5> {{ $patient->name }}</h5></td>
                            <td class="text-left" ><h5> {{ $patient->prenom }}</h5></td>
                            <td class="text-left"><h4> {{ $facture->montant }}</h4></td>
                            @if($patient->avance)
                                <td class="text-left"><h4>{{ $facture->avance }}</h4></td>
                                @else
                                <td class="text-left"><h4>0</h4></td>
                            @endif
                             @if($patient->avance)
                                <td class="text-left"><h4>{{ $facture->reste }}</h4></td>
                                @else
                                <td class="text-left"><h4>0</h4></td>
                            @endif
                         </tr>
                        <tr>
                            <div class="notices">
                                <H6><div>LA CAISSE: {{ $patient->user->prenom }} {{ $patient->user->name }}</div></H6>
                                <H6><div class="notice">Douala,{{ $patient->created_at->toFormattedDateString() }}</div></H6>
                            </div>
                        </tr>
                        </tbody>
                    </table>
                </main>
                <footer>
                    Centre Medico-churirgical d'urologie situé a la Vallée Douala Manga Bell Douala-Bali.
                    TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945.
                    SITE WEB: http://www.cmcu-cm.com
                </footer>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
</body>
</html>









































<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facture de {{ $facture->details_motif ? strtolower($facture->details_motif) : 'consultation'}}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .page-container {
            width: 100%;
            max-width: 21cm;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo {
            width: 60px;
            margin-bottom: 5px;
        }
        .header h6 {
            margin: 3px 0;
            font-size: 12px;
        }
        .invoice-details {
            text-align: center;
            margin: 15px 0;
        }
        .invoice-id {
            font-size: 14px;
            font-weight: bold;
            color: #3989c6;
            margin-bottom: 10px;
        }
        .insurance-info {
            text-align: center;
            font-size: 11px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th, table td {
            padding: 8px;
            text-align: left;
            background: #f5f5f5;
            border: 1px solid #ddd;
        }
        table th {
            font-weight: bold;
            font-size: 12px;
        }
        .amount {
            font-size: 14px;
            font-weight: bold;
        }
        .notices {
            margin-top: 20px;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #aaa;
        }
        .page-break {
            page-break-after: always;
        }
        @media print {
            .page-container { padding: 0; }
        }
    </style>
</head>
<body>

<!-- First Copy -->
<div class="page-container">
    <div class="header">
        <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="Logo">
        <h6><strong>CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</strong></h6>
        <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
        <h6>TEL: (+237) 233 423 389 / 674 068 988 / 698 873 945</h6>
        <h6>www.cmcu-cm.com</h6>
    </div>

    <div class="invoice-details">
        <div class="invoice-id">
            RECU {{ strtoupper($facture->details_motif ?? 'CONSULTATION') }} N°{{ $patient->numero_dossier }}
        </div>
    </div>

    @if($facture->assurance)
        <div class="insurance-info"><strong>ASSURANCE: {{ $facture->assurance }}</strong></div>
    @endif

    @if($patient->demarcheur)
        <div class="insurance-info">{{ $patient->demarcheur }}</div>
    @endif

    @if($facture->assurancec && $facture->assurec)
        <div class="insurance-info">
            <strong>PART ASSURANCE:</strong> {{ number_format($facture->assurancec, 0, ',', ' ') }} FCFA
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>PART PATIENT:</strong> {{ number_format($facture->assurec, 0, ',', ' ') }} FCFA
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>NOM</th>
                <th>PRENOM</th>
                <th>MONTANT (FCFA)</th>
                <th>AVANCE</th>
                <th>RESTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->prenom }}</td>
                <td class="amount">{{ number_format($facture->montant, 0, ',', ' ') }}</td>
                <td class="amount">{{ number_format($facture->avance ?? 0, 0, ',', ' ') }}</td>
                <td class="amount">{{ number_format($facture->reste ?? 0, 0, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="notices">
        <div><strong>LA CAISSE:</strong> {{ $patient->user ? $patient->user->prenom . ' ' . $patient->user->name : 'N/A' }}</div>
        <div><strong>DATE:</strong> Douala, {{ $patient->created_at->format('d/m/Y') }}</div>
    </div>

    <div class="footer">
        Centre Medico-chirurgical d'urologie situé à la Vallée Douala Manga Bell Douala-Bali.<br>
        TEL: (+237) 233 423 389 / 674 068 988 / 698 873 945 - www.cmcu-cm.com
    </div>
</div>

<!-- Page Break -->
<div class="page-break"></div>

<!-- Second Copy (Duplicate) -->
<div class="page-container">
    <div class="header">
        <img class="logo" src="{{ public_path('admin/images/logo.jpg') }}" alt="Logo">
        <h6><strong>CENTRE MEDICO-CHIRURGICAL D'UROLOGIE</strong></h6>
        <h6>VALLEE MANGA BELL DOUALA-BALI</h6>
        <h6>TEL: (+237) 233 423 389 / 674 068 988 / 698 873 945</h6>
        <h6>www.cmcu-cm.com</h6>
    </div>

    <div class="invoice-details">
        <div class="invoice-id">
            RECU {{ strtoupper($facture->details_motif ?? 'CONSULTATION') }} N°{{ $patient->numero_dossier }}
        </div>
    </div>

    @if($facture->assurance)
        <div class="insurance-info"><strong>ASSURANCE: {{ $facture->assurance }}</strong></div>
    @endif

    @if($patient->demarcheur)
        <div class="insurance-info">{{ $patient->demarcheur }}</div>
    @endif

    @if($facture->assurancec && $facture->assurec)
        <div class="insurance-info">
            <strong>PART ASSURANCE:</strong> {{ number_format($facture->assurancec, 0, ',', ' ') }} FCFA
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>PART PATIENT:</strong> {{ number_format($facture->assurec, 0, ',', ' ') }} FCFA
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>NOM</th>
                <th>PRENOM</th>
                <th>MONTANT (FCFA)</th>
                <th>AVANCE</th>
                <th>RESTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->prenom }}</td>
                <td class="amount">{{ number_format($facture->montant, 0, ',', ' ') }}</td>
                <td class="amount">{{ number_format($facture->avance ?? 0, 0, ',', ' ') }}</td>
                <td class="amount">{{ number_format($facture->reste ?? 0, 0, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="notices">
        <div><strong>LA CAISSE:</strong> {{ $patient->user ? $patient->user->prenom . ' ' . $patient->user->name : 'N/A' }}</div>
        <div><strong>DATE:</strong> Douala, {{ $patient->created_at->format('d/m/Y') }}</div>
    </div>

    <div class="footer">
        Centre Medico-chirurgical d'urologie situé à la Vallée Douala Manga Bell Douala-Bali.<br>
        TEL: (+237) 233 423 389 / 674 068 988 / 698 873 945 - www.cmcu-cm.com
    </div>
</div>

</body>
</html>