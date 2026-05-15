@extends('layouts.admin')
@section('title', 'CMCU | Modifier Ordonnance')
@section('content')

<style>
.rx-ac-wrap { position: relative; }
.rx-ac-field { position: relative; }
.rx-ac-field::after {
    content: '\f002';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .9rem;
    pointer-events: none;
}
.rx-dropdown {
    position: absolute;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    z-index: 9999;
}
.rx-drop-item { padding: .7rem 1rem; cursor: pointer; transition: background .15s; display: block; }
.rx-drop-item:hover { background: #eff6ff; }
.rx-drop-item.active { background: #1D4ED8; color: white; }
.rx-drop-custom { background: #eff6ff; border: 1px dashed #06b6d4; border-radius: 8px; margin: 4px; }
.rx-drop-custom:hover { background: #dbeafe; }
.rx-drop-custom.active { background: #0891b2; border-color: transparent; color: white; }
@keyframes rowFadeIn {
    from { opacity: 0; transform: translateX(-10px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes shake {
    10%,90%  { transform: translateX(-2px); }
    20%,80%  { transform: translateX(4px); }
    30%,50%,70% { transform: translateX(-5px); }
    40%,60%  { transform: translateX(5px); }
}
.rx-input.is-invalid, .rx-textarea.is-invalid {
    border-color: #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239,68,68,.12) !important;
    animation: shake .4s cubic-bezier(.36,.07,.19,.97) both;
}
</style>

<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        @can('show', \App\Models\User::class)
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-xl tw-mx-auto">

            {{-- ── Back button ──────────────────────────────────────────── --}}
            <a href="{{ route('patients.show', $patient->id) }}"
               class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-card tw-border tw-border-slate-200 tw-transition-all tw-duration-150 tw-no-underline tw-mb-6">
                <i class="fas fa-arrow-left tw-text-xs"></i>Retour au dossier patient
            </a>

            {{-- ── Page Header banner (amber = edit) ───────────────────── --}}
            <div class="tw-bg-amber-600 tw-rounded-2xl tw-px-7 tw-py-6 tw-mb-6" style="box-shadow:0 8px 28px rgba(217,119,6,.28)">
                <div class="tw-flex tw-items-center tw-gap-4 tw-mb-3">
                    <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-border tw-border-white/25 tw-shrink-0">
                        <i class="fas fa-prescription tw-text-white tw-text-xl"></i>
                    </div>
                    <h1 class="tw-text-white tw-font-bold tw-text-xl tw-mb-0">Modifier l'Ordonnance Médicale</h1>
                </div>
                <div class="tw-flex tw-items-center tw-flex-wrap tw-gap-3">
                    <span class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-white/20 tw-border tw-border-white/30 tw-text-white tw-text-sm tw-font-semibold tw-px-3.5 tw-py-1.5 tw-rounded-full">
                        <i class="fas fa-user-injured tw-text-xs"></i>
                        {{ $patient->name }} {{ $patient->prenom }}
                    </span>
                    <span class="tw-text-white/70 tw-text-sm" id="rxDate"></span>
                </div>
            </div>

            {{-- ── Tip ──────────────────────────────────────────────────── --}}
            <div class="tw-flex tw-items-start tw-gap-4 tw-bg-sky-50 tw-border-l-4 tw-border-sky-400 tw-rounded-xl tw-px-5 tw-py-4 tw-mb-6" id="rxTip">
                <div class="tw-w-9 tw-h-9 tw-rounded-lg tw-bg-sky-100 tw-text-sky-600 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                    <i class="fas fa-lightbulb tw-text-sm"></i>
                </div>
                <p class="tw-text-sm tw-text-sky-800 tw-mb-0">
                    <strong>Conseil pratique :</strong> Commencez à taper le nom du médicament pour voir les suggestions automatiques.
                    Sélectionnez un produit existant ou ajoutez un médicament personnalisé non répertorié.
                </p>
            </div>

            {{-- ── Main Card ────────────────────────────────────────────── --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                {{-- Card header --}}
                <div class="tw-px-6 tw-py-4 tw-bg-slate-50 tw-border-b tw-border-slate-200 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-amber-500 tw-text-white tw-flex tw-items-center tw-justify-center" style="box-shadow:0 4px 10px rgba(217,119,6,.3)">
                            <i class="fas fa-list-ul tw-text-sm"></i>
                        </div>
                        <div>
                            <p class="tw-font-bold tw-text-slate-800 tw-mb-0 tw-text-sm">Liste des Médicaments</p>
                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Modifiez les lignes selon les besoins</p>
                        </div>
                    </div>
                    <div class="tw-inline-flex tw-items-center tw-gap-2 tw-bg-amber-500 tw-text-white tw-text-sm tw-font-bold tw-px-3.5 tw-py-1.5 tw-rounded-full tw-font-mono">
                        <i class="fas fa-pills tw-text-xs"></i>
                        <span id="badgeCount">0</span>
                    </div>
                </div>

                {{-- Form --}}
                <div class="tw-p-5">
                    <form method="POST" id="prescriptionForm" action="{{ route('ordonances.update', $ordonance->id) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                        @php
                            $parseSeparated = function(string $value): array {
                                $value = trim($value);
                                if ($value === '') return [''];
                                if (str_contains($value, ' | ')) return array_map('trim', explode(' | ', $value));
                                return [$value];
                            };
                            $medicaments  = $parseSeparated((string) ($ordonance->medicament  ?? ''));
                            $descriptions = $parseSeparated((string) ($ordonance->description ?? ''));
                            $quantites    = $parseSeparated((string) ($ordonance->quantite    ?? ''));
                            $initialCount = count($medicaments);
                        @endphp

                        <div class="tw-rounded-xl tw-overflow-x-auto tw-border tw-border-slate-200">
                            <table class="tw-w-full tw-text-sm" id="rxTable">
                                <thead>
                                    <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-200">
                                        <th class="tw-px-4 tw-py-3 tw-w-[4%] tw-text-xs tw-font-semibold tw-text-slate-400 tw-uppercase tw-tracking-wide">#</th>
                                        <th class="tw-px-4 tw-py-3 tw-w-[33%] tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">
                                            <i class="fas fa-capsules tw-text-primary-600 tw-mr-1"></i>Médicament
                                        </th>
                                        <th class="tw-px-4 tw-py-3 tw-w-[38%] tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">
                                            <i class="fas fa-notes-medical tw-text-emerald-500 tw-mr-1"></i>Posologie &amp; Instructions
                                        </th>
                                        <th class="tw-px-4 tw-py-3 tw-w-[15%] tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide">
                                            <i class="fas fa-hashtag tw-text-amber-500 tw-mr-1"></i>Quantité
                                        </th>
                                        <th class="tw-px-4 tw-py-3 tw-w-[10%] tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="rxRows" class="tw-divide-y tw-divide-slate-100">
                                    @foreach($medicaments as $index => $medicament)
                                    @php $seq = $index + 1; @endphp
                                    <tr id="row_{{ $seq }}" data-seq="{{ $seq }}" style="animation:rowFadeIn .35s ease both">
                                        <td class="tw-px-4 tw-py-3 tw-align-top tw-pt-4">
                                            <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 tw-rounded-full tw-bg-amber-500 tw-text-white tw-text-[11px] tw-font-bold tw-font-mono rx-row-num">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="tw-px-4 tw-py-3">
                                            <div class="rx-ac-wrap">
                                                <div class="rx-ac-field">
                                                    <input type="text"
                                                           id="med_{{ $seq }}"
                                                           name="medicament[]"
                                                           class="form-control rx-input med-input"
                                                           data-seq="{{ $seq }}"
                                                           placeholder="Ex : Doliprane 1000 mg"
                                                           autocomplete="off"
                                                           value="{{ trim($medicament) }}"
                                                           required />
                                                </div>
                                                <div class="rx-dropdown" id="drop_{{ $seq }}" style="display:none"></div>
                                            </div>
                                        </td>
                                        <td class="tw-px-4 tw-py-3">
                                            <textarea name="description[]"
                                                      class="form-control rx-textarea"
                                                      rows="3"
                                                      placeholder="Ex : 1 comprimé matin et soir pendant 7 jours"
                                                      required>{{ trim($descriptions[$index] ?? '') }}</textarea>
                                        </td>
                                        <td class="tw-px-4 tw-py-3">
                                            <input type="number"
                                                   name="quantite[]"
                                                   class="form-control rx-input tw-text-center tw-font-mono"
                                                   placeholder="14"
                                                   min="1"
                                                   style="max-width:100px"
                                                   value="{{ trim($quantites[$index] ?? '') }}"
                                                   required />
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center">
                                            <div class="tw-flex tw-justify-center">
                                                @if($index == 0)
                                                <button type="button"
                                                        class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-emerald-500 hover:tw-bg-emerald-600 tw-text-white tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 tw-border-0 tw-cursor-pointer"
                                                        id="btnAddRow" title="Ajouter un médicament">
                                                    <i class="fas fa-plus tw-text-xs"></i>
                                                </button>
                                                @else
                                                <button type="button"
                                                        class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 tw-border-0 tw-cursor-pointer"
                                                        onclick="removeRow({{ $seq }})" title="Supprimer">
                                                    <i class="fas fa-trash-alt tw-text-xs"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="tw-px-6 tw-py-4 tw-bg-slate-50 tw-border-t tw-border-slate-200 tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-4">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-500 tw-text-white tw-flex tw-items-center tw-justify-center" style="box-shadow:0 4px 12px rgba(217,119,6,.3)">
                            <i class="fas fa-pills tw-text-sm"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-uppercase tw-text-slate-400 tw-font-semibold tw-mb-0">Médicaments</p>
                            <p class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-0 tw-leading-none" id="footerCount">0</p>
                        </div>
                    </div>
                    <button type="submit" form="prescriptionForm"
                            class="tw-inline-flex tw-items-center tw-gap-2.5 tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-font-bold tw-text-base tw-px-7 tw-py-3 tw-rounded-xl tw-transition-all tw-duration-150 tw-border-0 tw-cursor-pointer"
                            style="box-shadow:0 8px 28px rgba(217,119,6,.28)"
                            id="submitBtn">
                        <i class="fas fa-file-prescription tw-text-sm"></i>
                        Mettre à jour l'ordonnance
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>
    @endcan

<script src="{{ asset('vendor/js/jquery-2.2.0.min.js') }}"></script>
<script>
$(document).ready(function () {
    let rowSeq       = {{ $initialCount }};
    let rowCount     = {{ $initialCount }};
    let produitsData = [];
    let currentFocus = -1;
    let debounceTimer;

    (function () {
        const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
        $('#rxDate').text(new Date().toLocaleDateString('fr-FR', opts));
    })();

    function updateCount () { $('#badgeCount, #footerCount').text(rowCount); }
    updateCount();

    function reindexRows () {
        $('#rxRows tr').each(function (i) { $(this).find('.rx-row-num').text(i + 1); });
    }

    function initAutocompleteForAllRows () {
        $('.med-input').each(function () { initAC($(this).data('seq')); });
    }

    $.ajax({
        url: '{{ route("produits.search-json") }}',
        method: 'GET',
        success: function (data) { produitsData = data; initAutocompleteForAllRows(); },
        error:   function (xhr, st, err) { console.error('Erreur produits:', err); }
    });

    function addRow () {
        if (rowCount >= 10) { showAlert('Maximum 10 médicaments par ordonnance.', 'warning'); return; }
        rowSeq++; rowCount++;
        const seq = rowSeq;
        const html = `
        <tr id="row_${seq}" data-seq="${seq}" style="animation:rowFadeIn .35s ease both">
            <td class="tw-px-4 tw-py-3 tw-align-top tw-pt-4">
                <span class="tw-inline-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 tw-rounded-full tw-bg-amber-500 tw-text-white tw-text-[11px] tw-font-bold tw-font-mono rx-row-num">${rowCount}</span>
            </td>
            <td class="tw-px-4 tw-py-3">
                <div class="rx-ac-wrap">
                    <div class="rx-ac-field">
                        <input type="text" id="med_${seq}" name="medicament[]" class="form-control rx-input med-input"
                               data-seq="${seq}" placeholder="Ex : Doliprane 1000 mg" autocomplete="off" required />
                    </div>
                    <div class="rx-dropdown" id="drop_${seq}" style="display:none"></div>
                </div>
            </td>
            <td class="tw-px-4 tw-py-3">
                <textarea name="description[]" class="form-control rx-textarea" rows="3"
                          placeholder="Ex : 1 comprimé matin et soir pendant 7 jours" required></textarea>
            </td>
            <td class="tw-px-4 tw-py-3">
                <input type="number" name="quantite[]" class="form-control rx-input tw-text-center tw-font-mono"
                       placeholder="14" min="1" style="max-width:100px" required />
            </td>
            <td class="tw-px-4 tw-py-3 tw-text-center">
                <div class="tw-flex tw-justify-center">
                    <button type="button"
                            class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 tw-border-0 tw-cursor-pointer"
                            onclick="removeRow(${seq})" title="Supprimer">
                        <i class="fas fa-trash-alt tw-text-xs"></i>
                    </button>
                </div>
            </td>
        </tr>`;
        $('#rxRows').append(html);
        updateCount(); initAC(seq);
        setTimeout(() => $(`#med_${seq}`).focus(), 80);
        $('html,body').animate({ scrollTop: $(`#row_${seq}`).offset().top - 160 }, 400);
    }

    window.removeRow = function (seq) {
        if (rowCount <= 1) { showAlert('Une ordonnance doit contenir au moins un médicament.', 'info'); return; }
        $(`#row_${seq}`).animate({ opacity: 0 }, 250, function () {
            $(this).remove(); rowCount--; reindexRows(); updateCount();
        });
        $(`#drop_${seq}`).hide().empty();
    };

    $(document).on('click', '#btnAddRow', addRow);

    function initAC (seq) {
        const $in   = $(`#med_${seq}`);
        const $drop = $(`#drop_${seq}`);
        $in.off('input').on('input', function () {
            const val = $(this).val().trim(); clearTimeout(debounceTimer);
            if (val.length < 2) { $drop.hide().empty(); return; }
            debounceTimer = setTimeout(() => showSuggestions(val, $in, $drop), 280);
        });
        $in.off('keydown').on('keydown', function (e) {
            const $items = $drop.find('.rx-drop-item');
            if (!$drop.is(':visible') || !$items.length) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); currentFocus = (currentFocus + 1) % $items.length; markActive($items, $drop); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); currentFocus = (currentFocus - 1 + $items.length) % $items.length; markActive($items, $drop); }
            else if (e.key === 'Enter' && currentFocus > -1) { e.preventDefault(); $items.eq(currentFocus).trigger('click'); }
            else if (e.key === 'Escape') { $drop.hide().empty(); currentFocus = -1; }
        });
        $in.off('focus').on('focus', function () { const v = $(this).val().trim(); if (v.length >= 2) showSuggestions(v, $in, $drop); });
        $in.off('blur').on('blur',   function () { setTimeout(() => { $drop.hide().empty(); currentFocus = -1; }, 200); });
    }

    function showSuggestions (term, $in, $drop) {
        currentFocus = -1; $drop.empty();
        const off = $in.offset(), h = $in.outerHeight();
        if (!$drop.parent().is('body')) $('body').append($drop);
        $drop.css({ position:'absolute', top: off.top + h + 6 + 'px', left: off.left + 'px', width: $in.outerWidth() + 'px', zIndex: 9999 });
        const termUp = term.toUpperCase();
        let hits = 0, exact = false;
        $.each(produitsData, function (_, p) {
            if (hits >= 12) return false;
            const des = p.designation || '';
            if (!des.toUpperCase().includes(termUp)) return;
            hits++; if (des.toUpperCase() === termUp) exact = true;
            const si = des.toUpperCase().indexOf(termUp);
            const $item = $(`<div class="rx-drop-item" data-val="${esc(des)}">
                <div class="tw-font-semibold tw-text-sm tw-mb-0.5"><i class="fas fa-capsules tw-mr-1 tw-text-primary-500"></i>${esc(des.slice(0,si))}<strong>${esc(des.slice(si,si+term.length))}</strong>${esc(des.slice(si+term.length))}</div>
                <div class="tw-text-xs tw-text-slate-400"><i class="fas fa-tag tw-mr-1"></i>${esc(p.categorie || 'Médicament')}</div>
            </div>`);
            $item.on('click', function () { $in.val($(this).data('val')); $drop.hide().empty(); currentFocus = -1; $in.closest('tr').find('textarea').focus(); });
            $drop.append($item);
        });
        if (!exact && term.length >= 2) {
            const $custom = $(`<div class="rx-drop-item rx-drop-custom" data-val="${esc(term)}">
                <div class="tw-font-semibold tw-text-sm tw-mb-0.5"><i class="fas fa-stethoscope tw-mr-1"></i>Ajouter : <strong>"${esc(term)}"</strong></div>
                <div class="tw-text-xs tw-text-slate-500"><i class="fas fa-user-md tw-mr-1"></i>Médicament personnalisé</div>
            </div>`);
            $custom.on('click', function () { $in.val($(this).data('val')); $drop.hide().empty(); currentFocus = -1; $in.closest('tr').find('textarea').focus(); });
            $drop.append($custom);
        }
        if (!hits) $drop.html(`<div class="tw-text-center tw-py-4 tw-text-slate-400 tw-text-sm"><i class="fas fa-search tw-text-2xl tw-opacity-40 tw-block tw-mb-2"></i>Aucun résultat pour « ${esc(term)} »</div>`);
        $drop.show();
    }

    function markActive ($items, $drop) {
        $items.removeClass('active');
        if (currentFocus < 0 || currentFocus >= $items.length) return;
        const $a = $items.eq(currentFocus).addClass('active');
        const top = $a.position().top, ih = $a.outerHeight(), ch = $drop.height(), st = $drop.scrollTop();
        if (top + ih > ch) $drop.scrollTop(st + top + ih - ch + 10);
        else if (top < 0) $drop.scrollTop(st + top - 10);
    }

    $(document).on('click', function (e) {
        if (!$(e.target).hasClass('med-input') && !$(e.target).closest('.rx-dropdown').length) {
            $('.rx-dropdown').hide().empty(); currentFocus = -1;
        }
    });
    $(window).on('scroll resize', function () {
        $('.rx-dropdown:visible').each(function () {
            const id = $(this).attr('id').replace('drop_', ''), $in = $(`#med_${id}`);
            if (!$in.length) return;
            const off = $in.offset();
            $(this).css({ top: off.top + $in.outerHeight() + 6 + 'px', left: off.left + 'px' });
        });
    });

    $('#prescriptionForm').on('submit', function (e) {
        let ok = true;
        $(this).find('[required]').each(function () {
            if (!$(this).val().trim()) { ok = false; $(this).addClass('is-invalid'); setTimeout(() => $(this).removeClass('is-invalid'), 3000); }
            else { $(this).removeClass('is-invalid'); }
        });
        if (!ok) {
            e.preventDefault();
            showAlert('Veuillez remplir tous les champs obligatoires avant de soumettre.', 'danger');
            $('html,body').animate({ scrollTop: $('.is-invalid:first').offset().top - 130 }, 500);
            return false;
        }
        $('#submitBtn').prop('disabled', true).html(`<span class="spinner-border spinner-border-sm me-2"></span>Mise à jour en cours…`);
    });
    $(document).on('input change', '.is-invalid', function () { if ($(this).val().trim()) $(this).removeClass('is-invalid'); });

    function esc (str) { const d = document.createElement('div'); d.textContent = String(str); return d.innerHTML; }
    function showAlert (msg, type) {
        const icons   = { warning:'fas fa-exclamation-triangle', danger:'fas fa-times-circle', info:'fas fa-info-circle', success:'fas fa-check-circle' };
        const bgClass = (type === 'success' || type === 'info')
            ? 'tw-bg-emerald-50 tw-border-emerald-200 tw-text-emerald-800'
            : 'tw-bg-red-50 tw-border-red-200 tw-text-red-700';
        const $a = $(`<div class="tw-flex tw-items-start tw-gap-3 ${bgClass} tw-border tw-rounded-xl tw-px-5 tw-py-4 tw-mb-4 tw-text-sm" role="alert">
            <i class="${icons[type] || icons.danger} tw-mt-0.5 tw-shrink-0"></i>
            <p class="tw-mb-0 tw-flex-1">${esc(msg)}</p>
            <button class="tw-text-current tw-opacity-50 hover:tw-opacity-80 tw-border-0 tw-bg-transparent tw-cursor-pointer tw-shrink-0 btn-close-alert"><i class="fas fa-times tw-text-xs"></i></button>
        </div>`);
        $a.find('.btn-close-alert').on('click', () => $a.fadeOut(200, () => $a.remove()));
        $('#rxTip').before($a);
        $('html,body').animate({ scrollTop: $a.offset().top - 90 }, 400);
        setTimeout(() => $a.fadeOut(400, () => $a.remove()), 5000);
    }
});
</script>
@stop