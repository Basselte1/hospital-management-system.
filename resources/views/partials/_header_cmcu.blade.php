{{--
    CMCU Letterhead Header Partial
    Usage: @include('path.to._header_cmcu')
    Place this at the TOP of every PDF blade, inside the <body> (or main container).
    The header renders in the normal HTML flow — no mPDF SetHTMLHeader needed.
--}}
<table width="100%" style="border-collapse: collapse; margin-bottom: 0; font-family: DejaVu Sans, Arial, sans-serif;">
    <tr>
        <td width="18%" style="vertical-align: middle; padding-right: 8px;">
            <img src="{{ public_path('admin/images/logo.jpg') }}" style="width: 80px; height: auto;" alt="Logo CMCU">
        </td>
        <td width="82%" style="text-align: center; vertical-align: middle;">
            <div style="font-weight: bold; font-size: 14px; color: #4463dc; text-transform: uppercase; letter-spacing: 0.5px;">
                CENTRE MÉDICO-CHIRURGICAL D'UROLOGIE
            </div>
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
<div style="border-top: 3px solid #4463dc; margin: 6px 0 12px 0;"></div>