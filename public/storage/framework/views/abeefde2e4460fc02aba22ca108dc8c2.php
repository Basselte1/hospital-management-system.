<?php $__env->startSection('title', 'CMCU | Examens de laboratoire'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between tw-flex-wrap tw-gap-3">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Examens de Laboratoire</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Suivi ISO 15189 / SLMTA — phases pré-analytique, analytique et post-analytique</p>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireCreate', \App\Models\Patient::class)): ?>
                <a href="<?php echo e(route('laboratoire.create')); ?>"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-no-underline tw-shadow-sm tw-border-0">
                    <i class="fas fa-plus tw-text-xs"></i>
                    Nouveau bon de laboratoire
                </a>
                <?php endif; ?>
            </div>

            
            <form method="GET" action="<?php echo e(route('laboratoire.index')); ?>"
                  class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-p-4 tw-mb-5 tw-flex tw-flex-wrap tw-gap-3 tw-items-end">

                <div class="tw-flex-1 tw-min-w-[200px]">
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Rechercher un patient
                    </label>
                    <input type="text" name="search" value="<?php echo e($search ?? ''); ?>"
                           placeholder="Nom, prénom, n° dossier…"
                           class="tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50 focus:tw-outline-none focus:tw-border-[#1D4ED8] focus:tw-ring-2 focus:tw-ring-[#1D4ED8]/10">
                </div>

                <div class="tw-min-w-[170px]">
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Statut
                    </label>
                    <select name="statut"
                            class="tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50 focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente"  <?php echo e(($statut ?? '') === 'en_attente'  ? 'selected' : ''); ?>>En attente</option>
                        <option value="en_cours"    <?php echo e(($statut ?? '') === 'en_cours'    ? 'selected' : ''); ?>>En cours</option>
                        <option value="valide"      <?php echo e(($statut ?? '') === 'valide'      ? 'selected' : ''); ?>>Validé</option>
                        <option value="remis"       <?php echo e(($statut ?? '') === 'remis'       ? 'selected' : ''); ?>>Résultats remis</option>
                        <option value="archive"     <?php echo e(($statut ?? '') === 'archive'     ? 'selected' : ''); ?>>Archivé</option>
                    </select>
                </div>

                <div class="tw-min-w-[110px]">
                    <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-mb-1.5">
                        Par page
                    </label>
                    <select name="per_page"
                            class="tw-w-full tw-px-4 tw-py-2.5 tw-text-sm tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50 focus:tw-outline-none focus:tw-border-[#1D4ED8]">
                        <?php $__currentLoopData = [10, 20, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($n); ?>" <?php echo e(($perPage ?? 20) == $n ? 'selected' : ''); ?>><?php echo e($n); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <button type="submit"
                        class="tw-px-5 tw-py-2.5 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-border-0 tw-cursor-pointer">
                    <i class="fas fa-search tw-mr-1.5 tw-text-xs"></i>
                    Filtrer
                </button>
                <?php if($search || $statut): ?>
                <a href="<?php echo e(route('laboratoire.index')); ?>"
                   class="tw-px-4 tw-py-2.5 tw-bg-slate-100 tw-text-slate-600 tw-text-sm tw-font-medium tw-rounded-xl hover:tw-bg-slate-200 tw-transition-colors tw-no-underline">
                    <i class="fas fa-times tw-mr-1 tw-text-xs"></i>
                    Réinitialiser
                </a>
                <?php endif; ?>
            </form>

            
            <div class="tw-flex tw-flex-wrap tw-gap-3 tw-mb-5">
                <?php
                    $statusColors = [
                        'en_attente' => 'tw-bg-yellow-100 tw-text-yellow-700',
                        'en_cours'   => 'tw-bg-blue-100 tw-text-blue-700',
                        'valide'     => 'tw-bg-green-100 tw-text-green-700',
                        'remis'      => 'tw-bg-teal-100 tw-text-teal-700',
                        'archive'    => 'tw-bg-slate-100 tw-text-slate-600',
                    ];
                    $statusLabels = [
                        'en_attente' => 'En attente',
                        'en_cours'   => 'En cours',
                        'valide'     => 'Validé',
                        'remis'      => 'Remis',
                        'archive'    => 'Archivé',
                    ];
                ?>
                <?php $__currentLoopData = $statusColors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('laboratoire.index', ['statut' => $key])); ?>"
                   class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold tw-no-underline tw-transition-opacity hover:tw-opacity-80 <?php echo e($color); ?> <?php echo e(($statut ?? '') === $key ? 'tw-ring-2 tw-ring-offset-1 tw-ring-current' : ''); ?>">
                    <?php echo e($statusLabels[$key]); ?>

                    <span class="tw-bg-white/60 tw-rounded-full tw-px-1.5 tw-py-0.5 tw-text-[10px] tw-font-bold">
                        <?php echo e(\App\Models\ExamenLaboratoire::where('statut', $key)->count()); ?>

                    </span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">

                <div class="tw-px-6 tw-py-4 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-8 tw-h-8 tw-rounded-lg tw-bg-indigo-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-flask tw-text-indigo-500 tw-text-xs"></i>
                    </div>
                    <h2 class="tw-text-base tw-font-semibold tw-text-slate-700">
                        Liste des bons de laboratoire
                        <span class="tw-text-sm tw-font-normal tw-text-slate-400 tw-ml-2">
                            (<?php echo e($examens->total()); ?> résultat<?php echo e($examens->total() > 1 ? 's' : ''); ?>)
                        </span>
                    </h2>
                </div>

                <div class="tw-overflow-x-auto">
                    <table class="tw-w-full tw-text-sm tw-text-left">
                        <thead class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Bon n°</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Patient</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Laborantin</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Prélèvement</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Statut</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider">Créé le</th>
                                <th class="tw-px-6 tw-py-3 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wider tw-text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tw-divide-y tw-divide-slate-100">

                            <?php $__empty_1 = true; $__currentLoopData = $examens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:tw-bg-slate-50 tw-transition-colors tw-duration-100 <?php echo e($examen->statut === 'en_attente' ? 'tw-bg-amber-50/60' : ''); ?>">

                                
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-1.5">
                                        <?php if($examen->statut === 'en_attente'): ?>
                                        <span class="tw-w-2 tw-h-2 tw-rounded-full tw-bg-red-500 tw-animate-pulse tw-shrink-0" title="Nouvelle demande — en attente du laborantin"></span>
                                        <?php endif; ?>
                                        <span class="tw-font-mono tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-bg-[#BFDBFE]/40 tw-px-2 tw-py-1 tw-rounded-md">
                                            <?php echo e($examen->numero_bon ?? '—'); ?>

                                        </span>
                                    </div>
                                </td>

                                
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-gap-2.5">
                                        <div class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-teal-100 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                            <span class="tw-text-teal-600 tw-text-xs tw-font-bold">
                                                <?php echo e(strtoupper(substr($examen->patient->prenom ?? $examen->patient->name ?? '?', 0, 1))); ?>

                                            </span>
                                        </div>
                                        <div>
                                            <p class="tw-font-medium tw-text-slate-800 tw-mb-0 tw-leading-tight">
                                                <?php echo e($examen->patient->prenom ?? ''); ?> <?php echo e(strtoupper($examen->patient->name ?? '—')); ?>

                                            </p>
                                            <p class="tw-text-xs tw-text-slate-400 tw-mb-0">
                                                N° <?php echo e($examen->patient->numero_dossier ?? '—'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </td>

                                
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-slate-600">
                                    <?php if($examen->statut === 'en_attente'): ?>
                                        <span class="tw-text-xs tw-text-amber-600 tw-italic">En attente d'attribution</span>
                                    <?php else: ?>
                                        <?php echo e($examen->laborantin->prenom ?? ''); ?> <?php echo e($examen->laborantin->name ?? '—'); ?>

                                    <?php endif; ?>
                                </td>

                                
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-slate-500">
                                    <?php echo e($examen->date_prelevement
                                        ? $examen->date_prelevement->format('d/m/Y')
                                        : '—'); ?>

                                </td>

                                
                                <td class="tw-px-6 tw-py-4">
                                    <span class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold <?php echo e($examen->statut_color ?? 'tw-bg-slate-100 tw-text-slate-600'); ?>">
                                        <?php if($examen->statut === 'en_attente'): ?>
                                        <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-current tw-animate-pulse"></span>
                                        <?php endif; ?>
                                        <?php echo e($examen->statut_label); ?>

                                    </span>
                                </td>

                                
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-slate-400">
                                    <?php echo e($examen->created_at->format('d/m/Y')); ?>

                                </td>

                                
                                <td class="tw-px-6 tw-py-4">
                                    <div class="tw-flex tw-items-center tw-justify-center tw-gap-1.5">

                                        
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireWrite', \App\Models\Patient::class)): ?>
                                        <?php if($examen->statut === 'en_attente'): ?>
                                        <a href="<?php echo e(route('laboratoire.edit', $examen->id)); ?>"
                                           title="Prendre en charge cet examen"
                                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-red-500 tw-text-white tw-text-xs tw-font-semibold hover:tw-bg-red-600 tw-transition-colors tw-no-underline tw-shadow-sm">
                                            <i class="fas fa-flask tw-text-[10px]"></i>
                                            Prendre en charge
                                        </a>
                                        <?php elseif($examen->statut === 'en_cours'): ?>
                                        <a href="<?php echo e(route('laboratoire.edit', $examen->id)); ?>"
                                           title="Continuer la saisie"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-amber-100 tw-text-amber-600 hover:tw-bg-amber-500 hover:tw-text-white tw-transition-colors tw-no-underline">
                                            <i class="fas fa-pen tw-text-xs"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php endif; ?>

                                        
                                        <a href="<?php echo e(route('laboratoire.show', $examen->id)); ?>"
                                           title="Consulter"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] tw-text-[#1D4ED8] hover:tw-bg-[#1D4ED8] hover:tw-text-white tw-transition-colors tw-no-underline">
                                            <i class="fas fa-eye tw-text-xs"></i>
                                        </a>

                                        
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireCreate', \App\Models\Patient::class)): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('laboratoireWrite', \App\Models\Patient::class)): ?>
                                        <a href="<?php echo e(route('laboratoire.bon', $examen->id)); ?>"
                                           title="Réimprimer le bon"
                                           target="_blank"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-teal-100 tw-text-teal-700 hover:tw-bg-teal-600 hover:tw-text-white tw-transition-colors tw-no-underline">
                                            <i class="fas fa-print tw-text-xs"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php endif; ?>

                                        
                                        <?php if(in_array($examen->statut, ['valide', 'remis'])): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireWrite', \App\Models\Patient::class)): ?>
                                        <a href="<?php echo e(route('laboratoire.export', $examen->id)); ?>"
                                           title="Exporter le rapport"
                                           class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-green-100 tw-text-green-700 hover:tw-bg-green-600 hover:tw-text-white tw-transition-colors tw-no-underline">
                                            <i class="fas fa-file-pdf tw-text-xs"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php endif; ?>

                                        
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireWrite', \App\Models\Patient::class)): ?>
                                        <?php if($examen->statut === 'valide'): ?>
                                        <form method="POST" action="<?php echo e(route('laboratoire.update', $examen->id)); ?>"
                                              onsubmit="return confirm('Marquer ces résultats comme remis au patient ?')"
                                              class="tw-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="action" value="remise">
                                            <input type="hidden" name="date_remise_resultat" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>">
                                            <button type="submit"
                                                    title="Marquer comme remis"
                                                    class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-teal-100 tw-text-teal-700 hover:tw-bg-teal-600 hover:tw-text-white tw-transition-colors tw-border-0 tw-cursor-pointer">
                                                <i class="fas fa-paper-plane tw-text-xs"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', \App\Models\Patient::class)): ?>
                                        
                                        <?php if($examen->statut !== 'archive'): ?>
                                        <form method="POST" action="<?php echo e(route('laboratoire.destroy', $examen->id)); ?>"
                                              onsubmit="return confirm('Archiver ce bon de laboratoire ?')"
                                              class="tw-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    title="Archiver"
                                                    class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-slate-100 tw-text-slate-500 hover:tw-bg-slate-400 hover:tw-text-white tw-transition-colors tw-border-0 tw-cursor-pointer">
                                                <i class="fas fa-archive tw-text-xs"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="tw-px-6 tw-py-14 tw-text-center">
                                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-3">
                                        <div class="tw-w-12 tw-h-12 tw-rounded-xl tw-bg-slate-100 tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-flask tw-text-slate-400 tw-text-xl"></i>
                                        </div>
                                        <p class="tw-text-slate-500 tw-text-sm tw-mb-0">Aucun examen de laboratoire enregistré</p>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireCreate', \App\Models\Patient::class)): ?>
                                        <a href="<?php echo e(route('laboratoire.create')); ?>"
                                           class="tw-text-[#1D4ED8] tw-text-sm tw-no-underline hover:tw-underline">
                                            Créer le premier bon →
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <?php if($examens->hasPages()): ?>
                <div class="tw-px-6 tw-py-4 tw-border-t tw-border-slate-100">
                    <?php echo e($examens->appends(request()->query())->links()); ?>

                </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/laboratoire/index.blade.php ENDPATH**/ ?>