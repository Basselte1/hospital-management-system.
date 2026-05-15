<tbody id="show_consultation" style="display: contents;">
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>

        
        <tr>
            <td colspan="2" class="tw-px-0 tw-pt-6 tw-pb-1">
                <a href="<?php echo e(route('consultations.index', $patient->id)); ?>" class="tw-no-underline">
                    <span class="tw-inline-flex tw-items-center tw-gap-2 tw-text-base tw-font-bold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wide">
                        <i class="fas fa-stethoscope"></i> Consultation
                    </span>
                </a>
            </td>
        </tr>

        <?php if(count($patient->consultations) && $consultations): ?>

            <tr class="tw-bg-slate-50">
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-tracking-wide tw-w-48">Date</td>
                <td class="tw-px-3 tw-py-2 tw-font-semibold tw-text-slate-700 tw-text-sm">
                    <?php echo e($consultations->created_at?->format('d/m/Y') ?? '—'); ?>

                    <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>"
                       class="tw-float-right tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-text-xs tw-font-medium tw-px-3 tw-py-1 tw-no-underline tw-transition-colors">
                        <i class="fas fa-edit tw-text-[10px]"></i> Éditer
                    </a>
                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-w-48">Médecin responsable</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultations->medecin_r ?? 'Non renseigné'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Motif de consultation</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultations->motif_c ?? 'Non renseigné'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Allergies</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo nl2br(e($consultations->allergie ?? '')); ?></td></tr>
            <tr>
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Groupe sanguin</td>
                <td class="tw-px-3 tw-py-2">
                    <span class="tw-inline-flex tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-bold tw-px-2.5 tw-py-0.5">
                        <?php echo e($consultations->groupe ?? 'Non défini'); ?>

                    </span>
                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Antécédents médicaux</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo nl2br(e($consultations->antecedent_m ?? '')); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Antécédents chirurgicaux</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo nl2br(e($consultations->antecedent_c ?? '')); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Interrogatoire</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo nl2br(e($consultations->interrogatoire ?? '')); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Diagnostic</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo nl2br(e($consultations->diagnostic ?? '')); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Proposition thérapeutique</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo nl2br(e($consultations->proposition_therapeutique ?? '')); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Proposition de suivi</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo nl2br(e($consultations->proposition ?? '')); ?></td></tr>

        <?php else: ?>
            <tr>
                <td colspan="2" class="tw-px-3 tw-py-3 tw-text-sm tw-text-slate-400 tw-italic">Aucune consultation disponible</td>
            </tr>
        <?php endif; ?>

        <tr>
            <td class="tw-px-3 tw-py-3" colspan="2">
                <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                    <a href="<?php echo e(route('consultations.create', $patient->id)); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                        <i class="fas fa-book tw-text-xs"></i> Nouvelle consultation
                    </a>
                    <?php endif; ?>
                    <?php if(count($patient->consultations)): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                        <a href="<?php echo e(route('consultationsdesuivi.create', $patient->id)); ?>"
                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                            <i class="fa-solid fa-book-medical tw-text-xs"></i> Consultation de suivi
                        </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
        <tr>
            <td colspan="2" class="tw-px-0 tw-pt-6 tw-pb-1">
                <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>" class="tw-no-underline">
                    <span class="tw-inline-flex tw-items-center tw-gap-2 tw-text-base tw-font-bold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wide">
                        <i class="fas fa-lungs"></i> Consultation Anesthésie
                    </span>
                </a>
            </td>
        </tr>

        <?php if(count($patient->consultation_anesthesistes) && $consultation_anesthesistes): ?>

            <tr class="tw-bg-slate-50">
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-48">Date</td>
                <td class="tw-px-3 tw-py-2 tw-font-semibold tw-text-slate-700 tw-text-sm">
                    <?php echo e($consultation_anesthesistes->created_at?->format('d/m/Y') ?? '—'); ?>

                    <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>"
                       class="tw-float-right tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-text-xs tw-font-medium tw-px-3 tw-py-1 tw-no-underline tw-transition-colors">
                        <i class="fas fa-edit tw-text-[10px]"></i> Éditer
                    </a>
                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Service</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->service ?? 'Non renseigné'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Spécialité</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->specialite ?? 'Non renseignée'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Médecin traitant</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->medecin_traitant ?? 'Non renseigné'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Opérateur</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->operateur ?? 'Non renseigné'); ?></td></tr>
            <tr>
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Date d'intervention</td>
                <td class="tw-px-3 tw-py-2">
                    <span class="tw-inline-flex tw-rounded-full tw-bg-[#BFDBFE] tw-text-[#1D4ED8] tw-text-xs tw-font-semibold tw-px-2.5 tw-py-0.5">
                        <?php echo e($consultation_anesthesistes->date_intervention ?? 'Non définie'); ?>

                    </span>
                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Motif d'admission</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->motif_admission ?? 'Non renseigné'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Mémo</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->memo ?? ''); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Anesthésie en salle</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->anesthesi_salle ?? 'Non renseignée'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Date d'hospitalisation</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->date_hospitalisation ?? 'Non définie'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Classe ASA</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->classe_asa ?? 'Non définie'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Antécédents / Traitement</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->antecedent_traitement ?? ''); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Examens cliniques</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->examen_clinique ?? ''); ?></td></tr>
            <tr>
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Allergie</td>
                <td class="tw-px-3 tw-py-2">
                    <span class="tw-inline-flex tw-rounded-full tw-bg-amber-100 tw-text-amber-700 tw-text-xs tw-font-semibold tw-px-2.5 tw-py-0.5">
                        <?php echo e($consultation_anesthesistes->allergie ?? 'Aucune'); ?>

                    </span>
                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Intubation</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->intubation ?? 'Non renseignée'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Mallampati</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->mallampati ?? 'Non évalué'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Distance interincisive</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->distance_interincisive ?? 'Non mesurée'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Distance thyromentonnière</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->distance_thyromentoniere ?? 'Non mesurée'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Mobilité cervicale</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->mobilite_servicale ?? 'Non évaluée'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Technique d'anesthésie</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->technique_anesthesie1 ?? 'Non définie'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Bénéfice / Risque</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->benefice_risque ?? ''); ?></td></tr>
            <tr>
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Jeune préopératoire</td>
                <td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700">
                    <span class="tw-text-slate-500 tw-font-semibold">Solide :</span> <?php echo e($consultation_anesthesistes->solide ?? 'Non renseigné'); ?><br>
                    <span class="tw-text-slate-500 tw-font-semibold">Liquide :</span> <?php echo e($consultation_anesthesistes->liquide ?? 'Non renseigné'); ?>

                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Adaptation au traitement</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->adaptation_traitement ?? ''); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Autres adaptations</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->autre1 ?? ''); ?></td></tr>
            <tr>
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Technique envisagée</td>
                <td class="tw-px-3 tw-py-2">
                    <?php if($consultation_anesthesistes->technique_anesthesie): ?>
                        <ul class="tw-list-disc tw-list-inside tw-text-sm tw-text-slate-700 tw-space-y-0.5 tw-mb-0 tw-pl-0">
                            <?php $__currentLoopData = explode(",", $consultation_anesthesistes->technique_anesthesie); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $technique): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e(trim($technique)); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <span class="tw-text-slate-400 tw-text-sm">Non renseignée</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Antibioprophylaxie</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->antibiotique ?? 'Non prescrite'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Synthèse pré-opératoire</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($consultation_anesthesistes->synthese_preop ?? ''); ?></td></tr>
            <tr>
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Examens paracliniques</td>
                <td class="tw-px-3 tw-py-2">
                    <?php if($consultation_anesthesistes->examen_paraclinique): ?>
                        <ul class="tw-list-disc tw-list-inside tw-text-sm tw-text-slate-700 tw-space-y-0.5 tw-mb-0 tw-pl-0">
                            <?php $__currentLoopData = explode(",", $consultation_anesthesistes->examen_paraclinique); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e(trim($examen)); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php else: ?>
                        <span class="tw-text-slate-400 tw-text-sm">Aucun examen prescrit</span>
                    <?php endif; ?>
                </td>
            </tr>

            
            <tr>
                <td colspan="2" class="tw-px-3 tw-pt-5 tw-pb-1">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>" class="tw-no-underline">
                            <span class="tw-text-sm tw-font-bold tw-text-[#14B8A6] tw-uppercase tw-tracking-wide">
                                <i class="fas fa-clipboard-list tw-mr-1"></i> VPA / Éléments Nouveaux
                            </span>
                        </a>
                        <?php if(count($patient->visite_preanesthesiques)): ?>
                        <a href="<?php echo e(route('consentement_eclaire.pdf', $patient->id)); ?>"
                           class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-text-xs tw-font-medium tw-px-3 tw-py-1.5 tw-no-underline tw-transition-colors">
                            <i class="far fa-check-circle tw-text-xs"></i> Consentement éclairé
                        </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php if(count($patient->visite_preanesthesiques) && $visite_anesthesistes): ?>
                <tr class="tw-bg-slate-50">
                    <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-48">Date de visite</td>
                    <td class="tw-px-3 tw-py-2 tw-font-semibold tw-text-slate-700 tw-text-sm"><?php echo e($visite_anesthesistes->date_visite ?? '—'); ?></td>
                </tr>
                <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Éléments nouveaux</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($visite_anesthesistes->element_nouveaux ?? 'Aucun'); ?></td></tr>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="tw-px-3 tw-py-3 tw-text-sm tw-text-slate-400 tw-italic">Aucune visite pré-anesthésique disponible</td>
                </tr>
            <?php endif; ?>

        <?php else: ?>
            <tr>
                <td colspan="2" class="tw-px-3 tw-py-3 tw-text-sm tw-text-slate-400 tw-italic">Aucune consultation disponible</td>
            </tr>
        <?php endif; ?>

        <tr>
            <td class="tw-px-3 tw-py-3" colspan="2">
                <div class="tw-flex tw-items-center tw-gap-2 tw-flex-wrap">
                    <?php if(count($patient->dossiers)): ?>
                    <a href="<?php echo e(route('consultations.create', $patient->id)); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                        <i class="fas fa-book tw-text-xs"></i> Nouvelle Consultation Anesthésiste
                    </a>
                    <?php else: ?>
                    <span class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 tw-text-slate-400 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-cursor-not-allowed"
                          data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top"
                          data-bs-content="Vous devez d'abord compléter le dossier patient !">
                        <i class="fas fa-book tw-text-xs"></i> Nouvelle Consultation Anesthésiste
                    </span>
                    <?php endif; ?>
                    <?php if(count($patient->consultation_anesthesistes)): ?>
                    <button type="button"
                        class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-teal-500 hover:tw-bg-teal-600 tw-text-white tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-border-0 tw-transition-colors"
                        data-bs-toggle="modal" data-bs-target="#VisiteAnesthesiste">
                        <i class="far fa-plus-square tw-text-xs"></i> Visite Pré-anesthésique
                    </button>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endif; ?>

    
    <tr>
        <td colspan="2" class="tw-px-0 tw-pt-6 tw-pb-1">
            <a href="<?php echo e(route('fiche_parametre.index', $patient->id)); ?>" class="tw-no-underline">
                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-text-base tw-font-bold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wide">
                    <i class="fas fa-heartbeat"></i> Paramètres
                </span>
            </a>
        </td>
    </tr>

    <?php if(count($patient->parametres) > 0 && $parametres): ?>

        <tr class="tw-bg-slate-50">
            <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-48">Date</td>
            <td class="tw-px-3 tw-py-2 tw-font-semibold tw-text-slate-700 tw-text-sm">
                <?php echo e($parametres->created_at?->format('d/m/Y') ?? '—'); ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?>
                <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>"
                   class="tw-float-right tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-text-xs tw-font-medium tw-px-3 tw-py-1 tw-no-underline tw-transition-colors">
                    <i class="fas fa-edit tw-text-[10px]"></i> Éditer
                </a>
                <?php endif; ?>
            </td>
        </tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Date de naissance</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->date_naissance ?? 'Non renseignée'); ?></td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Âge</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->age ?? '—'); ?> Ans</td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Poids</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->poids ?? '—'); ?> Kg</td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Taille</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->taille ?? '—'); ?> M</td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Température</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->temperature ?? '—'); ?> °C</td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Glycémie</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->glycemie ?? '—'); ?> g/l</td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">SpO2</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->spo2 ?? '—'); ?> %</td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">IMC / BMI</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->inc_bmi ?? 'Non calculé'); ?></td></tr>
        <tr>
            <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">TA</td>
            <td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700">
                <span class="tw-text-slate-500 tw-font-semibold">Bg :</span> <?php echo e($parametres->bras_gauche ?? '—'); ?> mmHg &nbsp;
                <span class="tw-text-slate-500 tw-font-semibold">Bd :</span> <?php echo e($parametres->bras_droit ?? '—'); ?> mmHg
            </td>
        </tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">FR</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->fr ?? '—'); ?> Mvts/min</td></tr>
        <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">FC</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($parametres->fc ?? '—'); ?> Pls/min</td></tr>

    <?php else: ?>
        <tr>
            <td colspan="2" class="tw-px-3 tw-py-3 tw-text-sm tw-text-slate-400 tw-italic">Aucun paramètre disponible</td>
        </tr>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
        <tr>
            <td colspan="2" class="tw-px-0 tw-pt-6 tw-pb-1">
                <a href="<?php echo e(route('compte_rendu_bloc.index', $patient->id)); ?>" class="tw-no-underline">
                    <span class="tw-inline-flex tw-items-center tw-gap-2 tw-text-base tw-font-bold tw-text-[#1D4ED8] tw-uppercase tw-tracking-wide">
                        <i class="fas fa-file-medical-alt"></i> Compte-Rendu Opératoire
                    </span>
                </a>
            </td>
        </tr>

        <?php if(count($patient->compte_rendu_bloc_operatoires) && $compte_rendu_bloc_operatoires): ?>
            <tr class="tw-bg-slate-50">
                <td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500 tw-uppercase tw-w-48">Date</td>
                <td class="tw-px-3 tw-py-2 tw-font-semibold tw-text-slate-700 tw-text-sm">
                    <?php echo e($compte_rendu_bloc_operatoires->created_at?->format('d/m/Y') ?? '—'); ?>

                    <a href="<?php echo e(route('compte_rendu_bloc.edit', $patient->id)); ?>"
                       class="tw-float-right tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-[#BFDBFE] hover:tw-bg-[#93c5fd] tw-text-[#1D4ED8] tw-text-xs tw-font-medium tw-px-3 tw-py-1 tw-no-underline tw-transition-colors">
                        <i class="fas fa-edit tw-text-[10px]"></i> Éditer
                    </a>
                </td>
            </tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Chirurgien</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->chirurgien ?? 'Non renseigné'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Date de l'intervention</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->date_intervention ?? 'Non définie'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Durée de l'intervention</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->dure_intervention ?? 'Non renseignée'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Compte-rendu opératoire</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->compte_rendu_o ?? ''); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Résultats histo-pathologiques</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->resultat_histo ?? 'Non disponibles'); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Suites opératoires</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->suite_operatoire ?? ''); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Traitement proposé</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->traitement_propose ?? ''); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Soins et examens à réaliser</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->soins ?? ''); ?></td></tr>
            <tr><td class="tw-px-3 tw-py-2 tw-text-xs tw-font-semibold tw-text-slate-500">Conclusion</td><td class="tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700"><?php echo e($compte_rendu_bloc_operatoires->conclusion ?? ''); ?></td></tr>

            <tr>
                <td class="tw-px-3 tw-py-3">
                    <?php if(count($patient->consultations)): ?>
                    <a href="<?php echo e(route('compte_rendu_bloc.create', $patient->id)); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                        <i class="far fa-plus-square tw-text-xs"></i> Nouveau CRO
                    </a>
                    <?php else: ?>
                        <span class="tw-text-sm tw-text-slate-400 tw-italic">Complétez d'abord la consultation</span>
                    <?php endif; ?>
                </td>
                <td class="tw-px-3 tw-py-3">
                    <?php if(count($patient->compte_rendu_bloc_operatoires)): ?>
                    <a href="<?php echo e(route('compte_rendu_bloc_pdf.pdf', $patient->id)); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-teal-50 hover:tw-bg-teal-100 tw-text-teal-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                        <i class="fas fa-print tw-text-xs"></i> Imprimer le CRO
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td class="tw-px-3 tw-py-3" colspan="2">
                    <?php if(count($patient->consultations)): ?>
                    <a href="<?php echo e(route('compte_rendu_bloc.create', $patient->id)); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                        <i class="far fa-plus-square tw-text-xs"></i> Nouveau CRO
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>

    <?php else: ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
            <tr>
                <td class="tw-px-3 tw-py-3" colspan="2">
                    <a href="<?php echo e(route('consultations.create', $patient->id)); ?>"
                       class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-xl tw-bg-red-50 hover:tw-bg-red-100 tw-text-red-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-no-underline tw-transition-colors">
                        <i class="fas fa-book tw-text-xs"></i> Nouvelle consultation
                    </a>
                </td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>

<?php endif; ?>
</tbody><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/admin/consultations/show_consultation.blade.php ENDPATH**/ ?>