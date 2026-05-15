<?php $__env->startSection('title', 'CMCU | Liste des devis'); ?>
<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\Devi::class)): ?>
        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
            <div class="tw-max-w-screen-2xl tw-mx-auto">
                
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-4 tw-mb-8">
                    
                     <div>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                            <span class="tw-text-xs tw-font-semibold tw-tracking-widest tw-text-primary-700 tw-uppercase tw-bg-primary-100 tw-px-2.5 tw-py-1 tw-rounded-full">Devis</span>
                        </div>
                        <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-tracking-tight">Liste des devis</h1>
                        <p class="tw-text-sm tw-text-slate-500 tw-mt-0.5">Gestion et suivi de tous les devis patients</p>
                    </div>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Devi::class)): ?>
                    <button type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#devisModal"
                            data-mode="create"
                            class="table_link_right tw-inline-flex tw-items-center tw-gap-2 tw-bg-primary-700 hover:tw-bg-primary-800 tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 tw-rounded-xl tw-shadow-blue tw-transition-all tw-duration-150 tw-border-0 tw-cursor-pointer tw-whitespace-nowrap">
                        <i class="fas fa-plus tw-text-xs"></i>
                        Nouveau devis
                    </button>
                    <?php endif; ?>
                </div>
                
                
                
                <div class="tw-grid tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-8">

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-slate-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-file-alt tw-text-slate-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-slate-500 tw-font-medium tw-mb-0.5">Brouillons</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none"><?php echo e($devis->where('statut', 'brouillon')->count()); ?></p>
                        </div>
                    </div>

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-amber-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-clock tw-text-amber-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-amber-600 tw-font-medium tw-mb-0.5">En attente</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none"><?php echo e($devis->where('statut', 'en_attente')->count()); ?></p>
                        </div>
                    </div>

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-emerald-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-check-circle tw-text-emerald-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-emerald-600 tw-font-medium tw-mb-0.5">Validés</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none"><?php echo e($devis->where('statut', 'valide')->count()); ?></p>
                        </div>
                    </div>

                    
                    <div class="tw-bg-white tw-rounded-2xl tw-p-5 tw-shadow-card tw-border tw-border-red-100 tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-11 tw-h-11 tw-rounded-xl tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-times-circle tw-text-red-500 tw-text-base"></i>
                        </div>
                        <div>
                            <p class="tw-text-xs tw-text-red-500 tw-font-medium tw-mb-0.5">Refusés</p>
                            <p class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-leading-none"><?php echo e($devis->where('statut', 'refuse')->count()); ?></p>
                        </div>
                    </div>

                </div>

                
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-card tw-border tw-border-slate-100 tw-overflow-hidden">

                    
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-justify-between">
                        <div class="tw-flex tw-items-center tw-gap-2.5">
                            <div class="tw-w-1 tw-h-5 tw-rounded-full tw-bg-primary-700"></div>
                            <span class="tw-text-sm tw-font-semibold tw-text-slate-700">Tous les devis</span>
                        </div>
                        <span class="tw-text-xs tw-text-slate-400"><?php echo e($devis->count()); ?> résultat(s)</span>
                    </div>

                    
                    <div class="tw-overflow-x-auto">
                        <table id="myTable" class="tw-w-full tw-text-sm tw-table table-hover">
                            <thead>
                                <tr class="tw-bg-[#1D4ED8] tw-border-b tw-border-slate-200">
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Code</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Patient</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Médecin</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-right tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide tw-whitespace-nowrap">Montant</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Réduction</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-white.tw-uppercase.tw-tracking-wide" id="statut">Statut</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-left tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Date</th>
                                    <th class="tw-px-5 tw-py-3.5 tw-text-center tw-text-xs tw-font-semibold tw-text-white tw-uppercase tw-tracking-wide">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                <?php $__currentLoopData = $devis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $devi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:tw-bg-slate-50/70 tw-transition-colors tw-duration-100">

                                    
                                    <td class="tw-px-5 tw-py-3.5 tw-whitespace-nowrap">
                                        <span class="tw-font-mono tw-font-semibold tw-text-primary-700 tw-text-xs tw-bg-primary-50 tw-px-2 tw-py-1 tw-rounded-lg"><?php echo e($devi->code); ?></span>
                                    </td>

                                    
                                    <td class="tw-px-5 tw-py-3.5">
                                        <?php if($devi->patient): ?>
                                            <span class="tw-text-slate-800 tw-font-medium"><?php echo e($devi->patient->name); ?> <?php echo e($devi->patient->prenom); ?></span>
                                        <?php else: ?>
                                            <span class="tw-text-slate-400 tw-italic tw-text-xs">N/A</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="tw-px-5 tw-py-3.5">
                                        <?php if($devi->medecin): ?>
                                            <span class="tw-text-slate-700">Dr. <?php echo e($devi->medecin->name); ?></span>
                                        <?php elseif(!$devi->medecin_id && $devi->statut == 'brouillon'): ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-amber-700 tw-bg-amber-50 tw-border tw-border-amber-200 tw-text-xs tw-font-medium tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                                <i class="fas fa-exclamation-triangle tw-text-amber-500 tw-text-[10px]"></i>
                                                Pas de médecin
                                            </span>
                                        <?php else: ?>
                                            <span class="tw-text-slate-400 tw-italic tw-text-xs">Non assigné</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="tw-px-5 tw-py-3.5 tw-text-right tw-whitespace-nowrap">
                                        <span class="tw-font-semibold tw-text-slate-800"><?php echo e(number_format($devi->montant_apres_reduction, 0, ',', ' ')); ?>&nbsp;FCFA</span>
                                        <?php if($devi->pourcentage_reduction > 0): ?>
                                            <br>
                                            <span class="tw-text-xs tw-text-slate-400 tw-line-through"><?php echo e(number_format($devi->montant_avant_reduction, 0, ',', ' ')); ?>&nbsp;FCFA</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <?php if($devi->pourcentage_reduction > 0): ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1 tw-text-xs tw-font-semibold tw-text-teal-700 tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-2 tw-py-0.5 tw-rounded-full">
                                                <i class="fas fa-tag tw-text-[9px]"></i>
                                                -<?php echo e($devi->pourcentage_reduction); ?>%
                                            </span>
                                        <?php else: ?>
                                            <span class="tw-text-slate-300 tw-text-xs">—</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="tw-px-5 tw-py-3.5 tw-text-center">
                                        <?php if($devi->statut == 'brouillon'): ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-slate-600 tw-bg-slate-100 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-slate-400 tw-inline-block"></span>
                                                Brouillon
                                            </span>
                                        <?php elseif($devi->statut == 'en_attente'): ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-amber-700 tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-amber-400 tw-inline-block tw-animate-pulse"></span>
                                                En attente
                                            </span>
                                        <?php elseif($devi->statut == 'valide'): ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-emerald-700 tw-bg-emerald-50 tw-border tw-border-emerald-200 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-emerald-500 tw-inline-block"></span>
                                                Validé
                                            </span>
                                        <?php elseif($devi->statut == 'refuse'): ?>
                                            <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-text-red-600 tw-bg-red-50 tw-border tw-border-red-200 tw-px-2.5 tw-py-1 tw-rounded-full tw-whitespace-nowrap">
                                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-red-500 tw-inline-block"></span>
                                                Refusé
                                            </span>
                                            <?php if($devi->commentaire_medecin): ?>
                                                <i class="fas fa-comment-dots tw-text-red-400 tw-ml-1 tw-text-xs"
                                                   title="Raison: <?php echo e($devi->commentaire_medecin); ?>"
                                                   data-bs-toggle="tooltip"></i>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="tw-px-5 tw-py-3.5 tw-whitespace-nowrap">
                                        <span class="tw-text-xs tw-text-slate-500"><?php echo e($devi->created_at->format('d/m/Y')); ?></span>
                                    </td>

                                    
                                    <td class="tw-px-5 tw-py-3.5">
                                        <div class="tw-flex tw-items-center tw-justify-center tw-gap-1 tw-flex-wrap">

                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Devi::class)): ?>
                                                <?php if($devi->statut == 'brouillon' || $devi->statut == 'refuse'): ?>
                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-primary-200 tw-bg-primary-50 hover:tw-bg-primary-100 tw-text-primary-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 btn-edit-devis"
                                                            data-bs-target="#devisModal"
                                                            data-bs-toggle="modal"
                                                            data-id="<?php echo e($devi->id); ?>"
                                                            title="Modifier">
                                                        <i class="fas fa-edit tw-text-xs"></i>
                                                    </button>
                                                <?php endif; ?>

                                                
                                                <?php if($devi->statut == 'brouillon'): ?>
                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-emerald-200 tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 action-btn"
                                                            data-action="<?php echo e(route('devis.envoyer_validation', $devi->id)); ?>"
                                                            data-method="POST"
                                                            data-confirm="Envoyer ce devis au médecin pour validation ?"
                                                            title="Envoyer pour validation">
                                                        <i class="fas fa-paper-plane tw-text-xs"></i>
                                                    </button>
                                                <?php endif; ?>

                                                
                                                <?php if($devi->statut == 'en_attente' && $devi->user_id == Auth::id()): ?>
                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-amber-200 tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 action-btn"
                                                            data-action="<?php echo e(route('devis.annuler_envoi', $devi->id)); ?>"
                                                            data-method="POST"
                                                            data-confirm="Annuler l'envoi ? Le devis reviendra en brouillon."
                                                            title="Annuler l'envoi">
                                                        <i class="fas fa-undo-alt tw-text-xs"></i>
                                                    </button>
                                                <?php endif; ?>

                                                
                                                <?php if($devi->statut == 'refuse' && $devi->user_id == Auth::id()): ?>
                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-sky-200 tw-bg-sky-50 hover:tw-bg-sky-100 tw-text-sky-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 action-btn"
                                                            data-action="<?php echo e(route('devis.annuler_refus', $devi->id)); ?>"
                                                            data-method="POST"
                                                            data-confirm="Réinitialiser ce devis refusé ? Il reviendra en brouillon."
                                                            title="Réinitialiser le devis refusé">
                                                        <i class="fas fa-sync-alt tw-text-xs"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            
                                            <?php if($devi->statut == 'valide' && (
                                                    ($devi->medecin_id == Auth::id() && Auth::user()->role_id == 2)
                                                    || Auth::user()->role_id == 1
                                                )): ?>
                                                <button type="button"
                                                        class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-amber-200 tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 action-btn"
                                                        data-action="<?php echo e(route('devis.annuler_validation', $devi->id)); ?>"
                                                        data-method="POST"
                                                        data-confirm="Annuler la validation de ce devis ?"
                                                        title="Annuler validation">
                                                    <i class="fas fa-undo tw-text-xs"></i>
                                                </button>


                                                
                                            <?php endif; ?>

                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('valider', \App\Models\Devi::class)): ?>
                                                <?php if($devi->statut == 'en_attente' && (
                                                        ($devi->medecin_id == Auth::id() && Auth::user()->role_id == 2)
                                                        || Auth::user()->role_id == 1
                                                    )): ?>
                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-teal-200 tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#reductionModal"
                                                            data-devi-id="<?php echo e($devi->id); ?>"
                                                            data-montant="<?php echo e($devi->montant_avant_reduction); ?>"
                                                            title="Appliquer réduction">
                                                        <i class="fas fa-percent tw-text-xs"></i>
                                                    </button>

                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-emerald-200 tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-700 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#validerModal"
                                                            data-devi-id="<?php echo e($devi->id); ?>"
                                                            title="Valider">
                                                        <i class="fas fa-check tw-text-xs"></i>
                                                    </button>

                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-red-200 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#refuserModal"
                                                            data-devi-id="<?php echo e($devi->id); ?>"
                                                            title="Refuser">
                                                        <i class="fas fa-times tw-text-xs"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            
                                            <button type="button"
                                                    class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 hover:tw-bg-slate-100 tw-text-slate-600 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 view-devis-btn"
                                                    data-devi='<?php echo json_encode($devi, 15, 512) ?>'
                                                    title="Voir le détail">
                                                <i class="fas fa-eye tw-text-xs"></i>
                                            </button>

                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print', \App\Models\Devi::class)): ?>
                                                <?php if($devi->statut == 'valide'): ?>
                                                    <a href="<?php echo e(route('devis.print', $devi->id)); ?>"
                                                       class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 hover:tw-bg-slate-100 tw-text-slate-500 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                       title="Imprimer">
                                                        <i class="fas fa-print tw-text-xs"></i>
                                                    </a>
                                                <?php endif; ?>

                                                
                                                <form method="POST" action="<?php echo e(route('devis.generer_facture', $devi->id)); ?>" style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-slate-50 hover:tw-bg-slate-100 tw-text-slate-500 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150"
                                                            title="generer la facture ">
                                                        <i class="fas fa-print tw-text-xs"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Devi::class)): ?>
                                                <?php if($devi->statut == 'brouillon' || $devi->statut == 'refuse'): ?>
                                                    <button type="button"
                                                            class="tw-w-8 tw-h-8 tw-rounded-lg tw-border tw-border-red-200 tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-500 tw-flex tw-items-center tw-justify-center tw-transition-colors tw-duration-150 action-btn"
                                                            data-action="<?php echo e(route('devis.destroy', $devi->id)); ?>"
                                                            data-method="DELETE"
                                                            data-confirm="Êtes-vous sûr de vouloir supprimer ce devis ?"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash tw-text-xs"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <?php if($devis->hasPages()): ?>
                    <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100 tw-bg-slate-50/50">
                        <?php echo e($devis->links()); ?>

                    </div>
                    <?php endif; ?>

                </div>
                

                <form id="actionForm" method="POST" action="" style="display:none;">
            <?php echo csrf_field(); ?>
        </form>

        
        <?php echo $__env->make('admin.devis.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.devis.modals.reduction', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.devis.modals.validation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('admin.devis.modals.refusal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="modal fade" id="viewDevisModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-card-md tw-overflow-hidden">

                    <div class="modal-header tw-bg-[#1D4ED8] tw-border-0 tw-px-6 tw-py-4">
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-file-medical tw-text-white tw-text-sm"></i>
                            </div>
                            <h5 class="modal-title tw-text-white tw-font-semibold tw-text-base tw-mb-0">Détails du Devis</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body tw-p-6" id="viewDevisContent">
                        
                    </div>
                </div>
            </div>
        </div>

            </div>
        </main>
        <?php endif; ?>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>


<script src="<?php echo e(asset('admin/js/devis/convert_chiffre_lettre.js')); ?>"></script>
<script src="<?php echo e(asset('admin/js/devis/devis.js')); ?>"></script>

<script>
waitForjQuery(function() {
    $(document).on('click', '.action-btn', function(e) {
        e.preventDefault();

        var action     = $(this).data('action');
        var method     = ($(this).data('method') || 'POST').toUpperCase();
        var confirmMsg = $(this).data('confirm');

        if (confirmMsg && !confirm(confirmMsg)) {
            return;
        }

        var $form = $('#actionForm');
        $form.attr('action', action);
        $form.find('input[name="_method"]').remove();
        if (method !== 'POST') {
            $form.append(
                $('<input>', { type: 'hidden', name: '_method', value: method })
            );
        }
        $form.submit();
    });
});
</script>

<script>
console.log('Loading view devis functionality...');

window.viewDevis = function(devi) {
    console.log('viewDevis called with:', devi);

    if (!devi) {
        console.error('No devis data provided');
        return;
    }

    try {
        let statusClass = '';
        let statusDot   = '';
        let statusText  = devi.statut || 'N/A';

        if (devi.statut === 'valide') {
            statusClass = 'tw-text-emerald-700 tw-bg-emerald-50 tw-border tw-border-emerald-200';
            statusDot   = 'tw-bg-emerald-500';
            statusText  = 'Validé';
        } else if (devi.statut === 'en_attente') {
            statusClass = 'tw-text-amber-700 tw-bg-amber-50 tw-border tw-border-amber-200';
            statusDot   = 'tw-bg-amber-400';
            statusText  = 'En attente';
        } else if (devi.statut === 'refuse') {
            statusClass = 'tw-text-red-600 tw-bg-red-50 tw-border tw-border-red-200';
            statusDot   = 'tw-bg-red-500';
            statusText  = 'Refusé';
        } else if (devi.statut === 'brouillon') {
            statusClass = 'tw-text-slate-600 tw-bg-slate-100';
            statusDot   = 'tw-bg-slate-400';
            statusText  = 'Brouillon';
        }

        const patientName = devi.patient ? (devi.patient.name + ' ' + devi.patient.prenom) : 'N/A';
        const medecinName = devi.medecin ? ('Dr. ' + devi.medecin.name + ' ' + (devi.medecin.prenom || '')) : 'Non assigné';
        const ligneDevis  = devi.ligne_devis || [];

        // Line items table
        let lignesHtml = '';
        if (ligneDevis.length > 0) {
            let rows = ligneDevis.map(function(ligne, index) {
                const quantite = parseFloat(ligne.quantite) || 0;
                const prixU    = parseFloat(ligne.prix_u) || 0;
                const total    = quantite * prixU;
                return `
                    <tr class="tw-border-t tw-border-slate-100">
                        <td class="tw-px-4 tw-py-2.5 tw-text-xs tw-text-slate-400">${index + 1}</td>
                        <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700">${ligne.element || 'N/A'}</td>
                        <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${quantite}</td>
                        <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${parseInt(prixU).toLocaleString('fr-FR')} FCFA</td>
                        <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-font-semibold tw-text-slate-800">${parseInt(total).toLocaleString('fr-FR')} FCFA</td>
                    </tr>`;
            }).join('');

            lignesHtml = `
                <h6 class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-3 tw-mt-5">Éléments du devis</h6>
                <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-text-slate-500 tw-font-semibold">#</th>
                                <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-text-slate-500 tw-font-semibold">Élément</th>
                                <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold">Qté</th>
                                <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold">Prix U.</th>
                                <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>`;
        } else {
            lignesHtml = `
                <div class="tw-mt-4 tw-p-4 tw-rounded-xl tw-bg-amber-50 tw-border tw-border-amber-200 tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-amber-700">
                    <i class="fas fa-info-circle"></i> Aucun élément dans ce devis
                </div>`;
        }

        // Hospitalization section
        let hospitalizationHtml = '';
        const hasHospitalization = (devi.nbr_chambre > 0) || (devi.nbr_visite > 0) || (devi.nbr_ami_jour > 0);
        if (hasHospitalization) {
            let rows = '';
            if (devi.nbr_chambre > 0) {
                const totalChambre = (parseFloat(devi.nbr_chambre) || 0) * (parseFloat(devi.pu_chambre) || 0);
                rows += `<tr class="tw-border-t tw-border-slate-100">
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700">Chambre</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${devi.nbr_chambre}</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${parseInt(devi.pu_chambre).toLocaleString('fr-FR')} FCFA</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-font-semibold tw-text-slate-800">${parseInt(totalChambre).toLocaleString('fr-FR')} FCFA</td>
                </tr>`;
            }
            if (devi.nbr_visite > 0) {
                const totalVisite = (parseFloat(devi.nbr_visite) || 0) * (parseFloat(devi.pu_visite) || 0);
                rows += `<tr class="tw-border-t tw-border-slate-100">
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700">Visite</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${devi.nbr_visite}</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${parseInt(devi.pu_visite).toLocaleString('fr-FR')} FCFA</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-font-semibold tw-text-slate-800">${parseInt(totalVisite).toLocaleString('fr-FR')} FCFA</td>
                </tr>`;
            }
            if (devi.nbr_ami_jour > 0) {
                const totalAmi = (parseFloat(devi.nbr_ami_jour) || 0) * (parseFloat(devi.pu_ami_jour) || 0);
                rows += `<tr class="tw-border-t tw-border-slate-100">
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700">AMI-JOUR</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${devi.nbr_ami_jour}</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-text-slate-600">${parseInt(devi.pu_ami_jour).toLocaleString('fr-FR')} FCFA</td>
                    <td class="tw-px-4 tw-py-2.5 tw-text-sm tw-text-right tw-font-semibold tw-text-slate-800">${parseInt(totalAmi).toLocaleString('fr-FR')} FCFA</td>
                </tr>`;
            }
            hospitalizationHtml = `
                <h6 class="tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-widest tw-mb-3 tw-mt-5">Hospitalisation</h6>
                <div class="tw-rounded-xl tw-border tw-border-slate-200 tw-overflow-hidden">
                    <table class="tw-w-full tw-text-sm">
                        <thead>
                            <tr class="tw-bg-slate-50">
                                <th class="tw-px-4 tw-py-2.5 tw-text-left tw-text-xs tw-text-slate-500 tw-font-semibold">Type</th>
                                <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold">Quantité</th>
                                <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold">Prix U.</th>
                                <th class="tw-px-4 tw-py-2.5 tw-text-right tw-text-xs tw-text-slate-500 tw-font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>`;
        }

        // Doctor comment
        let commentsHtml = '';
        if (devi.commentaire_medecin) {
            commentsHtml = `
                <div class="tw-mt-4 tw-p-4 tw-rounded-xl tw-bg-sky-50 tw-border tw-border-sky-200 tw-text-sm tw-text-sky-800">
                    <p class="tw-font-semibold tw-mb-1"><i class="fas fa-comment-medical tw-mr-1.5"></i>Commentaire du médecin</p>
                    <p class="tw-mb-0">${devi.commentaire_medecin}</p>
                </div>`;
        }

        // Build full content
        let content = `
            
            <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-mb-4">
                <div class="tw-bg-slate-50 tw-rounded-xl tw-p-3">
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Code</p>
                    <p class="tw-font-mono tw-font-semibold tw-text-primary-700 tw-text-sm tw-mb-0">${devi.code || 'N/A'}</p>
                </div>
                <div class="tw-bg-slate-50 tw-rounded-xl tw-p-3">
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Statut</p>
                    <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-text-xs tw-font-semibold tw-px-2.5 tw-py-1 tw-rounded-full ${statusClass}">
                        <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-inline-block ${statusDot}"></span>
                        ${statusText}
                    </span>
                </div>
                <div class="tw-bg-slate-50 tw-rounded-xl tw-p-3">
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Patient</p>
                    <p class="tw-font-medium tw-text-slate-800 tw-text-sm tw-mb-0">${patientName}</p>
                </div>
                <div class="tw-bg-slate-50 tw-rounded-xl tw-p-3">
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Médecin</p>
                    <p class="tw-font-medium tw-text-slate-800 tw-text-sm tw-mb-0">${medecinName}</p>
                </div>
                <div class="tw-bg-slate-50 tw-rounded-xl tw-p-3">
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Type</p>
                    <p class="tw-font-medium tw-text-slate-800 tw-text-sm tw-mb-0">${devi.acces || 'N/A'}</p>
                </div>
                <div class="tw-bg-slate-50 tw-rounded-xl tw-p-3">
                    <p class="tw-text-xs tw-text-slate-400 tw-mb-1">Date de création</p>
                    <p class="tw-font-medium tw-text-slate-800 tw-text-sm tw-mb-0">
                        ${devi.created_at ? new Date(devi.created_at).toLocaleDateString('fr-FR') : 'N/A'}
                    </p>
                </div>
            </div>

            ${lignesHtml}
            ${hospitalizationHtml}
            ${commentsHtml}

            
            <div class="tw-mt-5 tw-rounded-xl tw-overflow-hidden tw-border tw-border-slate-200">
                <div class="tw-bg-slate-50 tw-px-5 tw-py-3 tw-flex tw-justify-between tw-items-center tw-border-b tw-border-slate-200">
                    <span class="tw-text-sm tw-text-slate-600">Montant initial</span>
                    <span class="tw-font-semibold tw-text-slate-800">${parseInt(devi.montant_avant_reduction || 0).toLocaleString('fr-FR')} FCFA</span>
                </div>
                <div class="tw-bg-slate-50 tw-px-5 tw-py-3 tw-flex tw-justify-between tw-items-center tw-border-b tw-border-slate-200">
                    <span class="tw-text-sm tw-text-slate-600">Réduction</span>
                    <span class="tw-font-semibold ${devi.pourcentage_reduction > 0 ? 'tw-text-red-500' : 'tw-text-slate-400'}">${devi.pourcentage_reduction || 0}%</span>
                </div>
                <div class="tw-bg-primary-700 tw-px-5 tw-py-4 tw-flex tw-justify-between tw-items-center">
                    <span class="tw-text-sm tw-font-semibold tw-text-white/80">Montant final à payer</span>
                    <span class="tw-text-xl tw-font-bold tw-text-white">${parseInt(devi.montant_apres_reduction || 0).toLocaleString('fr-FR')} FCFA</span>
                </div>
            </div>
        `;

        $('#viewDevisContent').html(content);

        const modalEl = document.getElementById('viewDevisModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

    } catch (error) {
        console.error('Error in viewDevis:', error);
        alert('Erreur lors de l\'affichage du devis');
    }
};

waitForjQuery(function() {
    $(document).ready(function() {
        $(document).on('click', '.view-devis-btn', function(e) {
            e.preventDefault();
            const deviData = $(this).data('devi');
            if (deviData) {
                viewDevis(deviData);
            } else {
                alert('Erreur: Données du devis introuvables');
            }
        });
    });
});
</script>

<script>
waitForjQuery(function() {
    $(document).ready(function() {
        if ($('#devisTable').length && !$.fn.DataTable.isDataTable('#devisTable')) {
            $('#devisTable').DataTable({
                language: {
                    url: "<?php echo e(asset('vendor/i18n/fr_fr.json')); ?>"
                },
                pageLength: 10,
                responsive: true,
                order: [[6, 'desc']]
            });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/devis/index.blade.php ENDPATH**/ ?>