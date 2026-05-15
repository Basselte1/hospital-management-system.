


<div class="modal fade" id="devisModal" tabindex="-1" aria-labelledby="devisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-2xl tw-overflow-hidden">

            
            <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                <h4 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0" id="devisModalLabel"></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body tw-p-6">
                <form id="devis_form" action="<?php echo e(route('devis.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    <input type="hidden" name="devi_id" id="devi_id">

                    
                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-4 tw-mb-5">
                        <div class="sm:tw-col-span-1">
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Patient <span class="tw-text-red-500">*</span></label>
                            <select class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]"
                                id="patient_id" name="patient_id" required>
                                <option value="">Sélectionnez un patient</option>
                                <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($patient->id); ?>" data-medecin="<?php echo e($patient->medecin_r); ?>">
                                    <?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nom du devis <span class="tw-text-red-500">*</span></label>
                            <input type="text" name="nom_devis" id="nom_devis" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                        </div>
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Code</label>
                            <input type="text" name="code_devis" id="code_devis"
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                            <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Auto-généré si vide</p>
                        </div>
                    </div>

                    <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4 tw-mb-5">
                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Type <span class="tw-text-red-500">*</span></label>
                            <select class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]"
                                id="acces_devis" name="acces_devis" required>
                                <option value="acte">Acte</option>
                                <option value="bloc">Bloc</option>
                            </select>
                        </div>
                        <div class="tw-flex tw-items-end">
                            <button type="button" id="import_patient_products"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-sm tw-font-medium tw-px-4 tw-py-2.5 tw-border-0 tw-transition-colors">
                                <i class="fas fa-download tw-text-xs"></i> Importer les produits consommés
                            </button>
                        </div>
                    </div>

                    
                    <ul class="nav nav-tabs tw-border-b tw-border-slate-200 tw-mb-4 tw-gap-1" id="devisTabs" role="tablist">
                        <?php $__currentLoopData = [
                            ['procedures', 'procedures-tab', 'fa-stethoscope', 'Éléments', 'procedures_count', 'tw-bg-[#BFDBFE] tw-text-[#1D4ED8]', true],
                            ['products', 'products-tab', 'fa-pills', 'Produits', 'products_count', 'tw-bg-teal-100 tw-text-teal-700', false],
                            ['hospitalization', 'hospitalization-tab', 'fa-bed', 'Hospitalisation', null, null, false],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$target, $id, $icon, $label, $badge, $badgeCls, $active]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tw-flex tw-items-center tw-gap-2 tw-text-sm tw-px-4 tw-py-2.5 tw-rounded-t-xl tw-border-0 <?php echo e($active ? 'active' : ''); ?>"
                                id="<?php echo e($id); ?>" data-bs-toggle="tab" data-bs-target="#<?php echo e($target); ?>" type="button" role="tab">
                                <i class="fas <?php echo e($icon); ?>"></i> <?php echo e($label); ?>

                                <?php if($badge): ?>
                                <span class="tw-inline-flex tw-rounded-full <?php echo e($badgeCls); ?> tw-text-xs tw-font-medium tw-px-2 tw-py-0.5" id="<?php echo e($badge); ?>">0</span>
                                <?php endif; ?>
                            </button>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <div class="tab-content" id="devisTabsContent">

                        
                        <div class="tab-pane fade show active" id="procedures" role="tabpanel">
                            <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-mb-3">
                                <div class="tw-grid tw-grid-cols-12 tw-bg-slate-50 tw-border-b tw-border-slate-200 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5 tw-text-center">#</div>
                                    <div class="tw-col-span-4 tw-px-3 tw-py-2.5">Élément</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Quantité</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Prix U.</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Total</div>
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5 tw-text-center">Action</div>
                                </div>
                                <div id="procedures_container" class="tw-divide-y tw-divide-slate-100">
                                    
                                </div>
                                <div class="tw-px-4 tw-py-3 tw-bg-slate-50 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                                    <button type="button" id="ajouter_procedure"
                                        class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-[#BFDBFE]/40 hover:tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-medium tw-px-3 tw-py-2 tw-border-0 tw-transition-colors">
                                        <i class="fas fa-plus-circle"></i> Ajouter une procédure
                                    </button>
                                    <span class="tw-text-sm tw-font-semibold tw-text-[#1D4ED8]">
                                        Sous-total : <span id="total_procedures">0</span> FCFA
                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="tab-pane fade" id="products" role="tabpanel">
                            <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-mb-3">
                                <div class="tw-grid tw-grid-cols-12 tw-bg-slate-50 tw-border-b tw-border-slate-200 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5 tw-text-center">#</div>
                                    <div class="tw-col-span-3 tw-px-3 tw-py-2.5">Produit</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Type</div>
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5">Stock</div>
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5">Qté</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Prix U.</div>
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5">Total</div>
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5 tw-text-center">Action</div>
                                </div>
                                <div id="products_container" class="tw-divide-y tw-divide-slate-100">
                                    
                                </div>
                                <div class="tw-px-4 tw-py-3 tw-bg-slate-50 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                                    <button type="button" id="ajouter_product"
                                        class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-text-xs tw-font-medium tw-px-3 tw-py-2 tw-border-0 tw-transition-colors">
                                        <i class="fas fa-plus-circle"></i> Ajouter un produit
                                    </button>
                                    <span class="tw-text-sm tw-font-semibold tw-text-[#14B8A6]">
                                        Sous-total : <span id="total_products">0</span> FCFA
                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="tab-pane fade" id="hospitalization" role="tabpanel">
                            <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden tw-mb-3">
                                <div class="tw-grid tw-grid-cols-12 tw-bg-slate-50 tw-border-b tw-border-slate-200 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase">
                                    <div class="tw-col-span-1 tw-px-3 tw-py-2.5 tw-text-center">#</div>
                                    <div class="tw-col-span-5 tw-px-3 tw-py-2.5">Type</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Quantité</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Prix U.</div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">Total</div>
                                </div>
                                <?php $__currentLoopData = [
                                    [1, 'Chambre', 'nbr_chambre', 'pu_chambre', 'prix_chambre', 30000],
                                    [2, 'Visite', 'nbr_visite', 'pu_visite', 'prix_visite', 10000],
                                    [3, 'AMI-JOUR (750×12)', 'nbr_ami_jour', 'pu_ami_jour', 'prix_ami_jour', 9000],
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$num, $type, $qtyName, $puName, $totalId, $defaultPu]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tw-grid tw-grid-cols-12 tw-divide-x tw-divide-slate-100 tw-border-b tw-border-slate-100">
                                    <div class="tw-col-span-1 tw-flex tw-items-center tw-justify-center tw-px-3 tw-py-2.5 tw-text-xs tw-text-slate-400"><?php echo e($num); ?></div>
                                    <div class="tw-col-span-5 tw-flex tw-items-center tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-600"><?php echo e($type); ?></div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">
                                        <input type="number" name="<?php echo e($qtyName); ?>" id="<?php echo e($qtyName); ?>" value="0" min="0"
                                            class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">
                                        <input type="number" name="<?php echo e($puName); ?>" id="<?php echo e($puName); ?>" value="<?php echo e($defaultPu); ?>" min="0"
                                            class="tw-w-full tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-1.5 tw-text-sm focus:tw-outline-none focus:tw-ring-1 focus:tw-ring-[#BFDBFE]">
                                    </div>
                                    <div class="tw-col-span-2 tw-px-3 tw-py-2.5">
                                        <input type="text" id="<?php echo e($totalId); ?>" value="0" readonly
                                            class="tw-w-full tw-rounded-lg tw-border tw-border-slate-100 tw-bg-slate-100 tw-px-2 tw-py-1.5 tw-text-sm tw-text-slate-500">
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="tw-px-4 tw-py-3 tw-bg-slate-50 tw-flex tw-justify-end">
                                    <span class="tw-text-sm tw-font-semibold tw-text-[#1D4ED8]">
                                        Sous-total : <span id="total_hospitalisation">0</span> FCFA
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tw-rounded-xl tw-bg-[#1D4ED8]/5 tw-border tw-border-[#BFDBFE]/50 tw-p-4 tw-mt-4">
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-3 tw-items-center">
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Procédures</p>
                                <p class="tw-font-bold tw-text-slate-700 tw-text-sm tw-mb-0"><span id="grand_total_procedures">0</span> FCFA</p>
                            </div>
                            <div>
                                <p class="tw-text-xs tw-text-slate-400 tw-mb-0">Produits</p>
                                <p class="tw-font-bold tw-text-slate-700 tw-text-sm tw-mb-0"><span id="grand_total_products">0</span> FCFA</p>
                            </div>
                            <div class="tw-text-right">
                                <p class="tw-text-xs tw-text-slate-500 tw-mb-0">Total Général</p>
                                <p class="tw-text-xl tw-font-bold tw-text-[#1D4ED8] tw-mb-0"><span id="total_general">0</span> <span class="tw-text-sm tw-font-normal tw-text-slate-400">FCFA</span></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            
            <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-flex tw-items-center tw-justify-end tw-gap-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Devi::class)): ?>
                <button type="button" id="devis_save"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors tw-text-sm">
                    <i class="fas fa-save tw-text-xs"></i> Enregistrer
                </button>
                <?php endif; ?>
                <button type="button" id="devis_export"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors tw-text-sm">
                    <i class="fas fa-file-pdf tw-text-xs"></i> Exporter PDF
                </button>
                <button type="button" data-bs-dismiss="modal"
                    class="tw-inline-flex tw-items-center tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors tw-text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>


