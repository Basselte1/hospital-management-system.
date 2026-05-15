

<style>
/* ── Tokens ──────────────────────────────────────────────────────────── */
:root {
    --fc-blue:        #1565C0;
    --fc-blue-light:  #E3F2FD;
    --fc-blue-mid:    #1976D2;
    --fc-teal:        #00695C;
    --fc-teal-light:  #E0F2F1;
    --fc-amber:       #F57F17;
    --fc-amber-light: #FFF8E1;
    --fc-green:       #2E7D32;
    --fc-green-light: #E8F5E9;
    --fc-red:         #C62828;
    --fc-red-light:   #FFEBEE;
    --fc-purple:      #4527A0;
    --fc-purple-light:#EDE7F6;
    --fc-gray:        #455A64;
    --fc-gray-light:  #ECEFF1;
    --fc-border:      #CFD8DC;
    --fc-text:        #263238;
    --fc-text-muted:  #607D8B;
    --fc-surface:     #FAFAFA;
    --fc-white:       #FFFFFF;
    --fc-radius:      6px;
    --fc-radius-lg:   10px;
    --fc-shadow:      0 1px 4px rgba(0,0,0,.08);
    --fc-shadow-md:   0 3px 10px rgba(0,0,0,.12);
}

/* ── Layout page ─────────────────────────────────────────────────────── */
.fc-page { padding: 20px 24px; background: var(--fc-surface); min-height: 100vh; }
.fc-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px; flex-wrap: wrap; gap: 10px;
}
.fc-page-title {
    font-size: 20px; font-weight: 700; color: var(--fc-text);
    display: flex; align-items: center; gap: 10px;
}
.fc-page-title .fc-title-icon {
    width: 38px; height: 38px; border-radius: var(--fc-radius);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}

