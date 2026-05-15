
<?php $__env->startSection('title', 'CMCU | Créer Ordonnance'); ?>
<?php $__env->startSection('content'); ?>


<style>
/* Minimal custom styles - only what Bootstrap doesn't cover */
:root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-dark: #1d4ed8;
    --success: #10b981;
    --danger: #ef4444;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-700: #374151;
    --gray-900: #111827;
}

body {
    background: #f0f4ff;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.rx-back {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: white;
    color: var(--primary);
    border: 2px solid rgba(37,99,235,.2);
    border-radius: 16px;
    padding: .6rem 1.25rem .6rem 1rem;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(0,0,0,.07);
    transition: all .25s ease;
    margin-bottom: 1.75rem;
}

.rx-back:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    box-shadow: 0 8px 28px rgba(37,99,235,.22);
    transform: translateX(-3px) translateY(-1px);
}

.rx-header {
    background: var(--primary);  /* solid color – gradient removed */
    border-radius: 28px;
    padding: 2.25rem 2.5rem;
    margin-bottom: 1.75rem;
    box-shadow: 0 8px 28px rgba(37,99,235,.22);
    color: white;
    position: relative;
    overflow: hidden;
}

/* Removed .rx-header::before – no more radial‑gradient overlays */

.rx-header-title-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.25);
}

.rx-header-meta-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.28);
    border-radius: 20px;
    padding: .375rem 1rem;
    backdrop-filter: blur(8px);
    font-weight: 600;
}

.rx-alert {
    border-radius: 16px;
    padding: 1.125rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: .875rem;
    box-shadow: 0 4px 16px rgba(0,0,0,.09);
    animation: alertSlide .35s ease both;
    position: relative;
    border: none;
}

@keyframes alertSlide {
    from { opacity:0; transform:translateY(-14px); }
    to   { opacity:1; transform:translateY(0); }
}

.rx-alert--success {
    background: #d1fae5;  /* solid color – gradient removed */
    color: #065f46;
    border-left: 4px solid var(--success);
}

.rx-alert--danger {
    background: #fee2e2;  /* solid color – gradient removed */
    color: #991b1b;
    border-left: 4px solid var(--danger);
}

.rx-tip {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    background: #eff6ff;  /* solid color – gradient removed */
    border-radius: 16px;
    padding: 1.125rem 1.5rem;
    margin-bottom: 1.75rem;
    border-left: 4px solid #06b6d4;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
}

.rx-card {
    background: white;
    border-radius: 28px;
    box-shadow: 0 10px 32px rgba(0,0,0,.11);
    overflow: hidden;
    transition: box-shadow .3s ease;
    border: 1px solid rgba(255,255,255,.8);
}

.rx-card:hover {
    box-shadow: 0 20px 48px rgba(0,0,0,.14);
}

.rx-card-header {
    background: var(--gray-50);  /* solid color – gradient removed */
    padding: 1.375rem 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.rx-card-header-icon {
    width: 40px;
    height: 40px;
    background: var(--primary);  /* solid color – gradient removed */
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(37,99,235,.25);
}

.rx-pills-badge {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    background: var(--primary);  /* solid color – gradient removed */
    color: white;
    border-radius: 20px;
    padding: .35rem .875rem;
    font-size: .85rem;
    font-weight: 700;
    box-shadow: 0 3px 10px rgba(37,99,235,.2);
    font-family: 'Fira Mono', 'Courier New', monospace;
}

.rx-table-wrap {
    border-radius: 16px;
    overflow-x: auto;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 6px rgba(0,0,0,.07);
    background: white;
}

.rx-table thead {
    background: var(--gray-50);  /* solid color – gradient removed */
}

.rx-table thead th {
    padding: 1rem 1.25rem;
    font-size: .8rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .8px;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}

.rx-table tbody tr:not(:last-child) td {
    border-bottom: 1px solid #f3f4f6;
}

.rx-table tbody tr:hover {
    background: #f5f8ff;
}

.rx-row-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    font-size: .7rem;
    font-weight: 700;
    margin-right: .5rem;
    flex-shrink: 0;
    font-family: 'Fira Mono', 'Courier New', monospace;
}

.rx-input, .rx-select, .rx-textarea {
    width: 100%;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: .8rem 1rem;
    font-size: .975rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--gray-800);
    background: white;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
}

.rx-input:focus, .rx-select:focus, .rx-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(37,99,235,.18), 0 2px 6px rgba(0,0,0,.07);
    transform: translateY(-1px);
}

