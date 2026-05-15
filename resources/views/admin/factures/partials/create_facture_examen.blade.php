{{--
    ============================================================
    FICHIER : resources/views/admin/factures/partials/create_facture_examen.blade.php

    CAUSE DU PROBLÈME :
      Le projet utilise Tailwind avec le préfixe "tw-".
      Les classes non-préfixées (fixed, inset-0, z-50, flex, etc.)
      ne sont PAS générées dans le CSS compilé → le navigateur les ignore
      → le modal se positionne en "static" dans le flux de la page
      → il pousse tout le contenu vers le bas (grand vide).

    SOLUTION :
      Toute la mise en page du modal est en CSS inline (style="").
      Le CSS inline ne dépend d'aucun build Tailwind.
      Seuls les styles visuels des éléments internes (couleurs, bordures,
      padding) utilisent encore des classes tw- compatibles avec ton layout.
    ============================================================
--}}

{{-- ══════════════════════════════════════════════════════════════════
     MODAL : Nouvelle Facture d'Examen
     ══════════════════════════════════════════════════════════════════ --}}

{{--
    STRUCTURE du modal :
      #modalNouvelleFactureExamen   → conteneur fixed plein écran (display:none par défaut)
        #fe-modal-overlay           → fond semi-transparent cliquable
        #fe-modal-dialog            → boîte de dialogue centrée
          en-tête vert
          corps scrollable (formulaire)
          pied de modal (boutons)
--}}
<div id="modalNouvelleFactureExamen"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            z-index:9999; overflow-y:auto;"
     aria-modal="true"
     role="dialog">

    {{-- Overlay cliquable --}}
    <div id="fe-modal-overlay"
         style="position:fixed; top:0; left:0; width:100%; height:100%;
                background:rgba(0,0,0,0.5); z-index:1;"></div>

    {{-- Centrage vertical + horizontal --}}
    <div style="position:relative; z-index:2; display:flex; align-items:center;
                justify-content:center; min-height:100%; padding:16px; box-sizing:border-box;">

        {{-- Boîte de dialogue --}}
        <div id="fe-modal-dialog"
             style="background:#fff; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.3);
                    width:100%; max-width:860px; max-height:90vh;
                    display:flex; flex-direction:column; overflow:hidden;">

            {{-- ── En-tête ─────────────────────────────────────────────────── --}}
            <div style="background:#059669; color:#fff; padding:16px 24px;
                        display:flex; align-items:center; justify-content:space-between;
                        border-radius:12px 12px 0 0; flex-shrink:0;">
                <div style="display:flex; align-items:center; gap:10px; font-size:16px; font-weight:600;">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.78 0-2.674-2.155-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    Nouvelle Facture d'Examen
                </div>
                <button type="button"
                        onclick="fermerModalFactureExamen()"
                        style="background:transparent; border:none; cursor:pointer; color:rgba(255,255,255,0.8);
                               padding:4px; border-radius:6px; display:flex; align-items:center;"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                        onmouseout="this.style.background='transparent'">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ── Corps scrollable ────────────────────────────────────────── --}}
            <div style="overflow-y:auto; flex:1; padding:24px;">
                <form id="formNouvelleFactureExamen"
                      method="POST"
                      action="{{ route('facturation.examens.store_direct') }}">
                    @csrf

                    {{-- ── ÉTAPE 1 : Patient ──────────────────────────────── --}}
                    <div style="border:1px solid #e5e7eb; border-radius:8px; margin-bottom:16px; background:#fff;">
                        <div style="background:#f9fafb; border-bottom:1px solid #e5e7eb;
                                    padding:12px 16px; border-radius:8px 8px 0 0;
                                    display:flex; align-items:center; gap:8px;">
                            <svg style="width:16px;height:16px;color:#6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span style="font-weight:600; font-size:13px; color:#374151;">1. Sélectionner le patient</span>
                        </div>
                        <div style="padding:16px;">
                            <div style="display:grid; grid-template-columns:2fr 1fr; gap:16px;">

                                <div>
                                    <label for="fe-patient-select"
                                           style="display:block; font-size:13px; font-weight:600;
                                                  color:#374151; margin-bottom:4px;">
                                        Patient <span style="color:#ef4444;">*</span>
                                    </label>
                                    {{-- Select2 AJAX — chargé via searchPatientsExamen() --}}
                                    <select id="fe-patient-select"
                                            name="patient_id"
                                            required
                                            style="width:100%;">
                                        <option value="">-- Rechercher un patient --</option>
                                    </select>
                                    <p style="font-size:11px; color:#9ca3af; margin-top:4px;">
                                        Tapez le nom, prénom ou numéro de dossier
                                    </p>
                                </div>

                                <div>
                                    <label style="display:block; font-size:13px; font-weight:500;
                                                  color:#6b7280; margin-bottom:4px;">N° Dossier</label>
                                    <input type="text"
                                           id="fe-numero-dossier"
                                           readonly
                                           placeholder="Auto-rempli"
                                           style="width:100%; border:1px solid #e5e7eb; border-radius:8px;
                                                  padding:8px 12px; font-size:13px; color:#6b7280;
                                                  background:#f9fafb; box-sizing:border-box;">
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ── ÉTAPE 2 : Prescriptions (masquée par défaut) ──── --}}
                    <div id="fe-prescriptions-card"
                         style="display:none; border:1px solid #e5e7eb; border-radius:8px;
                                margin-bottom:16px; background:#fff;">
                        <div style="background:#f9fafb; border-bottom:1px solid #e5e7eb;
                                    padding:12px 16px; border-radius:8px 8px 0 0;
                                    display:flex; align-items:center; gap:8px;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span style="font-weight:600; font-size:13px; color:#374151;">
                                2. Prescriptions existantes
                                <span style="font-weight:400; color:#9ca3af;">(optionnel — pré-remplit la description)</span>
                            </span>
                        </div>
                        <div style="padding:16px;">
                            <label for="fe-prescription-select"
                                   style="display:block; font-size:13px; font-weight:500;
                                          color:#374151; margin-bottom:4px;">
                                Sélectionner une prescription
                            </label>
                            <select id="fe-prescription-select"
                                    name="prescription_id"
                                    style="width:100%; border:1px solid #d1d5db; border-radius:8px;
                                           padding:8px 12px; font-size:13px; box-sizing:border-box;">
                                <option value="">-- Saisir manuellement (sans prescription) --</option>
                            </select>
                        </div>
                    </div>

                    {{-- ── ÉTAPE 3 : Détails facture ──────────────────────── --}}
                    <div style="border:1px solid #e5e7eb; border-radius:8px; margin-bottom:16px; background:#fff;">
                        <div style="background:#f9fafb; border-bottom:1px solid #e5e7eb;
                                    padding:12px 16px; border-radius:8px 8px 0 0;
                                    display:flex; align-items:center; gap:8px;">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span style="font-weight:600; font-size:13px; color:#374151;">3. Détails de la facture</span>
                        </div>
                        <div style="padding:16px;">
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

                                {{-- Description --}}
                                <div style="grid-column:span 2;">
                                    <label for="fe-libelle"
                                           style="display:block; font-size:13px; font-weight:600;
                                                  color:#374151; margin-bottom:4px;">
                                        Description des examens <span style="color:#ef4444;">*</span>
                                    </label>
                                    <textarea id="fe-libelle"
                                              name="libelle"
                                              rows="3"
                                              required
                                              placeholder="Ex: NFS, Glycémie, Créatinine / Échographie rénale..."
                                              style="width:100%; border:1px solid #d1d5db; border-radius:8px;
                                                     padding:8px 12px; font-size:13px; resize:vertical;
                                                     box-sizing:border-box; font-family:inherit;"></textarea>
                                    <p style="font-size:11px; color:#9ca3af; margin-top:4px;">
                                        Si vous avez sélectionné une prescription, ce champ est pré-rempli automatiquement.
                                    </p>
                                </div>

                                {{-- Médecin --}}
                                <div>
                                    <label for="fe-medecin"
                                           style="display:block; font-size:13px; font-weight:600;
                                                  color:#374151; margin-bottom:4px;">
                                        Médecin prescripteur
                                    </label>
                                    <input type="text"
                                           id="fe-medecin"
                                           name="medecin_r"
                                           placeholder="Nom du médecin prescripteur"
                                           style="width:100%; border:1px solid #d1d5db; border-radius:8px;
                                                  padding:8px 12px; font-size:13px; box-sizing:border-box;">
                                </div>

                                {{-- Mode paiement --}}
                                <div>
                                    <label style="display:block; font-size:13px; font-weight:600;
                                                  color:#374151; margin-bottom:4px;">
                                        Mode de paiement <span style="color:#ef4444;">*</span>
                                    </label>
                                    <select name="mode_paiement"
                                            required
                                            style="width:100%; border:1px solid #d1d5db; border-radius:8px;
                                                   padding:8px 12px; font-size:13px; box-sizing:border-box;">
                                        <option value="espèce">Espèce</option>
                                        <option value="orange money">Orange Money</option>
                                        <option value="mtn mobile money">MTN Mobile Money</option>
                                        <option value="chèque">Chèque</option>
                                        <option value="virement">Virement</option>
                                        <option value="bon de prise en charge">Bon de prise en charge</option>
                                    </select>
                                </div>

                                {{-- Montant --}}
                                <div>
                                    <label for="fe-montant"
                                           style="display:block; font-size:13px; font-weight:600;
                                                  color:#374151; margin-bottom:4px;">
                                        Montant total (FCFA) <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input type="number"
                                           id="fe-montant"
                                           name="montant"
                                           min="0"
                                           step="100"
                                           required
                                           placeholder="0"
                                           style="width:100%; border:1px solid #d1d5db; border-radius:8px;
                                                  padding:8px 12px; font-size:13px; box-sizing:border-box;">
                                </div>

                                {{-- Avance --}}
                                <div>
                                    <label for="fe-avance"
                                           style="display:block; font-size:13px; font-weight:600;
                                                  color:#374151; margin-bottom:4px;">
                                        Avance reçue (FCFA)
                                    </label>
                                    <input type="number"
                                           id="fe-avance"
                                           name="avance"
                                           min="0"
                                           step="100"
                                           value="0"
                                           placeholder="0"
                                           style="width:100%; border:1px solid #d1d5db; border-radius:8px;
                                                  padding:8px 12px; font-size:13px; box-sizing:border-box;">
                                </div>

                                {{-- Reste calculé --}}
                                <div style="grid-column:span 2;">
                                    <label style="display:block; font-size:13px; font-weight:500;
                                                  color:#6b7280; margin-bottom:4px;">
                                        Reste à payer (calculé automatiquement)
                                    </label>
                                    <input type="text"
                                           id="fe-reste-display"
                                           readonly
                                           value="0 FCFA"
                                           style="width:100%; border:1px solid #e5e7eb; border-radius:8px;
                                                  padding:8px 12px; font-size:13px; font-weight:700;
                                                  color:#059669; background:#f0fdf4; box-sizing:border-box;">
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Résumé (masqué par défaut) --}}
                    <div id="fe-resume"
                         style="display:none; background:#eff6ff; border:1px solid #bfdbfe;
                                border-radius:8px; padding:12px 16px; font-size:13px; color:#1d4ed8;">
                        <span id="fe-resume-text"></span>
                    </div>

                </form>
            </div>{{-- fin corps scrollable --}}

            {{-- ── Pied de modal ───────────────────────────────────────────── --}}
            <div style="border-top:1px solid #e5e7eb; background:#f9fafb; padding:16px 24px;
                        display:flex; justify-content:flex-end; gap:12px;
                        border-radius:0 0 12px 12px; flex-shrink:0;">

                <button type="button"
                        onclick="fermerModalFactureExamen()"
                        style="display:inline-flex; align-items:center; gap:8px;
                               border:1px solid #d1d5db; background:#fff; color:#374151;
                               padding:8px 16px; border-radius:8px; font-size:13px;
                               font-weight:500; cursor:pointer;"
                        onmouseover="this.style.background='#f9fafb'"
                        onmouseout="this.style.background='#fff'">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuler
                </button>

                <button type="submit"
                        form="formNouvelleFactureExamen"
                        id="fe-submit-btn"
                        style="display:inline-flex; align-items:center; gap:8px;
                               background:#059669; color:#fff; border:none;
                               padding:8px 20px; border-radius:8px; font-size:13px;
                               font-weight:600; cursor:pointer;"
                        onmouseover="this.style.background='#047857'"
                        onmouseout="this.style.background='#059669'">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Créer la facture examen
                </button>

            </div>

        </div>{{-- fin boîte de dialogue --}}
    </div>{{-- fin centrage --}}
