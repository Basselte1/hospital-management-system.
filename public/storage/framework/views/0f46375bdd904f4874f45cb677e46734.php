<style>
.exam-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
}

.exam-section {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    background-color: #f8f9fa;
    min-height: 400px;
    display: flex;
    flex-direction: column;
}

.exam-section h5 {
    background-color: #28a745;
    color: white;
    padding: 10px;
    margin: -15px -15px 15px -15px;
    border-radius: 8px 8px 0 0;
    text-align: center;
    font-weight: bold;
}

.exam-section p {
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.exam-section input[type="checkbox"] {
    margin-right: 5px;
    cursor: pointer;
}

.exam-section label {
    font-weight: 600;
    margin-bottom: 5px;
    margin-top: 10px;
}

.exam-section .form-control {
    width: 100%;
    padding: 6px 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.autres-input {
    margin-top: auto;
    padding-top: 10px;
}

@media (max-width: 768px) {
    .exam-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="exam-grid">
    <!-- RADIOGRAPHIE -->
    <div class="exam-section">
        <h5>RADIOGRAPHIE</h5>
        <p><input type="checkbox" name="radiographie[]" value="Thorax"> <span>Thorax</span></p>
        <p><input type="checkbox" name="radiographie[]" value="Abdomen sans préparation"> <span>Abdomen sans préparation</span></p>
        <div class="autres-input">
            <label for="radiographie_autres">Autres :</label>
            <input type="text" name="radiographie[]" class="form-control" id="radiographie_autres">
        </div>
    </div>

    <!-- ECHOGRAPHIE -->
    <div class="exam-section">
        <h5>ECHOGRAPHIE</h5>
        <p><input type="checkbox" name="echographie[]" value="Reins et vessie"> <span>Reins et vessie</span></p>
        <p><input type="checkbox" name="echographie[]" value="Scrotum"> <span>Scrotum</span></p>
        <div class="autres-input">
            <label for="echographie_autres">Autres :</label>
            <input type="text" name="echographie[]" class="form-control" id="echographie_autres">
        </div>
    </div>

    <!-- SCANNER -->
    <div class="exam-section">
        <h5>SCANNER</h5>
        <p><input type="checkbox" name="scanner[]" value="Abdomen-pelvis"> <span>Abdomen-pelvis</span></p>
        <p><input type="checkbox" name="scanner[]" value="Cérébral"> <span>Cérébral</span></p>
        <p><input type="checkbox" name="scanner[]" value="Rachis Cervical"> <span>Rachis Cervical</span></p>
        <p><input type="checkbox" name="scanner[]" value="Rachis dorso-lombaire"> <span>Rachis dorso-lombaire</span></p>
        <div class="autres-input">
            <label for="scanner_autres">Autres :</label>
            <input type="text" name="scanner[]" class="form-control" id="scanner_autres">
        </div>
    </div>

    <!-- IRM -->
    <div class="exam-section">
        <h5>IRM</h5>
        <p><input type="checkbox" name="irm[]" value="Abdomen-pelvis"> <span>Abdomen-pelvis</span></p>
        <p><input type="checkbox" name="irm[]" value="Prostate"> <span>Prostate</span></p>
        <p><input type="checkbox" name="irm[]" value="Moelle osseuse"> <span>Moelle osseuse</span></p>
        <div class="autres-input">
            <label for="irm_autres">Autres :</label>
            <input type="text" name="irm[]" class="form-control" id="irm_autres">
        </div>
    </div>

    <!-- SCINTIGRAPHIE -->
    <div class="exam-section">
        <h5>SCINTIGRAPHIE</h5>
        <p><input type="checkbox" name="scintigraphie[]" value="Rénale Mag3 lasix"> <span>Rénale Mag3 lasix</span></p>
        <p><input type="checkbox" name="scintigraphie[]" value="Rénale DTPA"> <span>Rénale DTPA</span></p>
        <p><input type="checkbox" name="scintigraphie[]" value="Rénale DMSA"> <span>Rénale DMSA</span></p>
        <p><input type="checkbox" name="scintigraphie[]" value="Osseuse"> <span>Osseuse</span></p>
        <div class="autres-input">
            <label for="scintigraphie_autres">Autres :</label>
            <input type="text" name="scintigraphie[]" class="form-control" id="scintigraphie_autres">
        </div>
    </div>

    <!-- AUTRES -->
    <div class="exam-section">
        <h5>AUTRES</h5>
        <div class="autres-input">
            <label for="autre">Autres examens :</label>
            <textarea name="autre" class="form-control" id="autre" rows="5" placeholder="Spécifiez d'autres examens..."></textarea>
        </div>
    </div>
</div><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcu\resources\views/admin/consultations/partials/feuille_examen_imagerie.blade.php ENDPATH**/ ?>