.rx-input--qty {
    font-family: 'Fira Mono', 'Courier New', monospace;
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    max-width: 120px;
}

.is-invalid {
    border-color: var(--danger) !important;
    box-shadow: 0 0 0 4px rgba(239,68,68,.12) !important;
    animation: shake .4s cubic-bezier(.36,.07,.19,.97) both;
}

@keyframes shake {
    10%,90%  { transform: translateX(-2px); }
    20%,80%  { transform: translateX(4px); }
    30%,50%,70% { transform: translateX(-5px); }
    40%,60%  { transform: translateX(5px); }
}

.rx-ac-wrap { position: relative; }
.rx-ac-field { position: relative; }
.rx-ac-field .rx-input { padding-right: 2.75rem; }

.rx-ac-field::after {
    content: '\f002';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .95rem;
    pointer-events: none;
}

.rx-dropdown {
    position: absolute;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
    max-height: 300px;
    overflow-y: auto;
}

@keyframes dropFade {
    from { opacity:0; transform:translateY(-8px) scale(.98); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}

.rx-drop-item {
    padding: .75rem 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: background .15s;
    display: block;
    margin-bottom: 2px;
}

.rx-drop-item:hover {
    background: #eff6ff;
    transform: translateX(2px);
}

.rx-drop-item.active {
    background: var(--primary);  /* solid color – gradient removed */
    color: white;
    box-shadow: 0 4px 12px rgba(37,99,235,.25);
}

.rx-drop-custom {
    background: #eff6ff;  /* solid color – gradient removed */
    border: 1px dashed #06b6d4;
}

.rx-drop-custom:hover {
    background: #dbeafe;  /* solid color – gradient removed */
}

.rx-drop-custom.active {
    background: #0891b2;  /* solid color – gradient removed */
    border-color: transparent;
}

.rx-action-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}

.rx-btn-circle {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all .22s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
    flex-shrink: 0;
}

.rx-btn-add {
    background: var(--success);  /* solid color – gradient removed */
    color: white;
    box-shadow: 0 3px 10px rgba(16,185,129,.25);
}

.rx-btn-add:hover {
    transform: scale(1.1) translateY(-1px);
    box-shadow: 0 6px 18px rgba(16,185,129,.35);
}

.rx-btn-remove {
    background: var(--danger);  /* solid color – gradient removed */
    color: white;
    box-shadow: 0 3px 10px rgba(239,68,68,.2);
}

.rx-btn-remove:hover {
    transform: scale(1.1) translateY(-1px);
    box-shadow: 0 6px 18px rgba(239,68,68,.3);
}

.rx-footer {
    background: var(--gray-50);  /* solid color – gradient removed */
    padding: 1.5rem 2rem;
    border-top: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.rx-footer-stat-icon {
    width: 44px;
    height: 44px;
    background: var(--primary);  /* solid color – gradient removed */
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(37,99,235,.22);
}

.rx-submit-btn {
    display: inline-flex;
    align-items: center;
    gap: .75rem;
    background: var(--primary);  /* solid color – gradient removed */
    color: white;
    border: none;
    border-radius: 16px;
    padding: .95rem 2.25rem;
    font-size: 1.05rem;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    box-shadow: 0 8px 28px rgba(37,99,235,.22);
    transition: all .25s ease;
    letter-spacing: -.1px;
    position: relative;
    overflow: hidden;
}

.rx-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(37,99,235,.4);
}

.rx-submit-btn:active {
    transform: translateY(0);
}

@keyframes rowFadeIn {
    from { opacity:0; transform:translateX(-10px); }
    to   { opacity:1; transform:translateX(0); }
}
</style>

