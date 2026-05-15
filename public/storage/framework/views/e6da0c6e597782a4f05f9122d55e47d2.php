<div class="modal fade" id="ordonanceAll" tabindex="-1" role="dialog" aria-labelledby="ordonanceAll" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content tw-rounded-2xl tw-border-0 tw-shadow-xl tw-overflow-hidden">
            <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-bg-[#1e40af]">
                <h5 class="tw-text-white tw-font-semibold tw-mb-0 tw-text-sm tw-uppercase tw-tracking-wide">
                    <i class="fas fa-file-medical tw-mr-2"></i>Documents Médicaux
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="tw-p-6">

                
                <ul class="nav nav-tabs tw-mb-5 tw-border-b tw-border-slate-100" id="medicalDocsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-px-4 tw-py-2 tw-rounded-t-lg"
                                id="ordonances-tab" data-bs-toggle="tab" data-bs-target="#ordonances-content"
                                type="button" role="tab" aria-controls="ordonances-content" aria-selected="true">
                            <i class="fas fa-file-prescription tw-mr-1.5"></i> Ordonnances
                        </button>
                    </li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-px-4 tw-py-2 tw-rounded-t-lg" type="button">
                            <a style="text-decoration:none;" href="<?php echo e(route('fiche.prescription_medicale.index', $patient)); ?>" title="Prescriptions médicales">
                                <i class="fas fa-pills tw-mr-1.5"></i> Prescriptions Médicales
                            </a>
                        </button>
                    </li>
                    <?php endif; ?>
                </ul>

                <div class="tab-content" id="medicalDocsTabContent">

                    
                    <div class="tab-pane fade show active" id="ordonances-content" role="tabpanel" aria-labelledby="ordonances-tab">
                        <?php if(count($patient->ordonances)): ?>

                        <h4 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-4">Ordonnances médicales</h4>

                        
                        <div class="tw-mb-4">
                            <div class="tw-flex tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-overflow-hidden focus-within:tw-ring-2 focus-within:tw-ring-[#BFDBFE]">
                                <span class="tw-px-3 tw-flex tw-items-center tw-text-slate-400">
                                    <i class="fas fa-search tw-text-xs"></i>
                                </span>
                                <input type="text" id="searchOrdonance"
                                       placeholder="Rechercher par médicament, posologie ou date..."
                                       class="tw-flex-1 tw-bg-transparent tw-px-2 tw-py-2 tw-text-sm focus:tw-outline-none">
                                <button type="button" id="clearSearch"
                                        class="tw-px-3 tw-flex tw-items-center tw-text-slate-400 hover:tw-text-slate-600 tw-border-l tw-border-slate-200 tw-border-0 tw-bg-transparent tw-cursor-pointer">
                                    <i class="fas fa-times tw-text-xs"></i>
                                </button>
                            </div>
                            <p class="tw-text-xs tw-text-slate-400 tw-mt-1 tw-mb-0">
                                <span id="resultCount"><?php echo e(count($patient->ordonances)); ?></span> ordonnance(s) trouvée(s)
                            </p>
                        </div>

                        
                        <div class="tw-overflow-x-auto tw-max-h-[440px] tw-overflow-y-auto tw-rounded-xl tw-border tw-border-slate-100">
                            <table class="tw-w-full tw-text-sm" id="ordonancesTable">
                                <thead class="tw-sticky tw-top-0 tw-z-10">
                                    <tr class="tw-bg-[#BFDBFE]/40 tw-border-b tw-border-[#BFDBFE]">
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wider">Médicament</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wider">Quantité</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wider">Posologie</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wider">Date</th>
                                        <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-semibold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ordonancesBody" class="tw-divide-y tw-divide-slate-100">
                                    <?php $__currentLoopData = $patient->ordonances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ordonance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $splitField = function(string $v): array {
                                            $v = trim($v);
                                            if ($v === '') return [];
                                            if (str_contains($v, ' | ')) {
                                                return array_map('trim', explode(' | ', $v));
                                            }
                                            return [$v];
                                        };
                                        $meds  = $splitField((string) ($ordonance->medicament  ?? ''));
                                        $qtys  = $splitField((string) ($ordonance->quantite    ?? ''));
                                        $descs = $splitField((string) ($ordonance->description ?? ''));
                                    ?>
                                    <tr class="ordonance-row hover:tw-bg-slate-50 tw-transition-colors"
                                        data-medicament="<?php echo e(strtolower($ordonance->medicament)); ?>"
                                        data-posologie="<?php echo e(strtolower($ordonance->description)); ?>"
                                        data-date="<?php echo e(strtolower($ordonance->created_at->toFormattedDateString())); ?>">
                                        <td class="tw-px-4 tw-py-3">
                                            <?php $__currentLoopData = $meds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="tw-text-slate-700"><?php echo e($med); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="tw-px-4 tw-py-3">
                                            <?php $__currentLoopData = $qtys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="tw-text-slate-600 tw-text-xs"><?php echo e($qty); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="tw-px-4 tw-py-3">
                                            <?php $__currentLoopData = $descs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="tw-text-slate-600 tw-text-xs"><?php echo e($desc); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-xs tw-text-slate-400 tw-whitespace-nowrap"><?php echo e($ordonance->created_at->toFormattedDateString()); ?></td>
                                        <td class="tw-px-4 tw-py-3">
                                            <div class="tw-flex tw-items-center tw-gap-2">
                                                <a href="<?php echo e(route('ordonance.pdf', $ordonance->id)); ?>" title="Imprimer"
                                                   class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-emerald-50 hover:tw-bg-emerald-100 tw-text-emerald-600 tw-no-underline tw-transition-colors">
                                                    <i class="fas fa-print tw-text-xs"></i>
                                                </a>
                                                <a href="<?php echo e(route('ordonances.edit', ['id' => $ordonance->id])); ?>?patient=<?php echo e($patient->id); ?>" title="Modifier"
                                                   class="tw-inline-flex tw-items-center tw-justify-center tw-w-8 tw-h-8 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-no-underline tw-transition-colors">
                                                    <i class="fas fa-edit tw-text-xs"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="noResultMessage" class="tw-hidden tw-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-amber-50 tw-border tw-border-amber-200 tw-px-4 tw-py-3 tw-mt-3 tw-text-sm tw-text-amber-700">
                            <i class="fas fa-exclamation-triangle tw-shrink-0"></i> Aucune ordonnance ne correspond à votre recherche.
                        </div>

                        <?php else: ?>
                        <div class="tw-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#BFDBFE]/20 tw-border tw-border-[#BFDBFE] tw-px-4 tw-py-3 tw-text-sm tw-text-[#1D4ED8]">
                            <i class="fas fa-info-circle tw-shrink-0"></i> Aucune ordonnance enregistrée pour ce patient.
                        </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOrdonance');
    const clearBtn = document.getElementById('clearSearch');
    const resultCount = document.getElementById('resultCount');
    const noResultMessage = document.getElementById('noResultMessage');
    const rows = document.querySelectorAll('.ordonance-row');

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        rows.forEach(row => {
            const matches = row.getAttribute('data-medicament').includes(searchTerm) ||
                            row.getAttribute('data-posologie').includes(searchTerm) ||
                            row.getAttribute('data-date').includes(searchTerm);
            if (matches || searchTerm === '') { row.style.display = ''; visibleCount++; }
            else { row.style.display = 'none'; }
        });
        resultCount.textContent = visibleCount;
        noResultMessage.classList.toggle('tw-hidden', visibleCount > 0 || searchTerm === '');
    }

    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
        searchInput.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); performSearch(); } });
    }
    if (clearBtn) {
        clearBtn.addEventListener('click', () => { searchInput.value = ''; performSearch(); searchInput.focus(); });
    }
});
</script><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/modal/ordonance_show.blade.php ENDPATH**/ ?>