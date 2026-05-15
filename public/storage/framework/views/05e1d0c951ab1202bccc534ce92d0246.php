
<aside id="sidebar"
       class="tw-flex tw-flex-col tw-h-screen tw-w-64 tw-min-w-[16rem] tw-bg-[#1e40af] tw-text-slate-200 tw-transition-all tw-duration-300 tw-overflow-y-auto tw-overflow-x-hidden tw-sticky tw-top-0 tw-z-50 sidebar-scroll">

    
    <div class="tw-flex tw-items-center tw-gap-3 tw-px-5 tw-py-5 tw-bg-[#1D4ED8] tw-shrink-0">
        <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white tw-flex tw-items-center tw-justify-center tw-shadow-lg">
            <i class="fas fa-hospital tw-text-[#1D4ED8] tw-text-lg"></i>
        </div>
        <div>
            <span class="tw-font-bold tw-text-white tw-text-base tw-tracking-wide tw-leading-none">CMCU</span>
            <p class="tw-text-[#BFDBFE] tw-text-[10px] tw-mt-0.5 tw-leading-none tw-mb-0">Portail Médical</p>
        </div>
    </div>

    
    <div class="tw-flex tw-items-center tw-gap-3 tw-px-4 tw-py-3 tw-bg-[#1a3a7a] tw-border-b tw-border-white/10 tw-shrink-0">
        <img src="<?php echo e(asset('admin/images/logo.jpg')); ?>"
             class="tw-w-9 tw-h-9 tw-rounded-full tw-object-cover tw-ring-2 tw-ring-[#14B8A6]/50"
             alt="Profile">
        <div class="tw-min-w-0">
            <p class="tw-text-xs tw-font-semibold tw-text-white tw-truncate tw-mb-0"><?php echo e(Auth::user()->name ?? 'Utilisateur'); ?></p>
            <p class="tw-text-[10px] tw-text-[#BFDBFE] tw-truncate tw-mb-0"><?php echo e(Auth::user()->role->name ?? ''); ?></p>
        </div>
    </div>

    
    <nav class="tw-flex-1 tw-py-3">
        <ul class="list-unstyled tw-mb-0 tw-space-y-0.5 tw-px-2">

            
            <li class="tw-px-2 tw-pt-1 tw-pb-1">
                <span class="tw-text-[10px] tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold">Principal</span>
            </li>

            
            <li>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('admin.dashboard') ? 'tw-bg-[#1D4ED8] tw-text-white tw-shadow-md' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center <?php echo e(request()->routeIs('admin.dashboard') ? 'tw-bg-white/20' : 'tw-bg-white/10'); ?>">
                        <i class="fas fa-tachometer-alt tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span>Tableau de bord</span>
                </a>
            </li>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['update', 'changeOwner'], \App\Models\User::class)): ?>
            <li>
                <a href="#usersSubmenu" data-bs-toggle="collapse"
                   aria-expanded="<?php echo e(request()->routeIs('users.*', 'roles.*') ? 'true' : 'false'); ?>"
                   class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('users.*', 'roles.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center <?php echo e(request()->routeIs('users.*', 'roles.*') ? 'tw-bg-white/20' : 'tw-bg-white/10'); ?>">
                        <i class="fas fa-user-friends tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span class="tw-flex-1">Utilisateurs</span>
                    <i class="fas fa-chevron-down tw-text-xs tw-transition-transform tw-duration-200 chevron <?php echo e(request()->routeIs('users.*', 'roles.*') ? 'tw-rotate-180' : ''); ?>"></i>
                </a>
                <ul class="collapse list-unstyled tw-mt-0.5 tw-space-y-0.5 tw-pl-3 <?php echo e(request()->routeIs('users.*', 'roles.*') ? 'show' : ''); ?>" id="usersSubmenu">
                    <li><a href="<?php echo e(route('users.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('users.index') ? 'tw-text-white tw-bg-white/10' : ''); ?>"><i class="fas fa-address-book tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Liste des utilisateurs</a></li>
                    <li><a href="<?php echo e(route('roles.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('roles.*') ? 'tw-text-white tw-bg-white/10' : ''); ?>"><i class="fas fa-user-shield tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Rôles</a></li>
                </ul>
            </li>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Patient::class)): ?>
            <li>
                <a href="#patientsSubmenu" data-bs-toggle="collapse"
                   aria-expanded="<?php echo e(request()->routeIs('patients.*') ? 'true' : 'false'); ?>"
                   class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('patients.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center <?php echo e(request()->routeIs('patients.*') ? 'tw-bg-white/20' : 'tw-bg-white/10'); ?>">
                        <i class="fas fa-user-injured tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span class="tw-flex-1">Patients</span>
                    <i class="fas fa-chevron-down tw-text-xs tw-transition-transform tw-duration-200 chevron <?php echo e(request()->routeIs('patients.*') ? 'tw-rotate-180' : ''); ?>"></i>
                </a>
                <ul class="collapse list-unstyled tw-mt-0.5 tw-space-y-0.5 tw-pl-3 <?php echo e(request()->routeIs('patients.*') ? 'show' : ''); ?>" id="patientsSubmenu">
                    <li><a href="<?php echo e(route('patients.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-clipboard-list tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Liste des patients</a></li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewVisitsList', \App\Models\Patient::class)): ?>
                    <li><a href="<?php echo e(route('patient-visits.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-history tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Visites patients</a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                    <li><a href="<?php echo e(route('patients.suivis')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-user-check tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Mes patients suivis</a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                    <li><a href="<?php echo e(route('produits.index', ['categorie' => 'ANESTHESISTE'])); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-syringe tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Produits anesthésiste</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Produit::class)): ?>
            <li class="tw-pt-2">
                <span class="tw-px-2 tw-text-[10px] tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold">Stock & Logistique</span>
            </li>
            <li class="tw-mt-1">
                <a href="#inventorySubmenu" data-bs-toggle="collapse"
                   aria-expanded="<?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'true' : 'false'); ?>"
                   class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center <?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'tw-bg-white/20' : 'tw-bg-white/10'); ?>">
                        <i class="fas fa-warehouse tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span class="tw-flex-1">Gestion des Stocks</span>
                    <i class="fas fa-chevron-down tw-text-xs tw-transition-transform tw-duration-200 chevron <?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'tw-rotate-180' : ''); ?>"></i>
                </a>
                <ul class="collapse list-unstyled tw-mt-0.5 tw-space-y-0.5 tw-pl-3 <?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'show' : ''); ?>" id="inventorySubmenu">
                    <?php if(in_array(auth()->user()->role_id, [1, 3, 5])): ?>
                    <li>
                        <a href="<?php echo e(route('bon-commandes.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white">
                            <i class="fas fa-file-invoice tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            <span class="tw-flex-1">Bons de Commande</span>
                            <?php if(auth()->user()->role_id === 3 && ($pendingBonsCount = cache()->remember('pending_bons_count', 300, fn() => \App\Models\BonCommande::where('statut', 'envoye')->count())) > 0): ?>
                            <span class="badge bg-warning tw-text-dark tw-text-[10px] tw-rounded-full tw-px-1.5"><?php echo e($pendingBonsCount); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li><a href="<?php echo e(route('produits.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-boxes tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Tous les Produits</a></li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approveEditRequests', \App\Models\Produit::class)): ?>
                    <li>
                        <a href="<?php echo e(route('produits.edit-permissions.pending')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white">
                            <i class="fas fa-edit tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            <span class="tw-flex-1">Modifs en attente</span>
                            <?php if(($editRequestsCount = cache()->remember('pending_edit_requests', 300, fn() => \App\Models\ProduitEditRequest::where('status', 'pending')->count())) > 0): ?>
                            <span class="badge bg-info tw-text-[10px] tw-rounded-full tw-px-1.5"><?php echo e($editRequestsCount); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('verifyStock', \App\Models\Produit::class)): ?>
                    <li><a href="<?php echo e(route('produits.stock.verification')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-clipboard-check tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Vérification des stocks</a></li>
                    <?php endif; ?>
                    <?php if(in_array(auth()->user()->role_id, [1, 3, 5])): ?>
                    <li>
                        <a href="<?php echo e(route('stock-receptions.ready')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-dolly tw-w-4 tw-text-center tw-text-[#ccfbf1]">

                        </i> Commandes à Réceptionner</a>
                    </li>
                    <li><a href="<?php echo e(route('stock-receptions.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-clipboard-list tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Historique Réceptions</a></li>
                    <?php endif; ?>
                    <?php if(in_array(auth()->user()->role_id, [1, 4, 7])): ?>
                    <li>
                        <a href="<?php echo e(route('reusable-products.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white">
                            <i class="fas fa-recycle tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            <span class="tw-flex-1">Produits Réutilisables</span>
                            <?php if(($pendingUsages = cache()->remember('pending_usages', 300, fn() => \App\Models\ProductUsage::where('statut_retour', 'en_attente')->where('quantite_retournable', '>', 0)->count())) > 0): ?>
                            <span class="badge bg-danger tw-text-[10px] tw-rounded-full tw-px-1.5"><?php echo e($pendingUsages); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAuditLogs', \App\Models\Produit::class)): ?>
                    <li><a href="<?php echo e(route('produits.audit-logs')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-history tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Historique & Traçabilité</a></li>
                    <?php endif; ?>
                </ul>
            </li>

            <?php if(auth()->user()->role_id === 3): ?>
            <li>
                <a href="<?php echo e(route('bon-commandes.validation')); ?>" class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('bon-commandes.validation') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-bg-white/10">
                        <i class="fas fa-clipboard-check tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span class="tw-flex-1">Valider Commandes</span>
                    <?php if(($pendingValidation = cache()->remember('pending_validation', 300, fn() => \App\Models\BonCommande::where('statut', 'envoye')->count())) > 0): ?>
                    <span class="badge bg-warning tw-text-dark tw-text-[10px]"><?php echo e($pendingValidation); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            
            <?php if(in_array(auth()->user()->role_id, [1, 3, 6, 7, 9])): ?>
            <li class="tw-pt-2">
                <span class="tw-px-2 tw-text-[10px] tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold">Pharmacie</span>
            </li>
            <li class="tw-mt-1">
                <a href="#pharmacySubmenu" data-bs-toggle="collapse"
                   aria-expanded="<?php echo e(request()->routeIs('pharmacie.*') ? 'true' : 'false'); ?>"
                   class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('pharmacie.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center <?php echo e(request()->routeIs('pharmacie.*') ? 'tw-bg-white/20' : 'tw-bg-white/10'); ?>">
                        <i class="fas fa-clinic-medical tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span class="tw-flex-1">Pharmacie</span>
                    <i class="fas fa-chevron-down tw-text-xs tw-transition-transform tw-duration-200 chevron <?php echo e(request()->routeIs('pharmacie.*') ? 'tw-rotate-180' : ''); ?>"></i>
                </a>
                <ul class="collapse list-unstyled tw-mt-0.5 tw-space-y-0.5 tw-pl-3 <?php echo e(request()->routeIs('pharmacie.*') ? 'show' : ''); ?>" id="pharmacySubmenu">
                    <?php if(in_array(auth()->user()->role_id, [1, 7])): ?>
                    <li><a href="<?php echo e(route('pharmacie.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-prescription-bottle-alt tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Pharmacie</a></li>
                    <?php endif; ?>
                    <?php if(in_array(auth()->user()->role_id, [1, 3, 6, 7])): ?>
                    <li>
                        <a href="<?php echo e(route('pharmacie.sales.list', ['statut_paiement' => 'en_attente'])); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white">
                            <i class="fas fa-cash-register tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            <span class="tw-flex-1">Paiements en Attente</span>
                            <?php if(($pendingPayments = cache()->remember('pending_pharma_payments', 300, fn() => \App\Models\VentePharmacie::where('statut_paiement', 'en_attente')->count())) > 0): ?>
                            <span class="badge bg-warning tw-text-[10px]"><?php echo e($pendingPayments); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(auth()->user()->role_id, [1, 3, 7, 9])): ?>
                    <li><a href="<?php echo e(route('pharmacie.history')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 
                    tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white">
                    <i class="fas fa-chart-line tw-w-4 tw-text-center tw-text-[#ccfbf1]">

                    </i> Historique Pharmacie</a></li>
                    <?php endif; ?>
                    <?php if(in_array(auth()->user()->role_id, [1, 3, 7])): ?>
                    <li><a href="<?php echo e(route('pharmacie.sales.external.create')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-hospital tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Vente Externe</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            
            <li class="tw-pt-2">
                <span class="tw-px-2 tw-text-[10px] tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold">Gestion Clinique</span>
            </li>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\chambre::class)): ?>
            <li class="tw-mt-1">
                <a href="<?php echo e(route('chambres.index')); ?>" class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('chambres.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-bg-white/10"><i class="fas fa-procedures tw-text-[#ccfbf1] tw-text-xs"></i></span>
                    Chambres
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\Event::class)): ?>
            <li>
                <a href="<?php echo e(route('events.index')); ?>" class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('events.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-bg-white/10"><i class="fas fa-calendar-check tw-text-[#ccfbf1] tw-text-xs"></i></span>
                    Rendez-vous
                </a>
            </li>
            <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\User::class)): ?>
            <li class="tw-pt-2">
                <span class="tw-px-2 tw-text-[10px] tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold">
                    Facturation
                </span>
            </li>
            <li class="tw-mt-1">
                
                <a href="#facturationSubmenu"
                data-bs-toggle="collapse"
                aria-expanded="<?php echo e(request()->routeIs('facturation.*') ? 'true' : 'false'); ?>"
                class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('facturation.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center <?php echo e(request()->routeIs('facturation.*') ? 'tw-bg-white/20' : 'tw-bg-white/10'); ?>">
                        <i class="fas fa-file-invoice-dollar tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span class="tw-flex-1">Facturation</span>
                    <i class="fas fa-chevron-down tw-text-xs tw-transition-transform tw-duration-200 chevron <?php echo e(request()->routeIs('facturation.*') ? 'tw-rotate-180' : ''); ?>"></i>
                </a>

                
                <ul class="collapse list-unstyled tw-mt-0.5 tw-space-y-0.5 tw-pl-3 <?php echo e(request()->routeIs('facturation.*') ? 'show' : ''); ?>"
                    id="facturationSubmenu">

                    
                    <li>
                        <a href="<?php echo e(route('facturation.dashboard')); ?>"
                        class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('facturation.dashboard') ? 'tw-text-white tw-bg-white/10' : ''); ?>">
                            <i class="fas fa-tachometer-alt tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            Vue d'ensemble
                        </a>
                    </li>

                    
                    <li>
                        <a href="<?php echo e(route('factures.consultation')); ?>"
                        class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('factures.consultation*') ? 'tw-text-white tw-bg-white/10' : ''); ?>">
                            <i class="fas fa-stethoscope tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            Consultations
                        </a>
                    </li>

                    
                    <li>
                        <a href="<?php echo e(route('facturation.examens.index')); ?>"
                        class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('facturation.examens.*') ? 'tw-text-white tw-bg-white/10' : ''); ?>">
                            <i class="fas fa-flask tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            Examens
                        </a>
                    </li>

                    
                    <li>
                        <a href="<?php echo e(route('facturation.actes.index')); ?>"
                        class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('facturation.actes.*') ? 'tw-text-white tw-bg-white/10' : ''); ?>">
                            <i class="fas fa-procedures tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            Actes / Soins
                        </a>
                    </li>

                    
                    <li>
                        <a href="<?php echo e(route('facturation.chambres.index')); ?>"
                        class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('facturation.chambres.*') ? 'tw-text-white tw-bg-white/10' : ''); ?>">
                            <i class="fas fa-bed tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            Chambres / Séjours
                        </a>
                    </li>

                    
                
                    <li>
                        <a href="<?php echo e(route('facture_devis.index')); ?>"
                        class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('devis.*') ? 'tw-text-white tw-bg-white/10' : ''); ?>">
                            <i class="fas fa-file-contract tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                            Devis
                        </a>
                    </li>
                 

                </ul>
            </li>
        <?php endif; ?>


        
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoire', \App\Models\Patient::class)): ?>
            <li class="tw-pt-2">
                <span class="tw-px-2 tw-text-[10px] tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold">Laboratoire</span>
            </li>
            <li class="tw-mt-1">
                <a href="#laboSubmenu" data-bs-toggle="collapse"
                   aria-expanded="<?php echo e(request()->routeIs('laboratoire.*') ? 'true' : 'false'); ?>"
                   class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('laboratoire.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center <?php echo e(request()->routeIs('laboratoire.*') ? 'tw-bg-white/20' : 'tw-bg-white/10'); ?>">
                        <i class="fas fa-flask tw-text-[#ccfbf1] tw-text-xs"></i>
                    </span>
                    <span class="tw-flex-1">Laboratoire</span>
                    <?php if(auth()->user()->role_id === 10): ?>
                        <?php
                            $labEnAttenteCount = cache()->remember('lab_en_attente_all', 30, function () {
                                return \App\Models\ExamenLaboratoire::where('statut', 'en_attente')->count();
                            });
                            $labEnCoursCount = cache()->remember('lab_en_cours_all', 60, function () {
                                return \App\Models\ExamenLaboratoire::where('statut', 'en_cours')->count();
                            });
                        ?>
                        <?php if($labEnAttenteCount > 0): ?>
                        <span class="badge tw-bg-red-500 tw-text-white tw-text-[10px] tw-rounded-full tw-px-1.5 tw-animate-pulse"><?php echo e($labEnAttenteCount); ?></span>
                        <?php elseif($labEnCoursCount > 0): ?>
                        <span class="badge tw-bg-amber-400 tw-text-dark tw-text-[10px] tw-rounded-full tw-px-1.5"><?php echo e($labEnCoursCount); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <i class="fas fa-chevron-down tw-text-xs tw-transition-transform tw-duration-200 chevron <?php echo e(request()->routeIs('laboratoire.*') ? 'tw-rotate-180' : ''); ?>"></i>
                </a>
                <ul class="collapse list-unstyled tw-mt-0.5 tw-space-y-0.5 tw-pl-3 <?php echo e(request()->routeIs('laboratoire.*') ? 'show' : ''); ?>" id="laboSubmenu">
                    <li><a href="<?php echo e(route('laboratoire.index')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('laboratoire.index') ? 'tw-text-white tw-bg-white/10' : ''); ?>"><i class="fas fa-list-ul tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Tous les examens</a></li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireCreate', \App\Models\Patient::class)): ?>
                    <li><a href="<?php echo e(route('laboratoire.create')); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white <?php echo e(request()->routeIs('laboratoire.create') ? 'tw-text-white tw-bg-white/10' : ''); ?>"><i class="fas fa-plus tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Nouveau bon</a></li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('laboratoireWrite', \App\Models\Patient::class)): ?>
                    <?php
                        $labNouveauxCount = cache()->remember('lab_nouveaux_' . auth()->id(), 30, function () {
                            return \App\Models\ExamenLaboratoire::where('statut', 'en_attente')->count();
                        });
                    ?>
                    <li>
                        <a href="<?php echo e(route('laboratoire.index', ['statut' => 'en_attente'])); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white">
                            <span class="tw-relative tw-w-4 tw-flex tw-justify-center">
                                <i class="fas fa-bell tw-text-[#ccfbf1]"></i>
                                <?php if($labNouveauxCount > 0): ?>
                                <span class="tw-absolute -tw-top-1.5 -tw-right-2 tw-bg-red-500 tw-text-white tw-text-[9px] tw-font-bold tw-rounded-full tw-w-4 tw-h-4 tw-flex tw-items-center tw-justify-center tw-leading-none"><?php echo e($labNouveauxCount > 9 ? '9+' : $labNouveauxCount); ?></span>
                                <?php endif; ?>
                            </span>
                            <span class="tw-flex-1">Nouvelles demandes</span>
                            <?php if($labNouveauxCount > 0): ?>
                            <span class="badge tw-bg-red-500 tw-text-white tw-text-[10px] tw-rounded-full tw-px-1.5"><?php echo e($labNouveauxCount); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="<?php echo e(route('laboratoire.index', ['statut' => 'en_cours'])); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-pen-to-square tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Résultats à saisir</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo e(route('laboratoire.index', ['statut' => 'valide'])); ?>" class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-text-slate-400 tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white"><i class="fas fa-check-circle tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i> Résultats validés</a></li>
                


                    
                <?php if(in_array(auth()->user()->role_id, [1, 6, 10])): ?>
                <li>
                    <a href="<?php echo e(route('tarifs_labo.index')); ?>"
                    class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white
                            <?php echo e(request()->routeIs('tarifs_labo.*') ? 'tw-text-white tw-bg-white/10' : 'tw-text-slate-400'); ?>">
                        <i class="fas fa-tag tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                        Tarifs laboratoire
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if(in_array(auth()->user()->role_id, [1, 2, 3, 10])): ?>
                <li>
                    <a href="<?php echo e(route('laboratoire.results_by_discipline')); ?>"
                    class="submenu-link tw-flex tw-items-center tw-gap-2 tw-px-3 tw-py-2 tw-rounded-lg tw-text-xs tw-no-underline tw-transition-all hover:tw-bg-white/30 hover:tw-text-white
                            <?php echo e(request()->routeIs('laboratoire.results_by_discipline') ? 'tw-text-white tw-bg-white/10' : 'tw-text-slate-400'); ?>">
                        <i class="fas fa-chart-simple tw-w-4 tw-text-center tw-text-[#ccfbf1]"></i>
                        Résultats par discipline
                    </a>
                </li>
                <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\Devi::class)): ?>
            <li>
                <a href="<?php echo e(route('devis.index')); ?>" class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('devis.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-bg-white/10"><i class="fas fa-file-contract tw-text-[#ccfbf1] tw-text-xs"></i></span>
                    Devis
                </a>
            </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\DevisElement::class)): ?>
            <li>
                <a href="<?php echo e(route('devis_elements.index')); ?>" class="sidebar-link tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2.5 tw-rounded-lg tw-text-sm tw-font-medium tw-text-slate-300 tw-no-underline tw-transition-all tw-duration-200 hover:tw-bg-[#2563eb] hover:tw-text-white <?php echo e(request()->routeIs('devis_elements.*') ? 'tw-bg-[#1D4ED8] tw-text-white' : ''); ?>">
                    <span class="tw-w-8 tw-h-8 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-bg-white/10"><i class="fas fa-list-ul tw-text-[#ccfbf1] tw-text-xs"></i></span>
                    Éléments de Devis
                </a>
            </li>
            <?php endif; ?>

        </ul>
    </nav>

    
    <div class="tw-px-4 tw-py-3 tw-border-t tw-border-white/10 tw-shrink-0">
        <span class="tw-text-[10px] tw-text-slate-500 tw-flex tw-items-center tw-gap-1.5">
            <i class="fas fa-code-branch"></i>
            CMCUAPP <?php echo e(app_version('label')); ?>

        </span>
    </div>

</aside>

<style>
.sidebar-scroll::-webkit-scrollbar { width: 4px; }
.sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 4px; }
@media (max-width: 768px) {
    #sidebar { position: fixed; margin-left: -16rem; z-index: 1000; }
    #sidebar.active { margin-left: 0; }
}
</style><?php /**PATH C:\Users\ADAM EDJABE\Desktop\cmcuapp\resources\views/partials/side_bar.blade.php ENDPATH**/ ?>