<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
    <div class="container-fluid py-4">
        
        <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="rx-back">
            <i class="fas fa-arrow-left"></i>
            Retour au dossier patient
        </a>

        
        <div class="rx-header">
            <div class="position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="rx-header-title-icon me-3">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <h1 class="h3 mb-0 fw-bold">Nouvelle Ordonnance Médicale</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="rx-header-meta-badge">
                        <i class="fas fa-user-injured"></i>
                        <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>

                    </div>
                    <span class="small opacity-80" id="rxDate"></span>
                </div>
            </div>
        </div>

        
        <div class="rx-tip">
            <div class="flex-shrink-0" style="width:38px;height:38px;background:rgba(6,182,212,.15);color:#0891b2;border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div class="small">
                <strong>Conseil pratique :</strong>
                Commencez à taper le nom du médicament pour voir les suggestions automatiques.
                Sélectionnez un produit existant ou ajoutez un médicament personnalisé non répertorié.
            </div>
        </div>

        
        <div class="rx-card">
            
            <div class="rx-card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rx-card-header-icon me-3">
                            <i class="fas fa-list-ul"></i>
                        </div>
                        <div>
                            <h2 class="h5 mb-0 fw-bold">Liste des Médicaments</h2>
                            <small class="text-muted">Remplissez chaque ligne avec précision</small>
                        </div>
                    </div>
                    <div class="rx-pills-badge">
                        <i class="fas fa-pills"></i>
                        <span id="badgeCount">0</span>
                    </div>
                </div>
            </div>

            
            <div class="p-4">
                <form method="POST" id="prescriptionForm" action="<?php echo e(route('ordonances.store')); ?>" novalidate>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">

                    
                    <div class="rx-table-wrap">
                        <table class="table mb-0" id="rxTable">
                            <thead>
                                <tr>
                                    <th style="width:4%">#</th>
                                    <th style="width:33%">
                                        <i class="fas fa-capsules" style="color:var(--primary);margin-right:.4rem;"></i>
                                        Médicament
                                    </th>
                                    <th style="width:38%">
                                        <i class="fas fa-notes-medical" style="color:#10b981;margin-right:.4rem;"></i>
                                        Posologie &amp; Instructions
                                    </th>
                                    <th style="width:15%">
                                        <i class="fas fa-hashtag" style="color:#f59e0b;margin-right:.4rem;"></i>
                                        Quantité
                                    </th>
                                    <th style="width:10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="rxRows">
                                
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            
            <div class="rx-footer">
                <div class="d-flex align-items-center gap-3">
                    <div class="rx-footer-stat-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div>
                        <div class="small text-uppercase text-muted fw-semibold">Médicaments</div>
                        <div class="h5 mb-0 fw-bold" id="footerCount">0</div>
                    </div>
                </div>
                <button type="submit" form="prescriptionForm" class="rx-submit-btn" id="submitBtn">
                    <i class="fas fa-file-medical"></i>
                    Générer l'ordonnance
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="<?php echo e(asset('vendor/js/jquery-2.2.0.min.js')); ?>"></script>
<script>
waitForjQuery(function() {
$(function () {
    /* State */
    let rowSeq       = 0;
    let rowCount     = 0;
    let produitsData = [];
    let currentFocus = -1;
    let debounceTimer;

    /* Date display */
    (function () {
        const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
        $('#rxDate').text(new Date().toLocaleDateString('fr-FR', opts));
    })();

    /* Load products then seed first row */
    $.ajax({
        url: '<?php echo e(route("produits.search-json")); ?>',
        method: 'GET',
        success: function (data) {
            produitsData = data;
            console.info('✓ ' + produitsData.length + ' produits chargés');
        },
        error: function (xhr, st, err) {
            console.error('Erreur chargement produits:', err);
        },
        complete: function () {
            addRow();
        }
    });

    /* addRow */
    function addRow () {
        if (rowCount >= 10) {
            showAlert('Maximum 10 médicaments par ordonnance.', 'warning');
            return;
        }
        rowSeq++;
        rowCount++;
        const seq = rowSeq;
        const isFirst = rowCount === 1;
        const actionBtn = isFirst
            ? `<button type="button" class="rx-btn-circle rx-btn-add"
                id="btnAddRow" title="Ajouter un médicament">
                <i class="fas fa-plus"></i>
            </button>`
            : `<button type="button" class="rx-btn-circle rx-btn-remove"
                onclick="removeRow(${seq})" title="Supprimer">
                <i class="fas fa-trash-alt"></i>
            </button>`;
        const html = `
        <tr id="row_${seq}" data-seq="${seq}" style="--delay:${(rowCount-1)*50}ms;animation:rowFadeIn .35s ease both">
            <td style="padding-top:1.1rem">
                <span class="rx-row-num">${rowCount}</span>
            </td>
            <td>
                <div class="rx-ac-wrap">
                    <div class="rx-ac-field">
                        <input type="text"
                            id="med_${seq}"
                            name="medicament[]"
                            class="rx-input med-input"
                            data-seq="${seq}"
                            placeholder="Ex : Doliprane 1000 mg"
                            autocomplete="off"
                            required />
                    </div>
                    <div class="rx-dropdown" id="drop_${seq}" style="display:none"></div>
                </div>
            </td>
            <td>
                <textarea name="description[]"
                    class="rx-textarea"
                    rows="3"
                    placeholder="Ex : 1 comprimé matin et soir pendant 7 jours"
                    required></textarea>
            </td>
            <td>
                <input type="number"
                    name="quantite[]"
                    class="rx-input rx-input--qty"
                    placeholder="14"
                    min="1"
                    required />
            </td>
            <td>
                <div class="rx-action-group">${actionBtn}</div>
            </td>
        </tr>`;
        $('#rxRows').append(html);
        updateCount();
        initAC(seq);
        setTimeout(() => $(`#med_${seq}`).focus(), 80);
        if (rowCount > 1) {
            $('html,body').animate({ scrollTop: $(`#row_${seq}`).offset().top - 160 }, 400);
        }
    }

    /* removeRow */
    window.removeRow = function (seq) {
        if (rowCount <= 1) {
            showAlert('Une ordonnance doit contenir au moins un médicament.', 'info');
            return;
        }
        const $row = $(`#row_${seq}`);
        $row.css({ opacity: 1 })
            .animate({ opacity: 0 }, 250, function () {
                $(this).remove();
                rowCount--;
                reindexRows();
                updateCount();
            });
        $(`#drop_${seq}`).hide().empty();
    };

    /* Delegate "Add" button */
    $(document).on('click', '#btnAddRow', addRow);

    /* updateCount / reindexRows */
    function updateCount () {
        const n = rowCount;
        $('#badgeCount').text(n);
        $('#footerCount').text(n);
    }
    function reindexRows () {
        $('#rxRows tr').each(function (i) {
            $(this).find('.rx-row-num').text(i + 1);
        });
    }

    /* Autocomplete */
    function initAC (seq) {
        const $in   = $(`#med_${seq}`);
        const $drop = $(`#drop_${seq}`);
        $in.on('input', function () {
            const val = $(this).val().trim();
            clearTimeout(debounceTimer);
            if (val.length < 2) { $drop.hide().empty(); return; }
            debounceTimer = setTimeout(() => showSuggestions(val, $in, $drop), 280);
        });
        $in.on('keydown', function (e) {
            const $items = $drop.find('.rx-drop-item');
            if (!$drop.is(':visible') || !$items.length) return;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentFocus = (currentFocus + 1) % $items.length;
                markActive($items, $drop);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentFocus = (currentFocus - 1 + $items.length) % $items.length;
                markActive($items, $drop);
            } else if (e.key === 'Enter' && currentFocus > -1) {
                e.preventDefault();
                $items.eq(currentFocus).trigger('click');
            } else if (e.key === 'Escape') {
                $drop.hide().empty();
                currentFocus = -1;
            }
        });
        $in.on('focus', function () {
            const val = $(this).val().trim();
            if (val.length >= 2) showSuggestions(val, $in, $drop);
        });
        $in.on('blur', function () {
            setTimeout(() => { $drop.hide().empty(); currentFocus = -1; }, 200);
        });
    }

    function showSuggestions (term, $in, $drop) {
        currentFocus = -1;
        $drop.empty();
        const off = $in.offset();
        const h   = $in.outerHeight();
         // Append dropdown to body instead of table cell
        if (!$drop.parent().is('body')) {
            $('body').append($drop);
        }

        $drop.css({
            position: 'absolute',
            top: off.top + h + 6 + 'px',
            left: off.left + 'px',
            width: $in.outerWidth() + 'px',
            zIndex: 9999
        });
        
        const termUp = term.toUpperCase();
        let hits = 0;
        let exact = false;
        $.each(produitsData, function (_, p) {
            if (hits >= 12) return false;
            const des = p.designation || '';
            if (!des.toUpperCase().includes(termUp)) return;
            hits++;
            if (des.toUpperCase() === termUp) exact = true;
            const si = des.toUpperCase().indexOf(termUp);
            const pre  = esc(des.slice(0, si));
            const mid  = esc(des.slice(si, si + term.length));
            const post = esc(des.slice(si + term.length));
            const $item = $(`
            <div class="rx-drop-item" data-val="${esc(des)}">
                <div class="fw-semibold mb-1">
                    <i class="fas fa-capsules"></i>
                    ${pre}<strong>${mid}</strong>${post}
                </div>
                <div class="small text-muted">
                    <i class="fas fa-tag"></i>
                    ${esc(p.categorie || 'Médicament')}
                </div>
            </div>`);
            $item.on('click', function () {
                $in.val($(this).data('val'));
                $drop.hide().empty();
                currentFocus = -1;
                $in.closest('tr').find('textarea').focus();
            });
            $drop.append($item);
        });
        if (!exact && term.length >= 2) {
            const $custom = $(`
            <div class="rx-drop-item rx-drop-custom" data-val="${esc(term)}">
                <div class="fw-semibold mb-1">
                    <i class="fas fa-stethoscope"></i>
                    Ajouter : <strong>"${esc(term)}"</strong>
                </div>
                <div class="small">
                    <i class="fas fa-user-md"></i>
                    Médicament personnalisé (non répertorié)
                </div>
            </div>`);
            $custom.on('click', function () {
                $in.val($(this).data('val'));
                $drop.hide().empty();
                currentFocus = -1;
                $in.closest('tr').find('textarea').focus();
            });
            $drop.append($custom);
        }
        if (!hits && term.length >= 2) {
            $drop.html(`
            <div class="text-center py-3 text-muted">
                <i class="fas fa-search fa-2x opacity-50 mb-2 d-block"></i>
                Aucun résultat pour « ${esc(term)} »
            </div>`);
        }
        $drop.show();
    }

    function markActive ($items, $drop) {
        $items.removeClass('active');
        if (currentFocus < 0 || currentFocus >= $items.length) return;
        const $a = $items.eq(currentFocus).addClass('active');
        const top = $a.position().top;
        const ih  = $a.outerHeight();
        const ch  = $drop.height();
        const st  = $drop.scrollTop();
        if (top + ih > ch) $drop.scrollTop(st + top + ih - ch + 10);
        else if (top < 0) $drop.scrollTop(st + top - 10);
    }

    $(document).on('click', function (e) {
        if (!$(e.target).hasClass('med-input') && !$(e.target).closest('.rx-dropdown').length) {
            $('.rx-dropdown').hide().empty();
            currentFocus = -1;
        }
    });

    $(window).on('scroll resize', function () {
        $('.rx-dropdown:visible').each(function () {
            const id   = $(this).attr('id').replace('drop_', '');
            const $in  = $(`#med_${id}`);
            if (!$in.length) return;
            const off = $in.offset();
            $(this).css({ top: off.top + $in.outerHeight() + 6 + 'px', left: off.left + 'px' });
        });
    });

    /* Form submit validation */
    $('#prescriptionForm').on('submit', function (e) {
        let ok = true;
        $(this).find('[required]').each(function () {
            if (!$(this).val().trim()) {
                ok = false;
                $(this).addClass('is-invalid');
                setTimeout(() => $(this).removeClass('is-invalid'), 3000);
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        if (!ok) {
            e.preventDefault();
            showAlert('Veuillez remplir tous les champs obligatoires avant de soumettre.', 'danger');
            $('html,body').animate({ scrollTop: $('.is-invalid:first').offset().top - 130 }, 500);
            return false;
        }
        $('#submitBtn').prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Génération en cours…
        `);
    });

    $(document).on('input change', '.is-invalid', function () {
        if ($(this).val().trim()) $(this).removeClass('is-invalid');
    });

    /* Helpers */
    function esc (str) {
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    }
    function showAlert (msg, type) {
        const icons = { warning:'fas fa-exclamation-triangle', danger:'fas fa-times-circle',
                        info:'fas fa-info-circle', success:'fas fa-check-circle' };
        const classes = { warning:'rx-alert--danger', danger:'rx-alert--danger',
                         info:'rx-alert--success', success:'rx-alert--success' };
        const $a = $(`
        <div class="rx-alert ${classes[type] || 'rx-alert--danger'}" role="alert">
            <div class="flex-shrink-0" style="width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="${icons[type] || icons.danger}"></i>
            </div>
            <div class="flex-grow-1">${esc(msg)}</div>
            <button class="btn-close" aria-label="Fermer"></button>
        </div>`);
        $a.find('.btn-close').on('click', () => $a.fadeOut(200, () => $a.remove()));
        $('.rx-tip').before($a);
        $('html,body').animate({ scrollTop: $a.offset().top - 90 }, 400);
        setTimeout(() => $a.fadeOut(400, () => $a.remove()), 5000);
    }
});
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/prescriptions/ordonance_create.blade.php ENDPATH**/ ?>