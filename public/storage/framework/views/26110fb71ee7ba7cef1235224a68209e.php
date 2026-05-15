<tbody id="show_consultation" style="display: contents;">
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('med_inf_anes', \App\Models\Patient::class)): ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
        <tr></tr>
        <tr>
            <td>
                <a href="<?php echo e(route('consultations.index', $patient->id)); ?>">
                    <h1 class="text-primary">CONSULTATION</h1>
                </a>
            </td>
            <td></td>
        </tr>

        <?php if(count($patient->consultations) && $consultations): ?>

            <tr>
                <td class="table-active"><b>DATE :</b></td>
                <td class="table-active"><b><?php echo e($consultations->created_at?->format('d/m/Y') ?? '—'); ?></b><a href="<?php echo e(route('consultations.edit', $patient->id )); ?>" class="btn btn-primary float-end"><i class="fas fa-eye"></i> Editer</a></td>
            </tr>
            <tr>
                <td>
                    <b>NOM et PRENOM du MEDECIN :</b>
                </td>
                <td><?php echo e($consultations->medecin_r ?? 'Non renseigné'); ?></td>
            </tr>
            <tr>
                <td>
                    <b>MOTIF DE LA CONSULTATION :</b>
                </td>
                <td><?php echo e($consultations->motif_c ?? 'Non renseigné'); ?></td>
            </tr>
            <tr>
                <td><b>ALLERGIES :</b></td>
                <td><?php echo nl2br(e($consultations->allergie ?? '')); ?></td>
            </tr>
            <tr>
                <td><b>GROUPE SANGUIN :</b></td>
                <td>
                    <span class="badge badge-primary"><?php echo e($consultations->groupe ?? 'Non défini'); ?></span>
                </td>
            </tr>
            <tr>
                <td><b>ANTECEDENTS MEDICAUX :</b></td>
                <td><?php echo nl2br(e($consultations->antecedent_m ?? '')); ?></td>
            </tr>
            <tr>
                <td><b>ANTECEDENTS CHIRURGICAUX :</b></td>
                <td><?php echo nl2br(e($consultations->antecedent_c ?? '')); ?></td>
            </tr>
            <tr>
                <td><b>INTERROGATOIRE :</b></td>
                <td><?php echo nl2br(e($consultations->interrogatoire ?? '')); ?></td>
            </tr>
            <tr>
                <td><b>DIAGNOSTIC :</b></td>
                <td><?php echo nl2br(e($consultations->diagnostic ?? '')); ?></td>
            </tr>
            <tr>
                <td><b>PROPOSITION THERAPEUTIQUE :</b></td>
                <td><?php echo nl2br(e($consultations->proposition_therapeutique ?? '')); ?></td>
            </tr>
            <tr>
                <td><b>PROPOSITION DE SUIVI :</b></td>
                <td><?php echo nl2br(e($consultations->proposition ?? '')); ?></td>
            </tr>
        <?php else: ?>
        <tr>
            <td>
                <b>Aucune consultation de disponible</b>
            </td>
            <td></td>
        </tr>
        <?php endif; ?>

        <tr>
            <td>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                <a class="btn btn-danger" href="<?php echo e(route('consultations.create', $patient->id)); ?>" title="Nouvelle consultation du patient">
                    <i class="fas fa-book"></i> Nouvelle consultation
                </a>
                <?php endif; ?>
            </td>
            <?php if(count($patient->consultations)): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
                    <td>
                        <a class="btn btn-success" title="Consultation de suivi" href="<?php echo e(route('consultationsdesuivi.create', $patient->id)); ?>">
                            <i class="fa-solid fa-book-medical"></i>Consultation de suivi
                        </a>
                    </td>
                <?php endif; ?>
            <?php endif; ?>
            <td></td>
        </tr>

    <?php endif; ?>











    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
        <tr>
            <td>
                <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>">
                    <h1 class="text-primary">CONSULTATION ANESTHESIE</h1>
                </a>
            </td>
            <td>&#xA0;</td>
            <!-- <td>
                
                <?php if($dossiers): ?>
                    <a class="btn btn-danger" href="<?php echo e(route('consultations.create', $patient->id)); ?>" title="Nouvelle consultation anesthésique du patient">
                        <i class="fas fa-book"></i> Nouvelle Consultation Anesthésiste
                    </a>
                <?php endif; ?>
                <?php if(count($patient->consultation_anesthesistes)): ?>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#VisiteAnesthesiste" title="Visite pré-anesthésique du patient" data-whatever="@mdo">
                        <i class="far fa-plus-square"></i>
                        Visite pré-anesthésique
                    </button>
                <?php endif; ?>
            </td> -->
        </tr>

        <?php if(count($patient->consultation_anesthesistes) && $consultation_anesthesistes): ?>

            <tr>
                <td class="table-active"><b>DATE :</b></td>
                <td class="table-active"><b><?php echo e($consultation_anesthesistes->created_at?->format('d/m/Y') ?? '—'); ?></b> <a href="<?php echo e(route('consultations.edit', $patient->id)); ?>" class="btn btn-primary float-end"><i class="fas fa-eye"></i> Editer</a></td>
            </tr>
            <tr>
                <td><b>SERVICE :</b></td>
                <td><?php echo e($consultation_anesthesistes->service ?? 'Non renseigné'); ?></td>
            </tr>
            <tr>
                <td>
                    <b>SPECIALITE :</b>
                </td>
                <td><?php echo e($consultation_anesthesistes->specialite ?? 'Non renseignée'); ?></td>
            </tr>
            <tr>
                <td>
                    <b>MEDECIN TRAITANT :</b>
                </td>
                <td><?php echo e($consultation_anesthesistes->medecin_traitant ?? 'Non renseigné'); ?></td>
            </tr>
            <tr>
                <td><b>OPERATEUR :</b></td>
                <td><?php echo e($consultation_anesthesistes->operateur ?? 'Non renseigné'); ?></td>
            </tr>
            <tr>
                <td><b>DATE D'INTERVENTION :</b></td>
                <td>
                    <span class="badge badge-primary"><?php echo e($consultation_anesthesistes->date_intervention ?? 'Non définie'); ?></span>
                </td>
            </tr>
            <tr>
                <td><b>MOTIF D'ADMISSION :</b></td>
                <td><?php echo e($consultation_anesthesistes->motif_admission ?? 'Non renseigné'); ?></td>
            </tr>
            <tr>
                <td><b>MEMO :</b></td>
                <td><?php echo e($consultation_anesthesistes->memo ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>ANESTHESIE EN SALLE :</b></td>
                <td><?php echo e($consultation_anesthesistes->anesthesi_salle ?? 'Non renseignée'); ?></td>
            </tr>
            <tr>
                <td><b>DATE D'HOSPITALISATION :</b></td>
                <td><?php echo e($consultation_anesthesistes->date_hospitalisation ?? 'Non définie'); ?></td>
            </tr>
            <tr>
                <td><b>CLASSE ASA :</b></td>
                <td><?php echo e($consultation_anesthesistes->classe_asa ?? 'Non définie'); ?></td>
            </tr>
            <tr>
                <td><b>ANTECEDENTS / TRAITEMENT :</b></td>
                <td><?php echo e($consultation_anesthesistes->antecedent_traitement ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>EXAMENS CLINIQUES :</b></td>
                <td><?php echo e($consultation_anesthesistes->examen_clinique ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>ALLERGIE :</b></td> 
                <td>
                    <span class="badge badge-primary"><?php echo e($consultation_anesthesistes->allergie ?? 'Aucune'); ?></span>
                </td>
            </tr>
            <tr>
                <td><b>Intubation :</b></td>
                <td><?php echo e($consultation_anesthesistes->intubation ?? 'Non renseignée'); ?></td>
            </tr>
            <tr>
                <td><b>Mallampati :</b></td>
                <td><?php echo e($consultation_anesthesistes->mallampati ?? 'Non évalué'); ?></td>
            </tr>
            <tr>
                <td><b>Distance-interincisive :</b></td>
                <td><?php echo e($consultation_anesthesistes->distance_interincisive ?? 'Non mesurée'); ?></td>
            </tr>
            <tr>
                <td><b>Distance thyromentoniére :</b></td>
                <td><?php echo e($consultation_anesthesistes->distance_thyromentoniere ?? 'Non mesurée'); ?></td>
            </tr>
            <tr>
                <td><b>Mobilité cervicale :</b></td>
                <td><?php echo e($consultation_anesthesistes->mobilite_servicale ?? 'Non évaluée'); ?></td>
            </tr>
            <tr>
                <td><b>Technique d'anesthésie :</b></td>
                <td><?php echo e($consultation_anesthesistes->technique_anesthesie1 ?? 'Non définie'); ?></td>
            </tr>
            <tr>
                <td><b>Bénéfice / Risque  :</b></td>
                <td><?php echo e($consultation_anesthesistes->benefice_risque ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>Jeune préopératoire :</b></td>
                <td>
                    <p><b>Solide :</b></p>
                    <p><?php echo e($consultation_anesthesistes->solide ?? 'Non renseigné'); ?></p>
                    <p><b>Liquide :</b></p>
                    <p><?php echo e($consultation_anesthesistes->liquide ?? 'Non renseigné'); ?></p>
                </td>
            </tr>
            <tr>
                <td><b>Adaptation au traitement personnel :</b></td>
                <td><?php echo e($consultation_anesthesistes->adaptation_traitement ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>Autres adaptations :</b></td>
                <td><?php echo e($consultation_anesthesistes->autre1 ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>Technique d'anesthésie envisagée :</b></td>
                <td>
                    <?php if($consultation_anesthesistes->technique_anesthesie): ?>
                        <?php $__currentLoopData = explode(",", $consultation_anesthesistes->technique_anesthesie); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $technique): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <ul>
                                <li><?php echo e(trim($technique)); ?></li>
                            </ul>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        Non renseignée
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><b>Antibioprophylaxie  :</b></td>
                <td><?php echo e($consultation_anesthesistes->antibiotique ?? 'Non prescrite'); ?></td>
            </tr>
            <tr>
                <td><b>Synthèse pré-opératoire :</b></td>
                <td><?php echo e($consultation_anesthesistes->synthese_preop ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>Examens paracliniques :</b></td>
                <td>
                    <?php if($consultation_anesthesistes->examen_paraclinique): ?>
                        <?php $__currentLoopData = explode(",", $consultation_anesthesistes->examen_paraclinique); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <ul>
                                <li><?php echo e(trim($examen)); ?></li>
                            </ul>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        Aucun examen prescrit
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo e(route('consultations.index_anesthesiste', $patient->id)); ?>">
                        <h1 class="text-info">VPA / ELEMENTS NOUVEAUX</h1>
                    </a>
                </td>
                <td>
                    <?php if(count($patient->visite_preanesthesiques)): ?>
                        <a class="btn btn-info" title="Imprimer le consentement éclairé" href="<?php echo e(route('consentement_eclaire.pdf', $patient->id)); ?>">
                            <i class="far fa-check-circle"></i> Consentement éclairé
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php if(count($patient->visite_preanesthesiques) && $visite_anesthesistes): ?>

                <tr>
                    <td class="table-active"><b>DATE :</b></td>
                    <td class="table-active"><b><?php echo e($visite_anesthesistes->date_visite ?? '—'); ?></b> 
                    <!-- <button class="btn btn-primary float-end"><i class="fas fa-eye"></i> Editer</button></td> -->
                </tr>
                <tr>
                    <td><b>Eléments nouveaux :</b></td>
                    <td><?php echo e($visite_anesthesistes->element_nouveaux ?? 'Aucun'); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td><b>Aucune visite pré-anesthésique disponible :</b></td>
                    <td></td>
                </tr>
            <?php endif; ?>


        <?php else: ?>
            <tr>
                <td>
                    <b>Aucune consultation de disponible</b>
                </td>
                <td></td>
            </tr>
        <?php endif; ?>

        <tr>
            <td>
                
                <?php if(count($patient->dossiers)): ?>
                    <a class="btn btn-danger" href="<?php echo e(route('consultations.create', $patient->id)); ?>" title="Nouvelle consultation anesthésique">
                        <i class="fas fa-book"></i> Nouvelle Consultation Anesthésiste
                    </a>
                <?php else: ?>
                    <a class="btn btn-secondary disabled" href="#" data-bs-placement="top" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-content="Vous devez d'abord compléter le dossier patient !" title="Consultation anesthésiste">
                        <i class="fas fa-book"></i> Nouvelle Consultation Anesthésiste
                    </a>
                <?php endif; ?>
            </td>
            <td>
                
                <?php if(count($patient->consultation_anesthesistes)): ?>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#VisiteAnesthesiste" title="Visite pré-anesthésique du patient" data-whatever="@mdo">
                        <i class="far fa-plus-square"></i>
                        Visite Pré-anesthésique
                    </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endif; ?>






    <tr>
        <td>
            <a href="<?php echo e(route('fiche_parametre.index', $patient->id)); ?>">
                <h1 class="text-primary">PARAMETRES</h1>
            </a>
        </td>

        <td></td>
    </tr>

    <?php if(count($patient->parametres) > 0 && $parametres): ?>

        <tr>
            <td class="table-active"><b>DATE :</b></td>
            <td class="table-active"><b><?php echo e($parametres->created_at?->format('d/m/Y') ?? '—'); ?></b> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('infirmier', \App\Models\Patient::class)): ?><a href="<?php echo e(route('consultations.edit', $patient->id)); ?>" class="btn btn-primary float-end"><i class="fas fa-eye"></i> Editer</a><?php endif; ?></td>
        </tr>
        <tr>
            <td><b>DATE DE NAISSANCE :</b></td>
            <td><?php echo e($parametres->date_naissance ?? 'Non renseignée'); ?></td>
        </tr>
        <tr>
            <td><b>AGE :</b></td>
            <td><?php echo e($parametres->age ?? '—'); ?> Ans</td>
        </tr>
        <tr>
            <td><b>POIDS :</b></td>
            <td><?php echo e($parametres->poids ?? '—'); ?> Kg</td>
        </tr>
        <tr>
            <td><b>TAILLE :</b></td>
            <td><?php echo e($parametres->taille ?? '—'); ?> M</td>
        </tr>
        <tr>
            <td><b>TEMPERATURE :</b></td>
            <td><?php echo e($parametres->temperature ?? '—'); ?> °C</td>
        </tr>
        <tr>
            <td><b>GLYCEMIE :</b></td>
            <td><?php echo e($parametres->glycemie ?? '—'); ?> g/l</td>
        </tr>
        <tr>
            <td><b>SPO2 :</b></td>
            <td><?php echo e($parametres->spo2 ?? '—'); ?> %</td>
        </tr>
        <tr>
            <td><b>IMC / BMI :</b></td>
            <td><?php echo e($parametres->inc_bmi ?? 'Non calculé'); ?></td>
        </tr>
        <tr>
            <td><b>TA :</b></td>
            <td>
                <b>Bg :</b> <?php echo e($parametres->bras_gauche ?? '—'); ?> mmHg
                <br>
                <b>Bd :</b> <?php echo e($parametres->bras_droit ?? '—'); ?> mmHg
            </td>
        </tr>
        <tr>
            <td><b>FR :</b></td>
            <td><?php echo e($parametres->fr ?? '—'); ?> Mvts/min</td>
        </tr>
        <tr>
            <td><b>FC :</b></td>
            <td><?php echo e($parametres->fc ?? '—'); ?> Pls/min</td>
        </tr>
    <?php else: ?>

        <tr>
            <td>
                <b>Aucun paramètre de disponible</b>
            </td>
            <td></td>
        </tr>
    <?php endif; ?>

    

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
        <tr>
            <td>
                <a href="<?php echo e(route('compte_rendu_bloc.index', $patient->id)); ?>">
                    <h1 class="text-primary text-center">COMPTE-RENDU</h1>
                </a>
            </td>
            <td></td>
        </tr>

        <?php if(count($patient->compte_rendu_bloc_operatoires) && $compte_rendu_bloc_operatoires): ?>
            <tr>
                <td class="table-active"><b>DATE :</b></td>
                <td class="table-active"><b><?php echo e($compte_rendu_bloc_operatoires->created_at?->format('d/m/Y') ?? '—'); ?></b> <a href="<?php echo e(route('compte_rendu_bloc.edit', $patient->id)); ?>" title="Modifier le compte-rendu" class="btn btn-primary float-end"><i class="fas fa-eye"></i> Editer</a></td>
            </tr>
            <tr>
                <td><b>NOM ET PRENOM DU CHIRURGIEN :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->chirurgien ?? 'Non renseigné'); ?></td>
            </tr>
            <tr>
                <td><b>DATE DE L'INTERVENTION :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->date_intervention ?? 'Non définie'); ?></td>
            </tr>
            <tr>
                <td><b>DUREE DE L'INTERVENTION :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->dure_intervention ?? 'Non renseignée'); ?></td>
            </tr>
            <tr>
                <td><b>COMPTE-RENDU OPERATOIRE :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->compte_rendu_o ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>RESULTATS HISTO-PATHOLOGIQUES :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->resultat_histo ?? 'Non disponibles'); ?></td>
            </tr>
            <tr>
                <td><b>SUITES OPERATOIRES :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->suite_operatoire ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>TRAITEMENT PROPOSE :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->traitement_propose ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>SOINS ET EXAMENS A REALISER :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->soins ?? ''); ?></td>
            </tr>
            <tr>
                <td><b>CONCLUSION :</b></td>
                <td><?php echo e($compte_rendu_bloc_operatoires->conclusion ?? ''); ?></td>
            </tr>
            <tr>
                <td>
                    <?php if(count($patient->consultations)): ?>
                    <a href="<?php echo e(route('compte_rendu_bloc.create', $patient->id)); ?>" title="Rédiger un compte-rendu opératoire" class="btn btn-danger">
                        <i class="far fa-plus-square"></i> Nouveau CRO
                    </a>
                    <?php else: ?>
                        <span class="text-muted">Complétez d'abord la consultation</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(count($patient->compte_rendu_bloc_operatoires)): ?>
                        <a class="btn btn-success" title="Imprimer le compte-rendu opératoire" href="<?php echo e(route('compte_rendu_bloc_pdf.pdf', $patient->id)); ?>">
                            <i class="fas fa-print"></i> Imprimer le CRO
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td>
                    <?php if(count($patient->consultations)): ?>
                    <a href="<?php echo e(route('compte_rendu_bloc.create', $patient->id)); ?>" title="Rédiger un compte-rendu opératoire" class="btn btn-danger">
                        <i class="far fa-plus-square"></i> Nouveau CRO
                    </a>
                    <?php endif; ?>
                </td>
                <td></td>
            </tr>
        <?php endif; ?>
    <?php else: ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chirurgien', \App\Models\Patient::class)): ?>
            <tr>
                <td>
                    <a class="btn btn-danger" href="<?php echo e(route('consultations.create', $patient->id)); ?>"
                        title="Nouvelle consultation du patient">
                        <i class="fas fa-book"></i> Nouvelle consultation
                    </a>
                </td>
                <td></td>
            </tr>
        <?php endif; ?>

    <?php endif; ?>


<?php endif; ?>

</tbody>

















<?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/consultations/show_consultation.blade.php ENDPATH**/ ?>