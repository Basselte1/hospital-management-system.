

<?php $__env->startSection('title', 'CMCU | Liste des patients'); ?>

<?php $__env->startSection('content'); ?>

<body>
    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
        <div class="container-fluid">
            <!-- En-tête -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-header-box">
                        <h1 class="page-title">
                            <i class="fas fa-users"></i> LISTE DES PATIENTS
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Formulaire de recherche -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="search-card">
                        <form action="<?php echo e(route('search.results')); ?>" method="post" class="row g-3">
                            <?php echo csrf_field(); ?>
                            <div class="col-md-8">
                                <label for="name" class="form-label">
                                    <i class="fas fa-search"></i> Rechercher un patient :
                                </label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    class="form-control form-control-lg" 
                                    placeholder="Entrez le nom ou prénom du patient..."
                                    required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search"></i> Rechercher
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Résultats de recherche -->
            <?php if(isset($patients)): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="results-info mb-3">
                        <p class="mb-0">
                            <i class="fas fa-info-circle"></i> 
                            Résultats de votre recherche sur <strong class="text-primary">"<?php echo e($name); ?>"</strong>
                        </p>
                    </div>

                    <div class="table-card">
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped table-bordered table-hover" width="100%">
                                <thead class="table-header">
                                    <tr>
                                        <th>NUMERO</th>
                                        <th>NOM</th>
                                        <th>PRENOM</th>
                                        <th>ASSURANCE</th>
                                        <th>DATE DE CREATION</th>
                                        <th class="text-center">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <span class="badge-numero"> <?php echo e($patient->numero_dossier); ?></span>
                                            <?php if($patient->isNew()): ?>
                                                <span class="badge bg-success badge-nouveau">NOUVEAU</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($patient->name); ?></td>
                                        <td><?php echo e($patient->prenom); ?></td>
                                        <td>
                                            <?php if($patient->prise_en_charge): ?>
                                                <span class="badge bg-info"><?php echo e($patient->prise_en_charge); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Aucune</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($patient->date_insertion); ?></td>
                                        <td class="text-center">
                                            <div class="btn-action-group">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('consulter', \App\Models\Patient::class)): ?>
                                                <a 
                                                    href="<?php echo e(route('patients.show', $patient->id)); ?>" 
                                                    title="Consulter le dossier du patient" 
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print', \App\Models\Patient::class)): ?>
                                                <a 
                                                    class="btn btn-success btn-sm" 
                                                    title="Générer la facture" 
                                                    href="<?php echo e(route('consultation.pdf', $patient->id)); ?>" 
                                                    onClick='if(this.disabled){ return false; } else { this.disabled = true; }'>
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Patient::class)): ?>
                                                <form 
                                                    action="<?php echo e(route('patients.destroy', $patient->id)); ?>" 
                                                    method="post" 
                                                    class="d-inline">
                                                    <?php echo csrf_field(); ?> 
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button 
                                                        type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Supprimer le dossier du patient" 
                                                        onclick="return myFunction()">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bouton Ajouter -->
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print', \App\Models\Patient::class)): ?>
            <div class="text-center mt-4 mb-4">
                <a 
                    href="<?php echo e(route('patients.create')); ?>" 
                    class="btn btn-primary btn-lg btn-add-patient" 
                    title="Vous allez ajouter un nouveau patient dans le système">
                    <i class="fas fa-plus-circle"></i> Ajouter un patient
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <style>
        /* En-tête de page */
        .page-header-box {
            background:  #5673f3 ;
            color: white;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 600;
            margin: 0;
        }

        /* Card de recherche */
        .search-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .search-card .form-label {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .search-card .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Info résultats */
        .results-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 12px 15px;
            border-radius: 4px;
        }

        .results-info p {
            color: #0d47a1;
            font-size: 0.95rem;
        }

        /* Card du tableau */
        .table-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Tableau DataTables */
        #myTable {
            margin-bottom: 0 !important;
            font-size: 0.9rem;
        }

        #myTable thead.table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        #myTable thead th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 8px;
            border-color: rgba(255,255,255,0.2);
        }

        #myTable tbody td {
            vertical-align: middle;
            padding: 10px 8px;
        }

        #myTable tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        /* Badge numéro */
        .badge-numero {
            background: #6c757d;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-nouveau {
            font-size: 0.75rem;
            padding: 3px 8px;
            margin-left: 5px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* Boutons d'action */
        .btn-action-group {
            display: inline-flex;
            gap: 5px;
        }

        .btn-action-group .btn {
            padding: 5px 10px;
            font-size: 0.875rem;
        }

        .btn-action-group .btn i {
            font-size: 0.9rem;
        }

        /* Bouton ajouter patient */
        .btn-add-patient {
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .btn-add-patient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        /* DataTables personnalisation */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 15px;
        }

        .dataTables_wrapper .dataTables_length select {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-left: 10px;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 15px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 5px 12px;
            margin: 0 2px;
            border-radius: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-color: #667eea;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.2rem;
            }

            .btn-action-group {
                flex-direction: column;
                gap: 3px;
            }

            .btn-action-group .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        function myFunction() {
            if(!confirm("Veuillez confirmer la suppression du dossier patient"))
                event.preventDefault();
        }
    </script>
</body>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cmcuapp\resources\views/admin/patients/index.blade.php ENDPATH**/ ?>