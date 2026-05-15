<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>@yield('doc_title', 'CMCU Document')</title>
<style>
/* ── Page Margins (Required for mPDF header/footer spacing) ───────── */
@page {
    margin-top:    45mm;
    margin-bottom: 28mm;
    margin-left:   10mm;
    margin-right:  10mm;
}

/* ── Base reset & Typography (From provided styling) ───────────────── */
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    color: #333;
    background: #fff;
    line-height: 1.5;
}
p, small { line-height: 1.5; margin: 0; }
h4 { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
u  { font-weight: 500; font-size: 14px; }
.text-center  { text-align: center !important; }
.text-right   { text-align: right  !important; }
.text-left    { text-align: left   !important; }
.text-primary { color: #0d6efd !important; }

/* ── Shared table helpers (From provided styling) ──────────────────── */
.table {
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 2rem;
    width: 100%;
}
.table thead th {
    background-color: #4463dc;
    color: #fff;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
    padding: 14px;
    border: 1px solid #dee2e6;
}
.table tbody td {
    vertical-align: middle;
    font-size: 13px;
    padding: 12px 14px;
    border: 1px solid #dee2e6;
}
.table tbody tr:nth-child(odd):not(.table-secondary):not(.table-primary):not(.section-header):not(.table-original):not(.table-reduction):not(.table-final) {
    background-color: #f9f9f9;
}

/* ── Section-header rows ───────────────────────────────────────────── */
.section-header td {
    background-color: #e3f2fd !important;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 14px;
    padding: 10px 14px !important;
}
.table-secondary td {
    background-color: #e9ecef !important;
    font-weight: 600;
    text-transform: uppercase;
    border-top: 2px solid #dee2e6;
}
.table-primary td {
    background-color: #dbeafe !important;
    font-weight: 700;
    border-top: 2px solid #4463dc;
}
.table-primary h5 { margin: 0; font-size: 1rem; font-weight: 700; color: #4463dc; }

/* ── Reduction & Final Totals ──────────────────────────────────────── */
.table-original td { background-color: #fff3f3 !important; border-top: 2px solid #dee2e6; }
.amount-struck     { text-decoration: line-through; color: #dc3545; font-weight: 600; }
.table-reduction td{ background-color: #fff8e1 !important; color: #856404; font-weight: 600; }
.table-final td    { background-color: #d1fae5 !important; font-weight: 700; border-top: 2px solid #10b981; }
.table-final h5    { margin: 0; font-size: 1rem; font-weight: 700; color: #065f46; }

/* ── mPDF Header Styles (Adapted to match new typography) ──────────── */
.cmcu-hdr-logo     { vertical-align: middle; padding-right: 8px; }
.cmcu-hdr-logo img { width: 58px; height: 58px; }
.cmcu-hdr-info     { width: 82%; text-align: center; vertical-align: middle; }
.clinic-name {
    font-weight: bold;
    font-size: 16px; /* Slightly larger to match new base font */
    color: #4463dc;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.clinic-line      { font-size: 11px; margin-top: 1px; color: #444; }
.clinic-blue      { color: #4463dc; }
.hdr-divider      { border: none; border-top: 3px solid #4463dc; margin: 5px 0 0 0; }

/* ── mPDF Footer Styles (Adapted from provided .cmcu-footer) ───────── */
/* Note: position:fixed removed as mPDF handles footer positioning via @page margins */
.cmcu-ftr-note {
    font-size: 11px;
    color: #555;
    font-style: italic;
    border-top: 1px solid #ccc;
    padding-top: 8px;
    margin-top: 16px;
    text-align: center;
}
.cmcu-ftr-band {
    background-color: #4463dc;
    color: #fff;
    text-align: center;
    font-size: 8px;
    padding: 5px 10px;
    line-height: 1.5;
}
</style>
@yield('pdf_styles')
</head>
<body>
{{-- ══════════════════════════════════════════════════════════════════
mPDF NAMED PAGE HEADER
══════════════════════════════════════════════════════════════════ --}}
<htmlpageheader name="cmcu_header">
<table style="border-collapse:collapse; width:100%; padding-bottom:5px;">
<tr>
<td class="cmcu-hdr-logo">
<img src="{{ public_path('admin/images/logo.jpg') }}" alt="Logo CMCU">
</td>
<td class="cmcu-hdr-info">
<div class="clinic-name">Centre Médico-Chirurgical d'Urologie</div>
<div class="clinic-line">ONMC : N°5531 DÉCISION N°007/10/D/ONMC/P/SG/MM</div>
<div class="clinic-line">Arrêté N° 3203/A/MINSANTE/SG/DOSTS/SDOS/SFSP</div>
<div class="clinic-line">Tél : +237 233 42 33 89 / +237 698 87 39 45 / +237 674 06 89 88</div>
<div class="clinic-line">Site internet : www.cmcu-cm.com | Email : cmcu_cmcu@yahoo.fr</div>
<div class="clinic-line">Situé à la vallée Manga Bell Douala - Bali</div>
<div class="clinic-line clinic-blue">Consultation sur rendez-vous</div>
<div class="clinic-line">N° de contribuable : P016400474386D</div>
</td>
</tr>
</table>
<hr class="hdr-divider">
</htmlpageheader>

{{-- ══════════════════════════════════════════════════════════════════
mPDF NAMED PAGE FOOTER
══════════════════════════════════════════════════════════════════ --}}
<htmlpagefooter name="cmcu_footer">
{{-- Per-document disclaimer --}}
@hasSection('pdf_footer_note')
<div class="cmcu-ftr-note">@yield('pdf_footer_note')</div>
@endif

<div class="cmcu-ftr-band">
Urgences Urologiques - Cancérologie - Centre de la Prostate - Coelioscopie - Calcul Urinaire<br>
Incontinence Urinaire - Stérilité Masculine - Dysfonctionement érectile - Lithotritie Extracorporelle<br>
Explorations Endoscopiques - Échographie - Débimétrie - Biopsies de la Prostate<br>
Médecine Générale - Médecine du Travail
&nbsp;&nbsp;&nbsp;<span style="float:right; padding-right:4px;">Page {PAGENO}/{nbpg}</span>
</div>
</htmlpagefooter>

{{-- Activate header + footer on ALL pages --}}
<sethtmlpageheader name="cmcu_header" value="on" show-this-page="1"/>
<sethtmlpagefooter name="cmcu_footer" value="on"/>

{{-- ══════════════════════════════════════════════════════════════════
CONTENT AREA
══════════════════════════════════════════════════════════════════ --}}
<div id="pdf-content">
@yield('content')
</div>
</body>
</html>



