<div class="modal fade" id="FicheInterventionAnesthesiste" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">

            
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-file-medical tw-mr-2"></i>Fiche d'Intervention — Anesthésiste
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="tw-p-6 tw-max-h-[82vh] tw-overflow-y-auto">
                <form action="<?php echo e(route('fiche_intervention.store')); ?>" method="post" class="tw-space-y-5">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" value="<?php echo e($patient->id); ?>" name="patient_id">

                    
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-user"></i> Patient
                        </h3>
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nom du patient</label>
                                <input type="text" value="<?php echo e($patient->name); ?>" name="nom_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prénom du patient</label>
                                <input type="text" value="<?php echo e($patient->prenom); ?>" name="prenom_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <?php $__currentLoopData = $patient->dossiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dossier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Sexe</label>
                                <input type="text" value="<?php echo e($dossier->sexe); ?>" name="sexe_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de naissance</label>
                                <input type="date" value="<?php echo e($dossier->date_naissance); ?>" name="date_naiss_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Téléphone</label>
                                <input type="number" value="<?php echo e($dossier->portable_2); ?>" name="portable_patient"
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>

                    
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-scalpel-path"></i> Intervention
                        </h3>
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Type <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="text" name="type_intervention" required
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Durée <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="time" name="dure_intervention" required
                                       class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Date intervention <span class="tw-text-red-500">*</span>
                                </label>
                                <input type="date" name="date_intervention" value="<?php echo e(old('date_intervention')); ?>" required
                                       class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Chirurgien <span class="tw-text-red-500">*</span>
                                </label>
                                <select name="medecin" id="medecin" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                                    <option value="">Choisir le médecin</option>
                                    <?php $__currentLoopData = $medecin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($m->name); ?> <?php echo e($m->prenom); ?>"><?php echo e($m->name); ?> <?php echo e($m->prenom); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        </div>
                    </div>

                    
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-person-booth"></i> Position du patient <span class="tw-text-red-500">*</span>
                        </h3>
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4 tw-space-y-2 tw-text-sm tw-text-slate-700">

                            
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Décubitus" id="decubitus"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Décubitus
                            </label>
                            <div class="tw-ml-6 tw-space-y-1.5 tw-border-l-2 tw-border-slate-200 tw-pl-4">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="decubitus[]" value="Latéral" id="lateral"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                    Latéral
                                </label>
                                <div class="tw-ml-6 tw-flex tw-gap-4">
                                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                        <input type="checkbox" name="laterale[]" value="Droite" id="laterale_droite"
                                               class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                        Droite
                                    </label>
                                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                        <input type="checkbox" name="laterale[]" value="Gauche" id="laterale_gauche"
                                               class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                        Gauche
                                    </label>
                                </div>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="decubitus[]" value="Dorsal" id="dorsal"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                    Dorsal
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="radio" name="decubitus[]" value="Ventral" id="ventral"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                    Ventral
                                </label>
                            </div>

                            
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Lithotomie" id="lithotomie"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Lithotomie
                            </label>

                            
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Lombotomie" id="lombotomie"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Lombotomie
                            </label>
                            <div class="tw-ml-6 tw-flex tw-gap-4 tw-border-l-2 tw-border-slate-200 tw-pl-4">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="lombotomie[]" value="Droite" id="lombotomie_droite"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                    Droite
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="lombotomie[]" value="Gauche" id="lombotomie_gauche"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                    Gauche
                                </label>
                            </div>

                            
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-font-medium">
                                <input type="radio" name="position_patient[]" value="Trendelenburg" id="trendelenburg"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]">
                                Trendelenburg
                            </label>

                            
                            <div class="tw-flex tw-items-center tw-gap-3 tw-mt-1">
                                <label class="tw-text-xs tw-font-medium tw-text-slate-500 tw-shrink-0">Autre :</label>
                                <input type="text" name="position_patient[]" id="position_autre"
                                       class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                            </div>

                        </div>
                    </div>

                    
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4">

                        
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-3">
                                Aide opératoire <span class="tw-text-red-500">*</span>
                            </p>
                            <div class="tw-space-y-2 tw-text-sm tw-text-slate-700">
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="aide_op[]" value="Oui" id="aide_oui"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Oui
                                </label>
                                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                                    <input type="checkbox" name="aide_op[]" value="Non" id="aide_non"
                                           class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded"> Non
                                </label>
                            </div>
                        </div>

                        
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-3">Hospitalisation</p>
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700 tw-mb-2">
                                <input type="radio" name="hospitalisation" value="Hospitalisation" id="hosp_oui"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]"> Hospitalisation
                            </label>
                            <input type="text" name="heure" placeholder="Heure"
                                   class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-px-3 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE]">
                        </div>

                        
                        <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-p-4">
                            <p class="tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-3">Ambulatoire</p>
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-text-sm tw-text-slate-700">
                                <input type="radio" name="ambulatoire" value="Ambulatoire" id="amb_oui"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8]"> Ambulatoire
                            </label>
                        </div>

                    </div>

                    
                    <div>
                        <h3 class="tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-widest tw-mb-3 tw-pb-2 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-syringe"></i> Anesthésie <span class="tw-text-red-500">*</span>
                        </h3>
                        <div class="tw-flex tw-flex-wrap tw-gap-3">
                            <?php $__currentLoopData = ['AL', 'AG', 'LR', 'RA', 'PD', 'ALR']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer tw-px-3 tw-py-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 hover:tw-bg-[#BFDBFE]/30 tw-text-sm tw-text-slate-700 tw-font-medium tw-transition-colors">
                                <input type="checkbox" name="anesthesie[]" value="<?php echo e($type); ?>" id="anesth_<?php echo e($type); ?>"
                                       class="tw-w-4 tw-h-4 tw-text-[#1D4ED8] tw-rounded">
                                <?php echo e($type); ?>

                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            Recommandation(s)
                        </label>
                        <textarea name="recommendation" rows="4"
                                  class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[#BFDBFE] tw-resize-none"><?php echo e(old('recommendation')); ?></textarea>
                    </div>

                    
                    <div class="tw-flex tw-justify-end tw-pt-2 tw-border-t tw-border-slate-100">
                        <button type="submit"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-border-0 tw-cursor-pointer tw-transition-colors">
                            <i class="fas fa-save tw-text-xs"></i> Ajouter au dossier
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/modal/fiche_intervention_anesthesiste.blade.php ENDPATH**/ ?>