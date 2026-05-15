
<div class="modal fade" id="modalNouvelleFactureExamen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
 
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-flask me-2"></i>Nouvelle Facture d'Examen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
 
            <div class="modal-body">
                <form id="formNouvelleFactureExamen"
                      method="POST"
                      action="<?php echo e(route('factures.examen.store_direct')); ?>">
                    <?php echo csrf_field(); ?>
 
                    
                    <div class="card mb-3">
                        <div class="card-header bg-light fw-bold">
                            <i class="fas fa-user me-2"></i>1. Sélectionner le patient
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">
                                        Patient <span class="text-danger">*</span>
                                    </label>
                                    
                                    <select id="fe-patient-select"
                                            name="patient_id"
                                            class="form-select"
                                            required
                                            style="width: 100%;">
                                        <option value="">-- Rechercher un patient --</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        Tapez le nom, prénom ou numéro de dossier
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">N° Dossier</label>
                                    <input type="text" id="fe-numero-dossier"
                                           class="form-control" readonly
                                           placeholder="Auto-rempli">
                                </div>
                            </div>
                        </div>
                    </div>
 
                    
                    <div class="card mb-3" id="fe-prescriptions-card" style="display:none;">
                        <div class="card-header bg-light fw-bold">
                            <i class="fas fa-clipboard-list me-2"></i>
                            2. Prescriptions existantes du patient
                            <span class="text-muted fw-normal">(optionnel — pré-remplit la description)</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Sélectionner une prescription</label>
                                    <select id="fe-prescription-select"
                                            name="prescription_id"
                                            class="form-select">
                                        <option value="">-- Saisir manuellement (sans prescription) --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    
                    <div class="card mb-3">
                        <div class="card-header bg-light fw-bold">
                            <i class="fas fa-file-invoice me-2"></i>3. Détails de la facture
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
 
                                
                                <div class="col-12">
                                    <label class="form-label fw-bold">
                                        Description des examens <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="fe-libelle"
                                              name="libelle"
                                              class="form-control"
                                              rows="3"
                                              required
                                              placeholder="Ex: NFS, Glycémie, Créatinine / Echographie rénale..."></textarea>
                                    <div class="form-text text-muted">
                                        Si vous avez sélectionné une prescription ci-dessus, ce champ est pré-rempli automatiquement.
                                    </div>
                                </div>
 
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Médecin prescripteur</label>
                                    <input type="text"
                                           id="fe-medecin"
                                           name="medecin_r"
                                           class="form-control"
                                           placeholder="Nom du médecin prescripteur">
                                </div>
 
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        Mode de paiement <span class="text-danger">*</span>
                                    </label>
                                    <select name="mode_paiement" class="form-select" required>
                                        <option value="espèce">Espèce</option>
                                        <option value="orange money">Orange Money</option>
                                        <option value="mtn mobile money">MTN Mobile Money</option>
                                        <option value="chèque">Chèque</option>
                                        <option value="virement">Virement</option>
                                        <option value="bon de prise en charge">Bon de prise en charge</option>
                                    </select>
                                </div>
 
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">
                                        Montant total (FCFA) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           id="fe-montant"
                                           name="montant"
                                           class="form-control"
                                           min="0"
                                           step="100"
                                           required
                                           placeholder="0">
                                </div>
 
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Avance reçue (FCFA)</label>
                                    <input type="number"
                                           id="fe-avance"
                                           name="avance"
                                           class="form-control"
                                           min="0"
                                           step="100"
                                           value="0"
                                           placeholder="0">
                                </div>
 
                                
                                <div class="col-md-4">
                                    <label class="form-label">Reste à payer (calculé)</label>
                                    <input type="text"
                                           id="fe-reste-display"
                                           class="form-control fw-bold"
                                           readonly
                                           value="0 FCFA"
                                           style="background-color: #f8f9fa;">
                                </div>
 
                            </div>
                        </div>
                    </div>
 
                    
                    <div id="fe-resume" class="alert alert-info d-none">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="fe-resume-text"></span>
                    </div>
 
                </form>
            </div>
 
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="submit"
                        form="formNouvelleFactureExamen"
                        class="btn btn-success"
                        id="fe-submit-btn">
                    <i class="fas fa-save me-1"></i>Créer la facture examen
                </button>
            </div>
 
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/factures/partials/create-facture-examen.blade.php ENDPATH**/ ?>