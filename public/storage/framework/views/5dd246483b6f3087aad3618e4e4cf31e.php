

<?php $__env->startSection('title', 'CMCU | Observations médicales'); ?>

<?php $__env->startSection('content'); ?>

<body>

    <div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
            <div class="container_fluid">
                <h1 class="text-center">OBSERVATIONS MEDICALES - <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></h1>
                <hr>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-right"
                            title="Retour à la liste des patients">
                            <i class="fas fa-arrow-left"></i> Retour au dossier patient 
                        </a>
                    </div>
                </div>
                    

                    <div class="tab-content">
                        
                        <ul class="nav nav-tabs mt-5">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                            <li class="active"><a data-bs-toggle="tab" href="#home" class="btn btn-primary">OBSERVATIONS MEDICALES</a></li>
                            <li><a data-bs-toggle="tab" href="#menu1" class="btn btn-primary ms-5">SOINS INFIRMIERS</a></li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                            <li><a data-bs-toggle="tab" href="#menu1" class="btn btn-primary ms-5">SOINS INFIRMIERS</a></li>
                        <?php endif; ?>
                        </ul>

                        <div id="home" class="tab-pane fade in active">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                                        
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5%">DATE</th>
                                                <th class="text-center" style="width: 15%">MEDECIN</th>
                                                <th class="text-center" style="width: 30%">OBSERVATIONS</th>
                                                <th class="text-center" style="width: 10%">ACTIONS</th>
                                            </tr>
                                        </thead>

                                    <?php endif; ?>
                                    <tbody>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                                        <!--  -->
                                        <form action="<?php echo e(route('observations_medicales.store')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <tr>
                                                <td><input name="date" class="form-control" value="<?php echo e(old('date', Carbon\Carbon::now()->ToDateString())); ?>" required="required" type="date"></td>
                                                <td>
                                                    <select name="user_id" class="form-control mb-2" required>
                                                        <option value="">Médecin / Chirurgien</option>
                                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> <?php echo e($user->prenom); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>

                                                    <select name="anesthesiste" class="form-control" required>
                                                        <option value="">Anesthésiste</option>
                                                        <?php $__currentLoopData = $anesthesistes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anesthesiste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($anesthesiste->name); ?> <?php echo e($anesthesiste->prenom); ?>"><?php echo e($anesthesiste->name); ?> <?php echo e($anesthesiste->prenom); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td><textarea name="observation" class="form-control" cols="100" rows="3" required></textarea></td>
                                            </tr>
                                            <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">
                                            <tr><td><input type="submit" class="btn btn-primary" value="Enregistrer"></td></tr>
                                        </form>
                                    <?php endif; ?>
                                    <tr>
                                        <td class="table-active"><b>DATE</b></td>
                                        <td class="table-active"><b>MEDECIN</b></td>
                                        <td class="table-active">
                                            <b>OBSERVATIONS</b>
                                        </td>
                                    </tr>
                                   <?php $__currentLoopData = $observation_medicales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $observation_medicale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($observation_medicale->date); ?></td>
                                            <td>
                                                <p><b>Chirurgien:</b> <?php echo e($observation_medicale->user->name); ?> <?php echo e($observation_medicale->user->prenom); ?></p>
                                                <p><b>Anesthésiste:</b> <?php echo e($observation_medicale->anesthesiste); ?></p>
                                            </td>
                                            <td><?php echo e($observation_medicale->observation); ?></td>
                                            <td class="text-end">
                                                <!-- Edit button -->
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        data-bs-toggle="modal" data-bs-target="#EditObservationModal<?php echo e($observation_medicale->id); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Delete button -->
                                                <form action="<?php echo e(route('observations_medicales.destroy', $observation_medicale->id)); ?>" 
                                                    method="POST" style="display:inline-block;" 
                                                    onsubmit="return confirm('Supprimer cette observation ?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        
                                        <?php echo $__env->make('admin.consultations.observation_medicale_edit', ['observation_medicale' => $observation_medicale], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="menu1" class="tab-pane fade">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                    <tr>
                                        <td class="table-active"><b>DATE</b></td>
                                        <td class="table-active"><b>MEDECIN</b></td>
                                        <td class="table-active">
                                            <b>OBSERVATIONS</b>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                                                <button type="button" class="btn btn-success float-end" title="Administrer des soins" data-bs-toggle="modal" data-bs-target="#SoinsInfirmier">
                                                    <i class="fas fa-heartbeat"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $soins_infirmiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soins_infirmier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($soins_infirmier->date); ?></td>
                                            <td>
                                                <p><b>Infirmier:</b> <?php echo e($soins_infirmier->user->name); ?> <?php echo e($soins_infirmier->user->prenom); ?></p>
                                            </td>
                                            <td><?php echo e($soins_infirmier->observation); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        <?php endif; ?>


        <?php echo $__env->make('admin.modal.soins_infirmier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>
</body>

<?php $__env->stopSection(); ?>










<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/consultations/observation_medicale.blade.php ENDPATH**/ ?>