/* ── KPI cards ───────────────────────────────────────────────────────── */
.fc-kpi-row { display: grid; grid-template-columns: repeat(auto-fit,minmax(150px,1fr)); gap: 12px; margin-bottom: 20px; }
.fc-kpi {
    background: var(--fc-white); border: 1px solid var(--fc-border);
    border-radius: var(--fc-radius-lg); padding: 14px 16px;
    box-shadow: var(--fc-shadow); position: relative; overflow: hidden;
}
.fc-kpi::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: var(--fc-radius-lg) var(--fc-radius-lg) 0 0;
}
.fc-kpi.blue::before   { background: var(--fc-blue); }
.fc-kpi.teal::before   { background: var(--fc-teal); }
.fc-kpi.green::before  { background: var(--fc-green); }
.fc-kpi.amber::before  { background: var(--fc-amber); }
.fc-kpi.red::before    { background: var(--fc-red); }
.fc-kpi.purple::before { background: var(--fc-purple); }
.fc-kpi-label { font-size: 11px; font-weight: 600; color: var(--fc-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
.fc-kpi-value { font-size: 24px; font-weight: 700; color: var(--fc-text); line-height: 1.1; }
.fc-kpi-sub   { font-size: 11px; color: var(--fc-text-muted); margin-top: 3px; }

/* ── Toolbar ─────────────────────────────────────────────────────────── */
.fc-toolbar {
    background: var(--fc-white); border: 1px solid var(--fc-border);
    border-radius: var(--fc-radius-lg); padding: 14px 16px;
    margin-bottom: 16px; box-shadow: var(--fc-shadow);
    display: flex; align-items: flex-end; flex-wrap: wrap; gap: 12px;
}
.fc-toolbar-group { display: flex; flex-direction: column; gap: 4px; }
.fc-toolbar-label { font-size: 11px; font-weight: 600; color: var(--fc-text-muted); text-transform: uppercase; }
.fc-toolbar .form-control, .fc-toolbar .form-select {
    height: 36px; font-size: 13px; border-color: var(--fc-border);
    border-radius: var(--fc-radius); color: var(--fc-text); min-width: 0;
}
.fc-toolbar-sep { width: 1px; background: var(--fc-border); align-self: stretch; margin: 0 4px; }

/* ── Boutons ─────────────────────────────────────────────────────────── */
.fc-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0 14px; height: 36px; border-radius: var(--fc-radius);
    font-size: 13px; font-weight: 500; border: 1px solid transparent;
    cursor: pointer; transition: all .15s; white-space: nowrap; text-decoration: none;
}
.fc-btn-primary   { background: var(--fc-blue);   color: #fff; border-color: var(--fc-blue-mid); }
.fc-btn-primary:hover { background: #0D47A1; color: #fff; }
.fc-btn-success   { background: var(--fc-green);  color: #fff; border-color: var(--fc-green); }
.fc-btn-success:hover { background: #1B5E20; color: #fff; }
.fc-btn-warning   { background: var(--fc-amber);  color: #fff; border-color: var(--fc-amber); }
.fc-btn-warning:hover { background: #E65100; color: #fff; }
.fc-btn-danger    { background: var(--fc-red);    color: #fff; border-color: var(--fc-red); }
.fc-btn-danger:hover { background: #B71C1C; color: #fff; }
.fc-btn-info      { background: var(--fc-teal);   color: #fff; border-color: var(--fc-teal); }
.fc-btn-info:hover { background: #004D40; color: #fff; }
.fc-btn-light     { background: var(--fc-white);  color: var(--fc-text); border-color: var(--fc-border); }
.fc-btn-light:hover { background: var(--fc-gray-light); }
.fc-btn-sm { height: 30px; padding: 0 10px; font-size: 12px; }
.fc-btn-xs { height: 26px; padding: 0 8px;  font-size: 11px; }

/* ── Tableau ─────────────────────────────────────────────────────────── */
.fc-table-card {
    background: var(--fc-white); border: 1px solid var(--fc-border);
    border-radius: var(--fc-radius-lg); overflow: hidden; box-shadow: var(--fc-shadow);
}
.fc-table-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; border-bottom: 1px solid var(--fc-border);
    background: var(--fc-surface); flex-wrap: wrap; gap: 8px;
}
.fc-table-card-header-left { display: flex; align-items: center; gap: 10px; }
.fc-table-responsive { overflow-x: auto; }
.fc-table {
    width: 100%; border-collapse: collapse; font-size: 13px; color: var(--fc-text);
}
.fc-table thead th {
    background: #ECEFF1; color: var(--fc-gray); font-weight: 600;
    font-size: 11px; text-transform: uppercase; letter-spacing: .4px;
    padding: 10px 12px; border-bottom: 2px solid var(--fc-border);
    white-space: nowrap; position: sticky; top: 0;
}
.fc-table tbody tr { border-bottom: 1px solid #ECEFF1; transition: background .1s; }
.fc-table tbody tr:hover { background: #F5F9FF; }
.fc-table tbody tr.soldee { background: #F1F8E9; }
.fc-table tbody tr.soldee:hover { background: #E8F5E9; }
.fc-table tbody td { padding: 10px 12px; vertical-align: middle; }
.fc-table tfoot td {
    padding: 10px 12px; font-weight: 700; font-size: 13px;
    background: #ECEFF1; border-top: 2px solid var(--fc-border);
}

/* ── Badges ──────────────────────────────────────────────────────────── */
.fc-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap;
}
.fc-badge-success { background: var(--fc-green-light); color: var(--fc-green); }
.fc-badge-warning { background: var(--fc-amber-light); color: var(--fc-amber); }
.fc-badge-info    { background: var(--fc-blue-light);  color: var(--fc-blue);  }
.fc-badge-danger  { background: var(--fc-red-light);   color: var(--fc-red);   }
.fc-badge-purple  { background: var(--fc-purple-light);color: var(--fc-purple);}
.fc-badge-teal    { background: var(--fc-teal-light);  color: var(--fc-teal);  }
.fc-badge-gray    { background: var(--fc-gray-light);  color: var(--fc-gray);  }

/* ── Reste coloré ────────────────────────────────────────────────────── */
.fc-reste-zero  { color: var(--fc-green); font-weight: 700; }
.fc-reste-nonzero { color: var(--fc-red); font-weight: 700; }

/* ── Proforma banner ─────────────────────────────────────────────────── */
.fc-proforma-info {
    background: var(--fc-amber-light); border: 1px solid #FFB300;
    border-radius: var(--fc-radius); padding: 8px 14px; font-size: 12px;
    color: #6D4C00; display: flex; align-items: center; gap: 8px; margin-bottom: 14px;
}

/* ── Bilan journalier section ────────────────────────────────────────── */
.fc-bilan-section {
    background: var(--fc-white); border: 1px solid var(--fc-border);
    border-radius: var(--fc-radius-lg); padding: 16px 20px;
    box-shadow: var(--fc-shadow); margin-top: 20px;
}
.fc-bilan-title {
    font-size: 14px; font-weight: 700; color: var(--fc-text);
    margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    padding-bottom: 10px; border-bottom: 1px solid var(--fc-border);
}

/* ── Action buttons groupe ───────────────────────────────────────────── */
.fc-actions { display: flex; gap: 4px; flex-wrap: nowrap; }
.fc-action-btn {
    width: 30px; height: 30px; border-radius: var(--fc-radius);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; cursor: pointer; border: 1px solid transparent;
    transition: all .15s; text-decoration: none;
}
.fc-action-btn.print-ok  { background: var(--fc-green-light);  color: var(--fc-green); border-color: #A5D6A7; }
.fc-action-btn.print-ok:hover  { background: var(--fc-green); color: #fff; }
.fc-action-btn.print-pf  { background: var(--fc-amber-light);  color: var(--fc-amber); border-color: #FFE082; }
.fc-action-btn.print-pf:hover  { background: var(--fc-amber); color: #fff; }
.fc-action-btn.view      { background: var(--fc-blue-light);   color: var(--fc-blue);  border-color: #90CAF9; }
.fc-action-btn.view:hover { background: var(--fc-blue); color: #fff; }
.fc-action-btn.add       { background: var(--fc-teal-light);   color: var(--fc-teal);  border-color: #80CBC4; }
.fc-action-btn.add:hover  { background: var(--fc-teal); color: #fff; }
.fc-action-btn.edit      { background: #E3F2FD; color: #1565C0; border-color: #90CAF9; }
.fc-action-btn.edit:hover { background: var(--fc-blue); color: #fff; }
.fc-action-btn.delete    { background: var(--fc-red-light);    color: var(--fc-red);   border-color: #EF9A9A; }
.fc-action-btn.delete:hover { background: var(--fc-red); color: #fff; }

/* ── Modal amélioré ──────────────────────────────────────────────────── */
.fc-modal .modal-header {
    background: linear-gradient(135deg, var(--fc-blue) 0%, var(--fc-blue-mid) 100%);
    color: #fff; border-bottom: none; border-radius: calc(var(--fc-radius-lg) - 1px) calc(var(--fc-radius-lg) - 1px) 0 0;
}
.fc-modal .modal-header .btn-close { filter: invert(1); }
.fc-modal .modal-content { border-radius: var(--fc-radius-lg); border: 1px solid var(--fc-border); box-shadow: var(--fc-shadow-md); }
.fc-modal .modal-footer { background: var(--fc-surface); border-top: 1px solid var(--fc-border); }
.fc-resume-box {
    background: var(--fc-blue-light); border: 1px solid #90CAF9;
    border-radius: var(--fc-radius); padding: 10px 14px; font-size: 13px; color: #0D47A1;
}
.fc-field-group { margin-bottom: 14px; }
.fc-field-group label { font-size: 12px; font-weight: 600; color: var(--fc-text-muted); margin-bottom: 4px; display: block; text-transform: uppercase; letter-spacing: .3px; }
.fc-field-group .form-control, .fc-field-group .form-select {
    border-color: var(--fc-border); border-radius: var(--fc-radius);
    font-size: 13px; color: var(--fc-text);
}
.fc-field-group .form-control:focus, .fc-field-group .form-select:focus {
    border-color: var(--fc-blue); box-shadow: 0 0 0 3px rgba(21,101,192,.1);
}
.fc-reste-input.zero     { background: var(--fc-green-light) !important; color: var(--fc-green) !important; font-weight: 700; }
.fc-reste-input.nonzero  { background: var(--fc-red-light) !important; color: var(--fc-red) !important; font-weight: 700; }

/* ── Impression  ─────────────────────────────────────────────────────── */
@media print {
    .fc-toolbar, .fc-bilan-section, .fc-actions,
    .fc-page-header .fc-btn, .no-print { display: none !important; }
    .fc-table-card { box-shadow: none; border: 1px solid #ccc; }
}
</style><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/partials/_factures_common.blade.php ENDPATH**/ ?>