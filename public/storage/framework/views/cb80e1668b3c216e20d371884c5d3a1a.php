<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Menu
        <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <li>
            <a href="<?php echo e(route('premedication_adaptation.index', $patient->id)); ?>" title="Traitement à l'hospitalisation / adaptation au traitement personnel" class="btn btn-success mb-1">
                <i class="fas fa-eye"></i>
                Prémédications
            </a>
        </li>
        
        <li>
            <a href="<?php echo e(route('consultations.index', $patient->id)); ?>" class="btn btn-success mb-1">
                <i class="fas fa-eye"></i>
                Consultations Chirurgicales
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('observations_medicales.index', $patient->id)); ?>" class="btn btn-primary mb-1">
                <i class="far fa-plus-square"></i>
                Observations Médicales
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('surveillance_score.index', $patient->id)); ?>" class="btn btn-success">
                <i class="far fa-plus-square"></i>
                Surveillance D'Aptitude >= 9/10
            </a>
        </li>
    </ul>

    <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#SpostAnesth"
            title="Surveillance post anesthésique" data-whatever="@mdo">
        <i class="far fa-plus-square"></i> Surveillance Post Anesthésique
    </button>



    <!-- Fiches de consommables -->
    <a href="<?php echo e(route('fiche_consommable.index', $patient->id)); ?>" class="btn btn-primary">
        <i class="far fa-plus-square"></i>
        FICHES DE CONSOMMABLES
    </a>
    
<?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Menu
        <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal"
                data-bs-target="#FicheInterventionAnesthesiste"
                title="Ajouter une fiche d'intervention" data-whatever="@mdo">
            <i class="far fa-plus-square"></i>
            Fiche d'Intervention
        </button>
        
        <li>
            <button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#SpostAnesth"
                    title="Surveillance post anesthésique" data-whatever="@mdo">
                <i class="far fa-plus-square"></i> Surveillance Post Anesthésique
            </button>
        </li>
        <li>
            <a href="<?php echo e(route('surveillance_score.index', $patient->id)); ?>" class="btn btn-success">
                <i class="far fa-plus-square"></i>
                Surveillance d'Aptitude >= 9/10
            </a>
        </li>
    </ul>


    <a href="<?php echo e(route('ordonance.create', $patient->id)); ?>" title="Nouvelle ordonnance médicale"
    class="btn btn-primary">
        <i class="far fa-plus-square"></i>
        Ordonnances
    </a>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#ordonanceModal"
            title="Prescrire un examen complémentaire" data-whatever="@mdo">
        <i class="far fa-plus-square"></i> Examens Complémentaires
    </button>
    <a href="<?php echo e(route('observations_medicales.index', $patient->id)); ?>" class="btn btn-primary">
        <i class="far fa-plus-square"></i>
        Observations Médicales
    </a>





<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
        Menu
        <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <li>
            <a href="<?php echo e(route('premedication_adaptation.index', $patient->id)); ?>" title="Traitement à l'hospitalisation / adaptation au traitement personnel" class="btn btn-success mb-1">
                <i class="fas fa-eye"></i>
                Prémédications
            </a>
        </li>
        
        <li>
            <a href="<?php echo e(route('consultations.index', $patient->id)); ?>" class="btn btn-success mb-1">
                <i class="fas fa-eye"></i>
                Consultations chirurgicales
            </a>
        </li>
        
        <li>
            <a href="<?php echo e(route('surveillance_score.index', $patient->id)); ?>" class="btn btn-success">
                <i class="far fa-plus-square"></i>
                Surveillance d'aptitude >= 9/10
            </a>
        </li>
    </ul>

    
    <a href="<?php echo e(route('observations_medicales.index', $patient->id)); ?>" class="btn btn-primary">
        <i class="far fa-plus-square"></i>
        Observations médicales
    </a>
    <a href="<?php echo e(route('fiche_consommable.index', $patient->id)); ?>" class="btn btn-primary">
        <i class="far fa-plus-square"></i>
        FICHES DE CONSOMMABLES
    </a>

    
    <button type="button" class="btn btn-primary" title="Surveillance pré-opératoire" data-bs-toggle="modal" data-bs-target="#SurveillancePre" data-whatever="@mdo">
        <i class="far fa-plus-square"></i>
            PRISE DE PARAMETRES OPERATOIRE
    </button>
                    
    
<?php endif; ?>
<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcu\resources\views/admin/patients/partials/menu.blade.php ENDPATH**/ ?>