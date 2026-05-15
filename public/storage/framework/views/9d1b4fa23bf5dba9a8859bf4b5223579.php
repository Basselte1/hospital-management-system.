<!-- Sidebar Holder -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h1><span>M</span></h1>
    </div>
    <img src="<?php echo e(asset('admin/images/logo.jpg')); ?>" class="profile-bg img-fluid" style="width: 100%">

    <ul class="list-unstyled components">

        
        <li class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.dashboard')); ?>">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
        </li>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['update', 'changeOwner'], \App\Models\User::class)): ?>
        <li class="<?php echo e(request()->routeIs('users.*', 'roles.*') ? 'active' : ''); ?>">
            <a href="#usersSubmenu" data-bs-toggle="collapse"
               aria-expanded="<?php echo e(request()->routeIs('users.*', 'roles.*') ? 'true' : 'false'); ?>">
                <i class="fas fa-user-friends"></i> Utilisateurs
                <i class="fas fa-angle-down float-end chevron"></i>
            </a>
            <ul class="collapse list-unstyled <?php echo e(request()->routeIs('users.*', 'roles.*') ? 'show' : ''); ?>" id="usersSubmenu">
                <li><a href="<?php echo e(route('users.index')); ?>"><i class="fas fa-address-book"></i> Liste des utilisateurs</a></li>
                <li><a href="<?php echo e(route('roles.index')); ?>"><i class="fas fa-user-shield"></i> Rôles</a></li>
            </ul>
        </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', \App\Models\Patient::class)): ?>
        <li class="<?php echo e(request()->routeIs('patients.*') ? 'active' : ''); ?>">
            <a href="#patientsSubmenu" data-bs-toggle="collapse"
               aria-expanded="<?php echo e(request()->routeIs('patients.*') ? 'true' : 'false'); ?>">
                <i class="fas fa-user-injured"></i> Patients
                <i class="fas fa-angle-down float-end chevron"></i>
            </a>
            <ul class="collapse list-unstyled <?php echo e(request()->routeIs('patients.*') ? 'show' : ''); ?>" id="patientsSubmenu">
                <li><a href="<?php echo e(route('patients.index')); ?>"><i class="fas fa-clipboard-list"></i> Liste des patients</a></li>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewVisitsList', \App\Models\Patient::class)): ?>
                <li><a href="<?php echo e(route('patient-visits.index')); ?>"><i class="fas fa-history"></i> Visites patients</a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('medecin', \App\Models\Patient::class)): ?>
                <li><a href="<?php echo e(route('patients.suivis')); ?>"><i class="fas fa-user-check"></i> Mes patients suivis</a></li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('anesthesiste', \App\Models\Patient::class)): ?>
                <li><a href="<?php echo e(route('produits.index', ['categorie' => 'ANESTHESISTE'])); ?>"><i class="fas fa-syringe"></i> Produits anesthésiste</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        
        <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\User::class)): ?>
        <li class="<?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('clients.index')); ?>">
                <i class="fas fa-users"></i> Clients Externes
            </a>
        </li>
        <?php endif; ?> -->

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\Produit::class)): ?>
        <li class="<?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'active' : ''); ?>">
            <a href="#inventorySubmenu" data-bs-toggle="collapse"
               aria-expanded="<?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'true' : 'false'); ?>">
                <i class="fas fa-warehouse"></i> Gestion des Stocks
                <i class="fas fa-angle-down float-end chevron"></i>
            </a>
            <ul class="collapse list-unstyled <?php echo e(request()->routeIs('bon-commandes.*', 'produits.*', 'stock-receptions.*', 'reusable-products.*') ? 'show' : ''); ?>" id="inventorySubmenu">

                <?php if(in_array(auth()->user()->role_id, [1, 3, 5])): ?>
                <li>
                    <a href="<?php echo e(route('bon-commandes.index')); ?>">
                        <i class="fas fa-file-invoice"></i> Bons de Commande
                        <?php if(auth()->user()->role_id === 3 && ($pendingBonsCount = cache()->remember('pending_bons_count', 300, fn() => \App\Models\BonCommande::where('statut', 'envoye')->count())) > 0): ?>
                        <span class="badge bg-warning text-dark ms-1"><?php echo e($pendingBonsCount); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>

                <li><a href="<?php echo e(route('produits.index')); ?>"><i class="fas fa-boxes"></i> Tous les Produits</a></li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approveEditRequests', \App\Models\Produit::class)): ?>
                <li>
                    <a href="<?php echo e(route('produits.edit-permissions.pending')); ?>">
                        <i class="fas fa-edit"></i> Modifications en attente
                        <?php if(($editRequestsCount = cache()->remember('pending_edit_requests', 300, fn() => \App\Models\ProduitEditRequest::where('status', 'pending')->count())) > 0): ?>
                        <span class="badge bg-info text-dark ms-1"><?php echo e($editRequestsCount); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('verifyStock', \App\Models\Produit::class)): ?>
                <li><a href="<?php echo e(route('produits.stock.verification')); ?>"><i class="fas fa-clipboard-check"></i> Vérification des stocks</a></li>
                <?php endif; ?>

                <?php if(in_array(auth()->user()->role_id, [1, 3, 5])): ?>
                <li><a href="<?php echo e(route('stock-receptions.ready')); ?>"><i class="fas fa-dolly"></i> Commandes à Réceptionner</a></li>
                <li><a href="<?php echo e(route('stock-receptions.index')); ?>"><i class="fas fa-clipboard-list"></i> Historique des Réceptions</a></li>
                <?php endif; ?>

                <?php if(in_array(auth()->user()->role_id, [1, 4, 7])): ?>
                <li>
                    <a href="<?php echo e(route('reusable-products.index')); ?>">
                        <i class="fas fa-recycle"></i> Produits Réutilisables
                        <?php if(($pendingUsages = cache()->remember('pending_usages', 300, fn() => \App\Models\ProductUsage::where('statut_retour', 'en_attente')->where('quantite_retournable', '>', 0)->count())) > 0): ?>
                        <span class="badge bg-danger text-white ms-1"><?php echo e($pendingUsages); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAuditLogs', \App\Models\Produit::class)): ?>
                <li><a href="<?php echo e(route('produits.audit-logs')); ?>"><i class="fas fa-history"></i> Historique et Traçabilité</a></li>
                <?php endif; ?>

            </ul>
        </li>

        <?php if(auth()->user()->role_id === 3): ?>
        <li class="<?php echo e(request()->routeIs('bon-commandes.validation') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('bon-commandes.validation')); ?>">
                <i class="fas fa-clipboard-check"></i> Valider Commandes
                <?php if(($pendingValidation = cache()->remember('pending_validation', 300, fn() => \App\Models\BonCommande::where('statut', 'envoye')->count())) > 0): ?>
                <span class="badge bg-warning text-dark ms-1"><?php echo e($pendingValidation); ?></span>
                <?php endif; ?>
            </a>
        </li>
        <?php endif; ?>
        <?php endif; ?>

        
        <?php if(in_array(auth()->user()->role_id, [1, 3, 6, 7, 9])): ?>
        <li class="<?php echo e(request()->routeIs('pharmacie.*') ? 'active' : ''); ?>">
            <a href="#pharmacySubmenu" data-bs-toggle="collapse"
               aria-expanded="<?php echo e(request()->routeIs('pharmacie.*') ? 'true' : 'false'); ?>">
                <i class="fas fa-clinic-medical"></i> Pharmacie
                <i class="fas fa-angle-down float-end chevron"></i>
            </a>
            <ul class="collapse list-unstyled <?php echo e(request()->routeIs('pharmacie.*') ? 'show' : ''); ?>" id="pharmacySubmenu">

                <?php if(in_array(auth()->user()->role_id, [1, 7])): ?>
                <li class="<?php echo e(request()->routeIs('pharmacie.index') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('pharmacie.index')); ?>">
                        <i class="fas fa-prescription-bottle-alt"></i> Pharmacie
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array(auth()->user()->role_id, [1, 3, 6, 7])): ?>
                <li class="<?php echo e(request()->routeIs('pharmacie.sales.list') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('pharmacie.sales.list', ['statut_paiement' => 'en_attente'])); ?>">
                        <i class="fas fa-cash-register"></i> Paiements en Attente
                        <?php if(($pendingPayments = cache()->remember('pending_pharma_payments', 300, fn() => \App\Models\VentePharmacie::where('statut_paiement', 'en_attente')->count())) > 0): ?>
                        <span class="badge bg-warning text-dark ms-1"><?php echo e($pendingPayments); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array(auth()->user()->role_id, [1, 3, 7, 9])): ?>
                <li class="<?php echo e(request()->routeIs('pharmacie.history') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('pharmacie.history')); ?>">
                        <i class="fas fa-chart-line"></i> Historique Pharmacie
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array(auth()->user()->role_id, [1, 3, 7])): ?>
                <li class="<?php echo e(request()->routeIs('pharmacie.sales.external.create') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('pharmacie.sales.external.create')); ?>">
                        <i class="fas fa-hospital"></i> Vente Externe
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </li>
        <?php endif; ?>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\chambre::class)): ?>
        <li class="<?php echo e(request()->routeIs('chambres.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('chambres.index')); ?>">
                <i class="fas fa-procedures"></i> Chambres
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\Event::class)): ?>
        <li class="<?php echo e(request()->routeIs('events.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('events.index')); ?>">
                <i class="fas fa-calendar-check"></i> Rendez-vous
            </a>
        </li>
        <?php endif; ?>

        <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Fiche::class)): ?>
        <li class="<?php echo e(request()->routeIs('fiches.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('fiches.index')); ?>">
                <i class="fas fa-smile"></i> Fiches de Satisfaction
            </a>
        </li>
        <?php endif; ?> -->

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\User::class)): ?>
        <li class="<?php echo e(request()->routeIs('factures.consultation') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('factures.consultation')); ?>">
                <i class="fas fa-file-invoice-dollar"></i> Factures
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', \App\Models\Devi::class)): ?>
        <li class="<?php echo e(request()->routeIs('devis.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('devis.index')); ?>">
                <i class="fas fa-file-contract"></i> Devis
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', \App\Models\DevisElement::class)): ?>
        <li class="<?php echo e(request()->routeIs('devis_elements.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('devis_elements.index')); ?>">
                <i class="fas fa-list-ul"></i> Éléments de Devis
            </a>
        </li>
        <?php endif; ?>

    </ul>
