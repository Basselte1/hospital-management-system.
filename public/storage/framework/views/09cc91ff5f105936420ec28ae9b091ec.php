<!-- Validation Modal -->
<div class="modal fade" id="validerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="validerForm" method="POST" action="#">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Valider le Devis</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle me-2"></i>
                        Vous êtes sur le point de valider ce devis. Cette action confirmera le devis pour le patient.
                    </div>
                    
                    <div class="mb-3">
                        <label for="commentaire_validation" class="form-label">Commentaire</label>
                        <textarea class="form-control" name="commentaire" id="commentaire_validation" rows="3" 
                                  placeholder="Ajoutez un commentaire (optionnel)"></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmer_validation" required>
                        <label class="form-check-label" for="confirmer_validation">
                            Je confirme vouloir valider ce devis
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Valider le devis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    var validerModal = document.getElementById('validerModal');
    if (validerModal) {
        validerModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var deviId = button.getAttribute('data-devi-id');
            document.getElementById('validerForm').setAttribute('action', '/admin/devis/valider/' + deviId);
            document.getElementById('commentaire_validation').value = '';
            document.getElementById('confirmer_validation').checked = false;
        });
    }
});
</script><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/devis/modals/validation.blade.php ENDPATH**/ ?>