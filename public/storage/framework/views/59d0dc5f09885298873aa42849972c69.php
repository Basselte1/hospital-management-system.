

<?php $__env->startSection('title', 'CMCU | Prémédication / Traitement'); ?>

<?php $__env->startSection('content'); ?>

    <style type="text/css">
        .tt-dropdown-menu {
            width: 100% !important;
        }
        .tt-menu {
            width: 422px;
            margin: 12px 0;
            padding: 8px 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
            -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
        }
        .tt-suggestion:hover {
            cursor: pointer;
            color: #fff;
            background-color: #0097cf;
        }
        #scrollable-dropdown-menu {
            max-height: 150px;
            overflow-y: auto;
        }
        .tt-suggestion p {
            margin: 0;
        }
    </style>

    <div class="wrapper">
        <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', \App\Models\User::class)): ?>
            <div class="col-md-12  toppad  offset-md-0 ">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', App\Models\Patient::class)): ?>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#DetailPremedication" title="Détails prémédication / préparation" data-whatever="@mdo">
                    <i class="fas fa-eye"></i> Consignes IDE / Préparations
                </button>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', App\Models\Patient::class)): ?>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#DetailPremedication" title="Détails prémédication / préparation" data-whatever="@mdo">
                            <i class="fas fa-eye"></i> Détails
                        </button>
                <?php endif; ?>
                <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn btn-success float-end">
                    <i class="fas fa-arrow-left"></i>  Retour au dossier patient
                </a>
            </div>
            <div class="container">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                <div class="row col-md-12">
                    <div class="container">
                        <h3 align="center">PREMEDICATION</h3>
                        <div class="row">
                            <div class="table-responsive col-md-12">
                                <form method="post" action="<?php echo e(route('premedication_consigne_preparation.store')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <table class="table table-bordered table-striped" id="user_table">
                                        <thead>
                                        <tr>
                                            <th width="35%">MEDICAMENT</th>
                                            <th width="35%">CONSIGNE IDE</th>
                                            <th width="35%">PREPARATION</th>
                                            <th width="30%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="search" name="medicament" class="form-control typeahead tt-query" id="search" autocomplete="off" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" value="<?php echo e(old('consigne_ide')); ?>" name="consigne_ide" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" value="<?php echo e(old('premedication')); ?>" name="preparation" required>
                                            </td>
                                            <td>
                                                <input type="submit" class="btn btn-primary" value="Enregistrer" />
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <input name="patient_id" value="<?php echo e($patient->id); ?>" type="hidden">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                    <br>
                    <hr>
                    <h1 class="text-center">Traitement à l'hospitalistion</h1>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                        <div class="row">
                            <div class="container">
                                <div class="table-responsive">
                                    <form method="post" id="dynamic_form" action="<?php echo e(route('traitement_hospitalisation.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <span id="result"></span>
                                        <table class="table table-bordered table-striped" id="user_table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="80%">Médicament, dosage, posologie</th>
                                                    <th width="40%">Durée (j)</th>
                                                    <th>J (-1)</th>
                                                    <th>J (0)</th>
                                                    <th>J (1)</th>
                                                    <th>J (2)</th>
                                                    <th>M</th>
                                                    <th>MI</th>
                                                    <th>S</th>
                                                    <th>N</th>
                                                    <th>M+1</th>
                                                    <th>MI+1</th>
                                                    <th>S+1</th>
                                                    <th>N+1</th>
                                                    <th>Date / Heure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <!-- FIXED: Removed 'disabled' and made it editable with readonly as fallback -->
                                                        <textarea name="medicament_posologie_dosage" cols="50" class="form-control" rows="2" readonly><?php if(!empty($medicament)): ?><?php echo e($medicament->medicament); ?><?php endif; ?></textarea>
                                                        <small class="text-muted">Ce champ est pré-rempli avec le dernier médicament de prémédication</small>
                                                    </td>
                                                    <td><input type="number" class="form-control" name="duree" min="0"></td>
                                                    <td><input type="checkbox" value="Ok" name="j"></td>
                                                    <td><input type="checkbox" value="Ok" name="j0"></td>
                                                    <td><input type="checkbox" value="Ok" name="j1"></td>
                                                    <td><input type="checkbox" value="Ok" name="j2"></td>
                                                    <td><input type="checkbox" value="Ok" name="m"></td>
                                                    <td><input type="checkbox" value="Ok" name="mi"></td>
                                                    <td><input type="checkbox" value="Ok" name="s"></td>
                                                    <td><input type="checkbox" value="Ok" name="n"></td>
                                                    <td><input type="checkbox" value="Ok" name="m1"></td>
                                                    <td><input type="checkbox" value="Ok" name="mi1"></td>
                                                    <td><input type="checkbox" value="Ok" name="s1"></td>
                                                    <td><input type="checkbox" value="Ok" name="n1"></td>
                                                    <td><input type="datetime-local" value="<?php echo e(Carbon\Carbon::now()->format('Y-m-d\TH:i')); ?>" class="form-control" name="date" required></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <input type="submit" class="btn btn-primary mb-2 float-end" value="Enregistrer" />
                                        <input name="patient_id" value="<?php echo e($patient->id); ?>" type="hidden">
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th><div class="text-center">Médicament, dosage, posologie</div></th>
                        <th><div class="text-center">Durée (j)</div></th>
                        <th><div>J (-1)</div></th>
                        <th><div>J (0)</div></th>
                        <th><div>J (1)</div></th>
                        <th><div>J (2)</div></th>
                        <th><div>M</div></th>
                        <th><div>MI</div></th>
                        <th><div>S</div></th>
                        <th><div>N</div></th>
                        <th><div>M+1</div></th>
                        <th><div>MI+1</div></th>
                        <th><div>S+1</div></th>
                        <th><div>N+1</div></th>
                        <th><div>Date</div></th>
                        <th><div>IDE</div></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <?php $__currentLoopData = $TraitementHospitalisations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $TraitementHospitalisation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($TraitementHospitalisation->medicament_posologie_dosage); ?></td>
                        <td><?php echo e($TraitementHospitalisation->duree); ?> Jour(s)</td>
                        <td><?php echo e($TraitementHospitalisation->j); ?></td>
                        <td><?php echo e($TraitementHospitalisation->j0); ?></td>
                        <td><?php echo e($TraitementHospitalisation->j1); ?></td>
                        <td><?php echo e($TraitementHospitalisation->j2); ?></td>
                        <td><?php echo e($TraitementHospitalisation->m); ?></td>
                        <td><?php echo e($TraitementHospitalisation->mi); ?></td>
                        <td><?php echo e($TraitementHospitalisation->s); ?></td>
                        <td><?php echo e($TraitementHospitalisation->n); ?></td>
                        <td><?php echo e($TraitementHospitalisation->m1); ?></td>
                        <td><?php echo e($TraitementHospitalisation->mi1); ?></td>
                        <td><?php echo e($TraitementHospitalisation->s1); ?></td>
                        <td><?php echo e($TraitementHospitalisation->n1); ?></td>
                        <td><?php echo e($TraitementHospitalisation->date); ?></td>
                        <td><?php echo e($TraitementHospitalisation->user->name); ?> <?php echo e($TraitementHospitalisation->user->prenom); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tfoot>
                </table>
                    <br>
                    <hr class="pb-3 pt-3">
                    <h1 class="text-center">Adaptation du traitemen personnel</h1>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                    <div class="table-responsive">
                        <form method="post" action="<?php echo e(route('adaptation_traitement.store')); ?>">
                            <?php echo csrf_field(); ?>
                            <table class="table table-bordered table-striped" id="user_table">
                                <thead>
                                <tr>
                                    <th class="text-center" width="80%">Médicament, dosage, posologie</th>
                                    <th>Arrêt</th>
                                    <th class="text-center">Poursuivre j'usqu'a la veille au soir</th>
                                    <th class="text-center">A continuer le matin</th>
                                    <th>J (-1)</th>
                                    <th>J (0)</th>
                                    <th>J (1)</th>
                                    <th>J (2)</th>
                                    <th>M</th>
                                    <th>MI</th>
                                    <th>S</th>
                                    <th>N</th>
                                    <th>M+1</th>
                                    <th>MI+1</th>
                                    <th>S+1</th>
                                    <th>N+1</th>
                                    <th width="5%">Date / Heure</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <textarea name="medicament_posologie_dosage" id="" cols="50" class="form-control" rows="2" required><?php echo e(old('medicament_posologie_dosage')); ?></textarea>
                                    </td>
                                    <td><input type="checkbox" value="Oui" name="arret"></td>
                                    <td><input type="checkbox" value="Oui" name="poursuivre"></td>
                                    <td><input type="checkbox" value="Oui" name="continuer"></td>
                                    <td><input type="checkbox" value="Ok" name="j"></td>
                                    <td><input type="checkbox" value="Ok" name="j0"></td>
                                    <td><input type="checkbox" value="Ok" name="j1"></td>
                                    <td><input type="checkbox" value="Ok" name="j2"></td>
                                    <td><input type="checkbox" value="Ok" name="m"></td>
                                    <td><input type="checkbox" value="Ok" name="mi"></td>
                                    <td><input type="checkbox" value="Ok" name="s"></td>
                                    <td><input type="checkbox" value="Ok" name="n"></td>
                                    <td><input type="checkbox" value="Ok" name="m1"></td>
                                    <td><input type="checkbox" value="Ok" name="mi1"></td>
                                    <td><input type="checkbox" value="Ok" name="s1"></td>
                                    <td><input type="checkbox" value="Ok" name="n1"></td>
                                    <td><input type="datetime-local" value="<?php echo e(Carbon\Carbon::now()->ToDateString()); ?>" class="form-control" name="date" required></td>
                                </tr>
                                </tbody>
                            </table>
                            <input type="submit" class="btn btn-primary mb-2 float-end" value="Enregistrer" />
                            <input name="patient_id" value="<?php echo e($patient->id); ?>" type="hidden">
                        </form>
                    </div>
                    <?php endif; ?>
                    <table class="table">
                        <thead>
                        <tr>
                            <th><div class="text-center">Médicament, dosage, posologie</div></th>
                            <th><div class="text-center">Arrêt</div></th>
                            <th><div class="text-center">Poursuivre j'usqu'a la veille au soir</div></th>
                            <th><div class="text-center">A continuer le matin</div></th>
                            <th><div>J (-1)</div></th>
                            <th><div>J (0)</div></th>
                            <th><div>J (1)</div></th>
                            <th><div>J (2)</div></th>
                            <th><div>M</div></th>
                            <th><div>MI</div></th>
                            <th><div>S</div></th>
                            <th><div>N</div></th>
                            <th><div>M+1</div></th>
                            <th><div>MI+1</div></th>
                            <th><div>S+1</div></th>
                            <th><div>N+1</div></th>
                            <th><div>Date</div></th>
                            <th><div>IDE</div></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <?php $__currentLoopData = $AdaptationTraitements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $AdaptationTraitement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($AdaptationTraitement->medicament_posologie_dosage); ?></td>
                            <td><?php echo e($AdaptationTraitement->arret); ?></td>
                            <td><?php echo e($AdaptationTraitement->poursuivre); ?></td>
                            <td><?php echo e($AdaptationTraitement->continuer); ?></td>
                            <td><?php echo e($AdaptationTraitement->j); ?></td>
                            <td><?php echo e($AdaptationTraitement->j0); ?></td>
                            <td><?php echo e($AdaptationTraitement->j1); ?></td>
                            <td><?php echo e($AdaptationTraitement->j2); ?></td>
                            <td><?php echo e($AdaptationTraitement->m); ?></td>
                            <td><?php echo e($AdaptationTraitement->mi); ?></td>
                            <td><?php echo e($AdaptationTraitement->s); ?></td>
                            <td><?php echo e($AdaptationTraitement->n); ?></td>
                            <td><?php echo e($AdaptationTraitement->m1); ?></td>
                            <td><?php echo e($AdaptationTraitement->mi1); ?></td>
                            <td><?php echo e($AdaptationTraitement->s1); ?></td>
                            <td><?php echo e($AdaptationTraitement->n1); ?></td>
                            <td><?php echo e($AdaptationTraitement->date); ?></td>
                            <td><?php echo e($AdaptationTraitement->user->name); ?> <?php echo e($AdaptationTraitement->user->prenom); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tfoot>
                    </table>
            </div>
            <?php echo $__env->make('admin.modal.detail_premedication_preparation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/premdication_tritement.blade.php ENDPATH**/ ?>