<div id="autocomplete-suggestions" class="list-group tw-fixed tw-z-50 tw-shadow-lg tw-rounded-xl tw-overflow-hidden" style="display:none; max-height:300px; overflow-y:auto;"></div>





<div class="modal fade" id="reductionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <form id="reductionForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="tw-px-6 tw-py-4 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-justify-between">
                    <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-percent"></i> Appliquer une Réduction
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="tw-p-6 tw-space-y-4">
                    <div class="tw-flex tw-items-center tw-justify-between tw-rounded-xl tw-bg-[#BFDBFE]/30 tw-border tw-border-[#BFDBFE] tw-px-4 tw-py-3">
                        <span class="tw-text-xs tw-text-slate-500 tw-font-medium tw-uppercase tw-tracking-wide">Montant actuel</span>
                        <span class="tw-font-bold tw-text-[#1D4ED8]"><span id="montant_actuel">0</span> FCFA</span>
                    </div>

                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            Pourcentage de réduction <span class="tw-text-red-500">*</span>
                        </label>
                        <select name="pourcentage_reduction" id="pourcentage_reduction" required
                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                            <option value="0">Aucune réduction (0%)</option>
                            <?php $__currentLoopData = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($pct); ?>"><?php echo e($pct); ?>%</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3">
                        <div class="tw-flex tw-justify-between tw-items-center">
                            <span class="tw-text-xs tw-text-teal-600 tw-font-medium tw-uppercase tw-tracking-wide">Nouveau montant</span>
                            <span class="tw-font-bold tw-text-[#14B8A6] tw-text-lg"><span id="nouveau_montant">0</span> FCFA</span>
                        </div>
                        <p class="tw-text-xs tw-text-teal-500 tw-mt-1 tw-mb-0">Économie : <span id="economie">0</span> FCFA</p>
                    </div>

                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Commentaire</label>
                        <textarea name="commentaire" id="commentaire_reduction" rows="3"
                            placeholder="Raison de la réduction (optionnel)"
                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                    </div>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">
                        <i class="fas fa-check tw-mr-1.5"></i> Appliquer
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="refuserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <form id="refuserForm" method="POST" action="#">
                <?php echo csrf_field(); ?>
                <div class="tw-px-6 tw-py-4 tw-bg-red-500 tw-flex tw-items-center tw-justify-between">
                    <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-times-circle"></i> Refuser le Devis
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="tw-p-6 tw-space-y-4">
                    <div class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-text-sm tw-text-red-700">
                        <i class="fas fa-exclamation-triangle tw-mt-0.5 tw-shrink-0"></i>
                        Vous êtes sur le point de refuser ce devis. Veuillez indiquer la raison du refus.
                    </div>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                            Raison du refus <span class="tw-text-red-500">*</span>
                        </label>
                        <textarea name="commentaire" id="commentaire_refus" rows="4" required
                            placeholder="Expliquez pourquoi ce devis est refusé..."
                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-red-200 focus:tw-border-red-400"></textarea>
                        <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Ce commentaire sera visible par le gestionnaire</p>
                    </div>
                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                        <input type="checkbox" id="confirmer_refus" required class="tw-accent-red-500">
                        <span class="tw-text-sm tw-text-slate-600">Je confirme vouloir refuser ce devis</span>
                    </label>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">
                        <i class="fas fa-times tw-mr-1.5"></i> Refuser le devis
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="validerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <form id="validerForm" method="POST" action="#">
                <?php echo csrf_field(); ?>
                <div class="tw-px-6 tw-py-4 tw-bg-[#14B8A6] tw-flex tw-items-center tw-justify-between">
                    <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-check-circle"></i> Valider le Devis
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="tw-p-6 tw-space-y-4">
                    <div class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-text-sm tw-text-teal-700">
                        <i class="fas fa-info-circle tw-mt-0.5 tw-shrink-0"></i>
                        Vous êtes sur le point de valider ce devis. Cette action le confirmera pour le patient.
                    </div>
                    <div>
                        <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Commentaire</label>
                        <textarea name="commentaire" id="commentaire_validation" rows="3"
                            placeholder="Ajoutez un commentaire (optionnel)"
                            class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE]"></textarea>
                    </div>
                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                        <input type="checkbox" id="confirmer_validation" required class="tw-accent-[#14B8A6]">
                        <span class="tw-text-sm tw-text-slate-600">Je confirme vouloir valider ce devis</span>
                    </label>
                </div>
                <div class="tw-px-6 tw-pb-6 tw-flex tw-gap-3">
                    <button type="submit" class="tw-flex-1 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-medium tw-py-2.5 tw-border-0 tw-transition-colors">
                        <i class="fas fa-check tw-mr-1.5"></i> Valider le devis
                    </button>
                    <button type="button" data-bs-dismiss="modal" class="tw-flex-1 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-border-0">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Reduction Modal ──────────────────────────────
    waitForjQuery(function() {
        $('#pourcentage_reduction').on('change', calculateReduction);

        $('#reductionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const deviId = button.data('devi-id');
            const montant = parseFloat(button.data('montant')) || 0;
            $('#reductionForm').attr('action', '/admin/devis/appliquer-reduction/' + deviId);
            $('#montant_actuel').text(montant.toLocaleString('fr-FR'));
            $('#pourcentage_reduction').val('0');
            $('#commentaire_reduction').val('');
            calculateReduction();
        });

        function calculateReduction() {
            const montantActuel = parseFloat($('#montant_actuel').text().replace(/\s/g, '').replace(/\u202F/g, '')) || 0;
            const pourcentage = parseInt($('#pourcentage_reduction').val()) || 0;
            const reduction = (montantActuel * pourcentage) / 100;
            $('#nouveau_montant').text((montantActuel - reduction).toLocaleString('fr-FR'));
            $('#economie').text(reduction.toLocaleString('fr-FR'));
        }

        // ── Refusal Modal ────────────────────────────
        $('#refuserModal').on('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const deviId = button.getAttribute('data-devi-id');
            document.getElementById('refuserForm').setAttribute('action', '/admin/devis/refuser/' + deviId);
            document.getElementById('commentaire_refus').value = '';
            document.getElementById('confirmer_refus').checked = false;
        });

        // ── Validation Modal ─────────────────────────
        $('#validerModal').on('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const deviId = button.getAttribute('data-devi-id');
            document.getElementById('validerForm').setAttribute('action', '/admin/devis/valider/' + deviId);
            document.getElementById('commentaire_validation').value = '';
            document.getElementById('confirmer_validation').checked = false;
        });
    });
});
</script><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/devis/modal.blade.php ENDPATH**/ ?>