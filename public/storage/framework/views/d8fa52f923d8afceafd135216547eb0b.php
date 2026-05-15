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
    background-color: #007bff;
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
    <!-- HEMATOLOGIE -->
    <div class="exam-section">
        <h5>HEMATOLOGIE</h5>
        <p><input type="checkbox" name="hematologie[]" value="NFS"> <span>NFS</span></p>
        <p><input type="checkbox" name="hematologie[]" value="Groupe rhésus"> <span>Groupe rhésus</span></p>
        <p><input type="checkbox" name="hematologie[]" value="Vitèsse de sédimentation"> <span>Vitèsse de sédimentation</span></p>
        <p><input type="checkbox" name="hematologie[]" value="CRP"> <span>CRP</span></p>
        <div class="autres-input">
            <label for="hematologie_autres">Autres :</label>
            <input type="text" name="hematologie[]" class="form-control" id="hematologie_autres">
        </div>
    </div>

    <!-- HEMOSTASE -->
    <div class="exam-section">
        <h5>HEMOSTASE</h5>
        <p><input type="checkbox" name="hemostase[]" value="Temps de coagulation"> <span>Temps de coagulation</span></p>
        <p><input type="checkbox" name="hemostase[]" value="Temps de céphaline activé"> <span>Temps de céphaline activé</span></p>
        <p><input type="checkbox" name="hemostase[]" value="TKC"> <span>TKC</span></p>
        <p><input type="checkbox" name="hemostase[]" value="Temps de saignement"> <span>Temps de saignement</span></p>
        <p><input type="checkbox" name="hemostase[]" value="Temps de thrombine"> <span>Temps de thrombine</span></p>
        <p><input type="checkbox" name="hemostase[]" value="Taux de prothrombine (INR)"> <span>Taux de prothrombine (INR)</span></p>
        <div class="autres-input">
            <label for="hemostase_autres">Autres :</label>
            <input type="text" name="hemostase[]" class="form-control" id="hemostase_autres">
        </div>
    </div>

    <!-- BIOCHIMIE -->
    <div class="exam-section">
        <h5>BIOCHIMIE</h5>
        <p><input type="checkbox" name="biochimie[]" value="Glycémie"> <span>Glycémie</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Ionogramme Na+/K+/Cl-/Ca+"> <span>Ionogramme Na+/K+/Cl-/Ca+</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Acide urique"> <span>Acide urique</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Créatinine"> <span>Créatinine</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Clairance de la créatine"> <span>Clairance de la créatine</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Amylases"> <span>Amylases</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Lipases"> <span>Lipases</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Gamma GT"> <span>Gamma GT</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Transaminases (GOT-GPT)"> <span>Transaminases (GOT-GPT)</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Cholestérol (HDL-LDL)"> <span>Cholestérol (HDL-LDL)</span></p>
        <p><input type="checkbox" name="biochimie[]" value="LDH"> <span>LDH</span></p>
        <p><input type="checkbox" name="biochimie[]" value="Phosphatases alcalines"> <span>Phosphatases alcalines</span></p>
        <div class="autres-input">
            <label for="biochimie_autres">Autres :</label>
            <input type="text" name="biochimie[]" class="form-control" id="biochimie_autres">
        </div>
    </div>

    <!-- HORMONOLOGIE/SEROLOGIE -->
    <div class="exam-section">
        <h5>HORMONOLOGIE/SEROLOGIE</h5>
        <p><input type="checkbox" name="hormonologie[]" value="FSH"> <span>FSH</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="LH"> <span>LH</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Prolactine"> <span>Prolactine</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="TSH"> <span>TSH</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="PTH"> <span>PTH</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="AMH"> <span>AMH</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Inhibine B"> <span>Inhibine B</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Testostérone total et libre"> <span>Testostérone total et libre</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Sérologie chlamydia"> <span>Sérologie chlamydia</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Sérologie syphilis"> <span>Sérologie syphilis</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Sérologie Hépatite C"> <span>Sérologie Hépatite C</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Sérologie Hépatite B"> <span>Sérologie Hépatite B</span></p>
        <p><input type="checkbox" name="hormonologie[]" value="Caryotype"> <span>Caryotype</span></p>
        <div class="autres-input">
            <label for="hormonologie_autres">Autres :</label>
            <input type="text" name="hormonologie[]" class="form-control" id="hormonologie_autres">
        </div>
    </div>

    <!-- MARQUEURS TUMORAUX -->
    <div class="exam-section">
        <h5>MARQUEURS TUMORAUX</h5>
        <p><input type="checkbox" name="marqueurs[]" value="PSA Total"> <span>PSA Total</span></p>
        <p><input type="checkbox" name="marqueurs[]" value="PSA Libre"> <span>PSA Libre</span></p>
        <p><input type="checkbox" name="marqueurs[]" value="AFP"> <span>AFP</span></p>
        <p><input type="checkbox" name="marqueurs[]" value="BHCG"> <span>BHCG</span></p>
        <p><input type="checkbox" name="marqueurs[]" value="CEA"> <span>CEA</span></p>
        <p><input type="checkbox" name="marqueurs[]" value="CA 15.3"> <span>CA 15.3</span></p>
        <p><input type="checkbox" name="marqueurs[]" value="CA 125"> <span>CA 125</span></p>
        <p><input type="checkbox" name="marqueurs[]" value="CA 19.9"> <span>CA 19.9</span></p>
        <div class="autres-input">
            <label for="marqueurs_autres">Autres :</label>
            <input type="text" name="marqueurs[]" class="form-control" id="marqueurs_autres">
        </div>
    </div>

    <!-- BACTERIOLOGIE/PARASITOLOGIE -->
    <div class="exam-section">
        <h5>BACTERIOLOGIE/PARASITOLOGIE</h5>
        <p><input type="checkbox" name="bacteriologie[]" value="Prélèvement urétral"> <span>Prélèvement urétral</span></p>
        <p><input type="checkbox" name="bacteriologie[]" value="Prélèvement pus"> <span>Prélèvement pus</span></p>
        <p><input type="checkbox" name="bacteriologie[]" value="Recherche chlamydia"> <span>Recherche chlamydia</span></p>
        <p><input type="checkbox" name="bacteriologie[]" value="Goutte épaisse"> <span>Goutte épaisse</span></p>
        <p><input type="checkbox" name="bacteriologie[]" value="Hémoculture"> <span>Hémoculture</span></p>
        <p><input type="checkbox" name="bacteriologie[]" value="Recherche BK crachats"> <span>Recherche BK crachats</span></p>
        <p><input type="checkbox" name="bacteriologie[]" value="Coproculture"> <span>Coproculture</span></p>
        <div class="autres-input">
            <label for="bacteriologie_autres">Autres :</label>
            <input type="text" name="bacteriologie[]" class="form-control" id="bacteriologie_autres">
        </div>
    </div>

    <!-- SPERMIOLOGIE -->
    <div class="exam-section">
        <h5>SPERMIOLOGIE</h5>
        <p><input type="checkbox" name="spermiologie[]" value="Spermogramme"> <span>Spermogramme</span></p>
        <p><input type="checkbox" name="spermiologie[]" value="Spermoculture"> <span>Spermoculture</span></p>
        <p><input type="checkbox" name="spermiologie[]" value="Contrôle Poste Vasectomie"> <span>Contrôle Poste Vasectomie</span></p>
        <p><input type="checkbox" name="spermiologie[]" value="Fructose"> <span>Fructose</span></p>
        <p><input type="checkbox" name="spermiologie[]" value="Alpha Glucosidase"> <span>Alpha Glucosidase</span></p>
        <div class="autres-input">
            <label for="spermiologie_autres">Autres :</label>
            <input type="text" name="spermiologie[]" class="form-control" id="spermiologie_autres">
        </div>
    </div>

    <!-- URINES -->
    <div class="exam-section">
        <h5>URINES</h5>
        <p><input type="checkbox" name="urines[]" value="Anatomo-Cytopathologie"> <span>Anatomo-Cytopathologie</span></p>
        <p><input type="checkbox" name="urines[]" value="Recherche BK"> <span>Recherche BK</span></p>
        <p><input type="checkbox" name="urines[]" value="Examen Cytobactériologique"> <span>Examen Cytobactériologique</span></p>
        <p><input type="checkbox" name="urines[]" value="Recherche Schistosomiase"> <span>Recherche Schistosomiase</span></p>
        <p><input type="checkbox" name="urines[]" value="Recherche Bilharziose"> <span>Recherche Bilharziose</span></p>
        <div class="autres-input">
            <label for="urines_autres">Autres :</label>
            <input type="text" name="urines[]" class="form-control" id="urines_autres">
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/consultations/partials/feuille_examen_biologie.blade.php ENDPATH**/ ?>