</div>{{-- fin #modalNouvelleFactureExamen --}}

@push('scripts')
<script>
(function () {
    // ────────────────────────────────────────────────────────────────────────────
    // Helpers show/hide — style.display uniquement, zéro dépendance Tailwind
    // ────────────────────────────────────────────────────────────────────────────

    function afficher(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = '';
    }

    function cacher(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }

    // ────────────────────────────────────────────────────────────────────────────
    // Ouverture du modal
    // Exposé sur window.* → appelable depuis onclick="ouvrirModalFactureExamen()"
    // ────────────────────────────────────────────────────────────────────────────

    // Flag : Select2 ne doit être initialisé qu'une seule fois
    var select2Initialise = false;

    window.ouvrirModalFactureExamen = function () {
        var modal = document.getElementById('modalNouvelleFactureExamen');
        if (!modal) {
            console.error('[Modal Examen] Element #modalNouvelleFactureExamen introuvable dans le DOM.');
            return;
        }
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';

        // ✅ CORRECTION Bug 1 : Select2 initialisé ICI après display:block
        // POURQUOI : au DOMContentLoaded le modal est display:none → Select2
        // calcule un conteneur 0×0 → dropdown hors-écran → rien ne s'affiche.
        // Après display:block, le modal a ses vraies dimensions → Select2
        // positionne correctement le dropdown à l'intérieur.
        if (!select2Initialise && typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            initialiserSelect2();
            select2Initialise = true;
        }
    };

    // ────────────────────────────────────────────────────────────────────────────
    // Fermeture du modal
    // ────────────────────────────────────────────────────────────────────────────

    window.fermerModalFactureExamen = function () {
        var modal = document.getElementById('modalNouvelleFactureExamen');
        if (!modal) return;

        modal.style.display = 'none';
        document.body.style.overflow = ''; // restitue le scroll de la page

        // Réinitialiser le formulaire
        var form = document.getElementById('formNouvelleFactureExamen');
        if (form) form.reset();

        var resteDisplay = document.getElementById('fe-reste-display');
        if (resteDisplay) resteDisplay.value = '0 FCFA';

        cacher('fe-resume');
        cacher('fe-prescriptions-card');

        var nd = document.getElementById('fe-numero-dossier');
        if (nd) nd.value = '';

        // Réinitialiser Select2 si disponible
        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            try { $('#fe-patient-select').val(null).trigger('change'); } catch (e) {}
        }
    };

    // ────────────────────────────────────────────────────────────────────────────
    // Fermer en cliquant sur l'overlay
    // ────────────────────────────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', function () {
        var overlay = document.getElementById('fe-modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', window.fermerModalFactureExamen);
        }
    });

    // ────────────────────────────────────────────────────────────────────────────
    // Fermer avec la touche Echap
    // ────────────────────────────────────────────────────────────────────────────

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var modal = document.getElementById('modalNouvelleFactureExamen');
        if (modal && modal.style.display !== 'none') {
            window.fermerModalFactureExamen();
        }
    });

    // ────────────────────────────────────────────────────────────────────────────
    // Calcul automatique du reste (montant - avance)
    // ────────────────────────────────────────────────────────────────────────────

    function recalculerReste() {
        var montant = parseFloat(document.getElementById('fe-montant').value) || 0;
        var avance  = parseFloat(document.getElementById('fe-avance').value)  || 0;
        var reste   = Math.max(0, montant - avance);

        var resteDisplay = document.getElementById('fe-reste-display');
        if (resteDisplay) {
            resteDisplay.value = reste.toLocaleString('fr-FR') + ' FCFA';
        }

        var resumeText = document.getElementById('fe-resume-text');
        if (montant > 0) {
            if (resumeText) {
                resumeText.textContent =
                    'Montant : '  + montant.toLocaleString('fr-FR') + ' FCFA  |  ' +
                    'Avance : '   + avance.toLocaleString('fr-FR')  + ' FCFA  |  ' +
                    'Reste dû : ' + reste.toLocaleString('fr-FR')   + ' FCFA';
            }
            afficher('fe-resume');
        } else {
            cacher('fe-resume');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var elMontant = document.getElementById('fe-montant');
        var elAvance  = document.getElementById('fe-avance');
        if (elMontant) elMontant.addEventListener('input', recalculerReste);
        if (elAvance)  elAvance.addEventListener('input',  recalculerReste);
    });

    // ────────────────────────────────────────────────────────────────────────────
    // Select2 : initialisation (appelée UNE SEULE FOIS depuis ouvrirModalFactureExamen)
    //
    // ✅ CORRECTION Bug 1 : cette fonction n'est plus dans DOMContentLoaded.
    // Elle est appelée depuis ouvrirModalFactureExamen() APRÈS display:block,
    // ce qui garantit que le modal a ses vraies dimensions quand Select2
    // calcule la position du dropdown.
    // ────────────────────────────────────────────────────────────────────────────

    function initialiserSelect2() {
        if (typeof $ === 'undefined' || !$.fn || !$.fn.select2) {
            console.warn('[Modal Examen] Select2 non disponible — recherche patient désactivée.');
            return;
        }

        $('#fe-patient-select').select2({
            dropdownParent: $('#modalNouvelleFactureExamen'),
            placeholder: '-- Rechercher un patient --',
            minimumInputLength: 2,
            language: { inputTooShort: function () { return 'Tapez au moins 2 caractères'; } },
            ajax: {
                url: '{{ route("facturation.examens.api.search_patients") }}',
                dataType: 'json',
                delay: 300,
                data:           function (params) { return { q: params.term }; },
                processResults: function (data)   { return { results: data.results }; },
                cache: true,
            },
        });

        // Quand un patient est sélectionné → remplir n° dossier + charger prescriptions
        $('#fe-patient-select').on('select2:select', function (e) {
            var data = e.params.data;
            var nd = document.getElementById('fe-numero-dossier');
            if (nd) nd.value = data.numero_dossier ? 'CMCU-' + data.numero_dossier : '';
            // ✅ Bug 3 résolu automatiquement : chargerPrescriptions() est appelé
            // dès qu'un patient est sélectionné, ce qui était impossible avant
            // car Select2 ne déclenchait jamais select2:select.
            chargerPrescriptions(data.id);
        });

        // Quand on efface la sélection
        $('#fe-patient-select').on('select2:clear', function () {
            var nd = document.getElementById('fe-numero-dossier');
            if (nd) nd.value = '';
            cacher('fe-prescriptions-card');
            var sel = document.getElementById('fe-prescription-select');
            if (sel) sel.innerHTML = '<option value="">-- Saisir manuellement (sans prescription) --</option>';
        });
    }

    // ────────────────────────────────────────────────────────────────────────────
    // AJAX : charger les prescriptions du patient sélectionné
    // ────────────────────────────────────────────────────────────────────────────

    function chargerPrescriptions(patientId) {
        if (!patientId) return;

        fetch('{{ route("facturation.examens.api.patient_examens") }}?patient_id=' + patientId)
            .then(function (r) { return r.json(); })
            .then(function (prescriptions) {
                var select = document.getElementById('fe-prescription-select');
                if (!select) return;

                select.innerHTML = '<option value="">-- Saisir manuellement (sans prescription) --</option>';

                if (prescriptions.length > 0) {
                    prescriptions.forEach(function (p) {
                        var opt = document.createElement('option');
                        opt.value           = p.id;
                        opt.textContent     = p.nom + ' (' + p.detail + ')';
                        opt.dataset.libelle = p.libelle;
                        opt.dataset.medecin = p.medecin;
                        select.appendChild(opt);
                    });
                    afficher('fe-prescriptions-card');
                } else {
                    cacher('fe-prescriptions-card');
                }
            })
            .catch(function (err) {
                console.error('[Modal Examen] Erreur chargement prescriptions:', err);
            });
    }

    // ────────────────────────────────────────────────────────────────────────────
    // Pré-remplissage libellé + médecin depuis la prescription choisie
    // ────────────────────────────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', function () {
        var sel = document.getElementById('fe-prescription-select');
        if (!sel) return;

        sel.addEventListener('change', function () {
            var opt     = this.options[this.selectedIndex];
            var libelle = document.getElementById('fe-libelle');
            var medecin = document.getElementById('fe-medecin');

            if (opt && opt.value) {
                if (libelle) libelle.value = opt.dataset.libelle || '';
                if (medecin) medecin.value = opt.dataset.medecin || '';
            } else {
                if (libelle) libelle.value = '';
                if (medecin) medecin.value = '';
            }
        });
    });

})(); // fin IIFE
</script>
@endpush