</nav>

<style>
    /* ─── Sidebar base ─────────────────────────────────────────── */
    #sidebar {
        background: #1a1a2e;          /* dark navy base */
        color: #c8cfe0;
        min-height: 100vh;
        width: 250px;
        transition: all 0.3s;
    }

    #sidebar .sidebar-header {
        padding: 20px;
        background: #16213e;
        text-align: center;
    }

    #sidebar .sidebar-header h1 span {
        color: #4fc3f7;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 2px;
    }

    /* ─── All nav links ────────────────────────────────────────── */
    #sidebar ul.components {
        padding: 8px 0;
    }

    #sidebar ul li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 20px;
        color: #c8cfe0;              /* light text on dark bg */
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        border-left: 3px solid transparent;
        transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease, padding-left 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
    }

    #sidebar ul li a i:first-child {
        width: 18px;
        text-align: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    /* ─── Hover state ──────────────────────────────────────────── */
    #sidebar ul li a:hover {
        background: #4463dc;   /* subtle accent glow */
        color: #4c6ef5;                          /* accent colour, NOT the bg */
        border-left-color: #4fc3f7;
        padding-left: 24px;
        text-decoration: none;
    }

    /* ─── Active / current page ───────────────────────────────── */
    #sidebar ul li.active > a {
        background: hsla(228, 89%, 63%, 0.80);
        color: #fff;
        border-left-color: #4fc3f7;
        font-weight: 600;
    }

    /* ─── Submenu items (nested) ──────────────────────────────── */
    #sidebar ul ul {
        background: rgba(0, 0, 0, 0.15);
    }

    #sidebar ul ul li a {
        padding-left: 46px;
        font-size: 0.825rem;
        border-left: none;
        border-left: 3px solid transparent;
    }

    #sidebar ul ul li a:hover {
        background: #fff;
        color: #4c6ef5;
        padding-left: 52px;
        border-left-color: #4fc3f7;
    }

    #sidebar ul ul li.active > a {
        background: #4463dc;
        color: #fff;
        border-left-color: #4fc3f7;
    }

    /* ─── Chevron rotation on open ────────────────────────────── */
    #sidebar ul li a[aria-expanded="true"] .chevron {
        transform: rotate(180deg);
    }

    #sidebar ul li a .chevron {
        margin-left: auto;
        transition: transform 0.25s ease;
    }

    /* ─── Badges ──────────────────────────────────────────────── */
    #sidebar .badge {
        font-size: 0.7rem;
        padding: 0.2em 0.5em;
        border-radius: 10px;
        margin-left: auto;
        flex-shrink: 0;
    }

    /* ─── Section divider utility ─────────────────────────────── */
    #sidebar ul li.nav-section-label {
        padding: 16px 20px 4px;
        font-size: 0.65rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #556080;
        pointer-events: none;
    }

    /* ─── Responsive ──────────────────────────────────────────── */
    @media (max-width: 768px) {
        #sidebar {
            margin-left: -250px;
        }

        #sidebar.active {
            margin-left: 0;
        }
    }
</style><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/partials/side_bar.blade.php ENDPATH**/ ?>