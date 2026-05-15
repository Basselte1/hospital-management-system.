

<div class="modal fade" id="modalAjoutElement" tabindex="-1"
     aria-labelledby="modalAjoutElementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAjoutElementLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Ajouter un élément — Facture N°&nbsp;<span id="modal-facture-numero"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            
            <div class="modal-body">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-1"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-triangle me-1"></i> Erreurs :</strong>
                        <ul class="mb-0 mt-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('factures.ajouter-element')); ?>" method="POST"
                      id="formAjoutElement" novalidate>
                    <?php echo csrf_field(); ?>

                    
                    <input type="hidden" name="facture_consultation_id" id="input-facture-id">

                    
                    <div class="mb-4">
                        <label for="select-type-acte" class="form-label fw-bold">
                            Type d'acte <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-3">
                            <button type="button"
                                    class="btn btn-outline-primary flex-fill py-3 btn-type-acte"
                                    data-type="examen_labo">
                                <i class="fas fa-microscope fs-4 d-block mb-1"></i>
                                Examen prescrit
                            </button>
                            <button type="button"
                                    class="btn btn-outline-warning flex-fill py-3 btn-type-acte"
                                    data-type="soin_infirmier">
                                <i class="fas fa-syringe fs-4 d-block mb-1"></i>
                                Soin infirmier
                            </button>
                        </div>
                        
                        <input type="hidden" name="type_acte" id="input-type-acte"
                               value="<?php echo e(old('type_acte')); ?>">
                        <div class="invalid-feedback d-block text-danger small mt-1"
                             id="error-type-acte" style="display:none!important"></div>
                    </div>

                    
                    <div id="form-details" class="d-none border rounded p-3 bg-light">

                        
                        <h6 class="mb-3 fw-bold" id="form-details-titre">
                            <i class="fas fa-edit me-1"></i> Détails de l'acte
                        </h6>

                        
                        <div class="mb-3">
                            <label class="form-label">
                                <span id="label-acte-existant">Acte existant du patient</span>
                                <span class="text-muted">(optionnel — pré-remplit la description)</span>
                            </label>
                            <select class="form-select" id="select-acte-existant" name="acte_id">
                                <option value="">-- Sélectionner ou saisir manuellement ci-dessous --</option>
                            </select>
                            <div class="form-text text-muted" id="loading-actes" style="display:none">
                                <i class="fas fa-spinner fa-spin me-1"></i> Chargement…
                            </div>
                        </div>

                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['libelle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="libelle" id="input-libelle"
                                       value="<?php echo e(old('libelle')); ?>"
                                       placeholder="Ex : NFS, Pansement, Injection IV…"
                                       maxlength="255">
                                <?php $__errorArgs = ['libelle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    Montant unitaire <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control <?php $__errorArgs = ['montant_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="montant_unitaire" id="input-montant-unitaire"
                                       value="<?php echo e(old('montant_unitaire', 0)); ?>"
                                       min="0" step="100" placeholder="5000">
                                <?php $__errorArgs = ['montant_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    Quantité <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="quantite" id="input-quantite"
                                       value="<?php echo e(old('quantite', 1)); ?>"
                                       min="1" step="1">
                                <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Médecin prescripteur</label>
                                <input type="text" class="form-control"
                                       name="medecin" id="input-medecin"
                                       value="<?php echo e(old('medecin')); ?>"
                                       placeholder="Nom du médecin (optionnel)"
                                       maxlength="150">
                            </div>

                            
                            <div class="col-md-6" id="row-infirmiere" style="display:none">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user-nurse me-1 text-warning"></i>
                                    Infirmière ayant effectué les soins
                                </label>
                                <input type="text" class="form-control"
                                       name="infirmiere" id="input-infirmiere"
                                       value="<?php echo e(old('infirmiere')); ?>"
                                       placeholder="Nom de l'infirmière"
                                       maxlength="150">
                            </div>
                        </div>

                        
                        <div class="alert alert-info py-2 mb-0 mt-3">
                            <i class="fas fa-calculator me-1"></i>
                            <strong>Total ligne :</strong>
                            <span id="display-total" class="fw-bold fs-5">0</span> FCFA
                        </div>

                    </div>

                </form>
            </div>

            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <button type="submit" form="formAjoutElement" id="btn-submit"
                        class="btn btn-success d-none">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </div>

        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    'use strict';

    // ── Références DOM ────────────────────────────────────────────────────────
    const modalEl        = document.getElementById('modalAjoutElement');
    const inputFactureId = document.getElementById('input-facture-id');
    const inputTypeActe  = document.getElementById('input-type-acte');
    const formDetails    = document.getElementById('form-details');
    const formDetailsTitre = document.getElementById('form-details-titre');
    const labelActeExist = document.getElementById('label-acte-existant');
    const selectActeExist= document.getElementById('select-acte-existant');
    const loadingActes   = document.getElementById('loading-actes');
    const inputLibelle   = document.getElementById('input-libelle');
    const inputMontant   = document.getElementById('input-montant-unitaire');
    const inputQuantite  = document.getElementById('input-quantite');
    const displayTotal   = document.getElementById('display-total');
    const btnSubmit      = document.getElementById('btn-submit');
    const btnsType       = document.querySelectorAll('.btn-type-acte');

    // ── 1. Initialiser la modal à chaque ouverture ────────────────────────────
    modalEl.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;

        document.getElementById('modal-facture-numero').textContent = btn.dataset.factureNumero ?? '';
        inputFactureId.value = btn.dataset.factureId ?? '';

        const patientId = btn.dataset.patientId ?? '';
        
        // ← NOUVEAU : médecin déjà dans la facture, pas besoin d'AJAX pour ça
        const medecinFacture = btn.dataset.medecin ?? '';

        resetForm();

        if (patientId) {
            modalEl.dataset.currentPatientId = patientId;
        } else {
            delete modalEl.dataset.currentPatientId;
        }
        
        // ← NOUVEAU : stocker le médecin de la facture pour pré-remplir plus tard
        modalEl.dataset.medecinFacture = medecinFacture;
    });

    function resetForm() {
        inputTypeActe.value = '';
        inputLibelle.value  = '';
        inputMontant.value  = 0;
        inputQuantite.value = 1;
        document.getElementById('input-medecin').value    = '';
        document.getElementById('input-infirmiere').value = '';
        document.getElementById('row-infirmiere').style.display = 'none';
        displayTotal.textContent = '0';
        formDetails.classList.add('d-none');
        btnSubmit.classList.add('d-none');

        // Réinitialiser les boutons de type
        btnsType.forEach(b => {
            b.classList.remove('active', 'btn-primary', 'btn-warning');
            b.classList.add(b.dataset.type === 'examen_labo' ? 'btn-outline-primary' : 'btn-outline-warning');
        });

        selectActeExist.innerHTML = '<option value="">-- Sélectionner ou saisir manuellement ci-dessous --</option>';
    }

    // ── 2. Clic sur un bouton de type d'acte ─────────────────────────────────
    btnsType.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const type = this.dataset.type;
            inputTypeActe.value = type;

            // Style actif
            btnsType.forEach(b => {
                b.classList.remove('active', 'btn-primary', 'btn-warning',
                                   'btn-outline-primary', 'btn-outline-warning');
            });

            if (type === 'examen_labo') {
                this.classList.add('active', 'btn-primary');
                btnsType.forEach(b => {
                    if (b !== this) b.classList.add('btn-outline-warning');
                });
                formDetailsTitre.innerHTML = '<i class="fas fa-microscope me-1 text-primary"></i> Détails de l\'examen de laboratoire';
                labelActeExist.textContent = 'Examen existant du patient';
                document.getElementById('row-infirmiere').style.display = 'none';
                chargerActes('examen_labo');
            } else {
                this.classList.add('active', 'btn-warning');
                btnsType.forEach(b => {
                    if (b !== this) b.classList.add('btn-outline-primary');
                });
                formDetailsTitre.innerHTML = '<i class="fas fa-syringe me-1 text-warning"></i> Détails du soin infirmier';
                labelActeExist.textContent = 'Soin infirmier existant du patient';
                document.getElementById('row-infirmiere').style.display = '';
                chargerActes('soin_infirmier');
            }

           // Dans le forEach des btnsType, après formDetails.classList.remove('d-none') :
                formDetails.classList.remove('d-none');
                btnSubmit.classList.remove('d-none');

                // ← NOUVEAU : pré-remplir le médecin de la facture immédiatement
                const medecinFacture = modalEl.dataset.medecinFacture ?? '';
                if (medecinFacture) {
                    document.getElementById('input-medecin').value = medecinFacture;
                }
        });
    });

    // ── 3. Charger les actes existants via AJAX ───────────────────────────────
    function chargerActes(type) {
        const patientId = modalEl.dataset.currentPatientId;
        if (!patientId) return;

        selectActeExist.innerHTML = '';
        loadingActes.style.display = 'block';

        const url = type === 'examen_labo'
            ? `<?php echo e(route('api.factures.patient-examens')); ?>?patient_id=${patientId}`
            : `<?php echo e(route('api.factures.patient-soins')); ?>?patient_id=${patientId}`;

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            }
        })
        .then(function (response) {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(function (data) {
            loadingActes.style.display = 'none';
            selectActeExist.innerHTML = '<option value="">-- Sélectionner ou saisir manuellement --</option>';

            if (!Array.isArray(data) || data.length === 0) {
                selectActeExist.innerHTML += '<option disabled>Aucun acte enregistré pour ce patient</option>';
                return;
            }

            data.forEach(function (acte) {
                const label  = acte.nom ?? ('Acte #' + acte.id);
                const detail = acte.detail ?? '';
                const opt    = new Option(label + (detail ? ' (' + detail + ')' : ''), acte.id);
    
                // Stocker toutes les données dans les data-attributes de l'option
                opt.dataset.libelle    = acte.libelle    ?? label;
                opt.dataset.medecin    = acte.medecin    ?? '';
                opt.dataset.infirmiere = acte.infirmiere ?? '';   // ← NOM infirmière
            
                selectActeExist.add(opt);
            });
        })
        .catch(function () {
            loadingActes.style.display = 'none';
            selectActeExist.innerHTML = '<option value="">Erreur de chargement — saisie manuelle uniquement</option>';
        });
    }

    // Pré-remplir la description quand on choisit un acte existant
    selectActeExist.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                if (opt.dataset.libelle) {
                    inputLibelle.value = opt.dataset.libelle;
                }
                // Priorité : médecin de l'acte > médecin de la facture
                const medecinActe    = opt.dataset.medecin ?? '';
                const medecinFacture = modalEl.dataset.medecinFacture ?? '';
                document.getElementById('input-medecin').value = medecinActe || medecinFacture;

                // Pré-remplir l'infirmière si disponible (soin_infirmier)
                if (opt.dataset.infirmiere !== undefined) {
                    document.getElementById('input-infirmiere').value = opt.dataset.infirmiere ?? '';
                }
            }
        });

    // ── 4. Calcul automatique du total en temps réel ──────────────────────────
    [inputMontant, inputQuantite].forEach(function (el) {
        el.addEventListener('input', recalculerTotal);
    });

    function recalculerTotal() {
        const montant  = parseFloat(inputMontant.value)  || 0;
        const quantite = parseFloat(inputQuantite.value) || 0;
        displayTotal.textContent = (montant * quantite).toLocaleString('fr-FR');
    }

    // ── 5. Validation côté client avant soumission ────────────────────────────
    document.getElementById('formAjoutElement').addEventListener('submit', function (e) {
        let valid = true;

        if (!inputTypeActe.value) {
            e.preventDefault();
            document.getElementById('error-type-acte').textContent = 'Veuillez choisir un type d\'acte.';
            document.getElementById('error-type-acte').style.removeProperty('display');
            valid = false;
        }

        if (!inputLibelle.value.trim()) {
            e.preventDefault();
            inputLibelle.classList.add('is-invalid');
            valid = false;
        } else {
            inputLibelle.classList.remove('is-invalid');
        }

        if (!valid) {
            return false;
        }
    });

})();
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/partials/modal-ajouter-element.blade.php ENDPATH**/ ?>