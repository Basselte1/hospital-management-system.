
<?php $__env->startSection('title', 'CMCU | Nouvelle Vente Patient'); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper">
    <?php echo $__env->make('partials.side_bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-user-injured"></i> Nouvelle Vente Patient
                </h1>
                <hr class="w-25 mx-auto">
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('pharmacie.index')); ?>" class="btn btn-success">
                    <i class="fas fa-arrow-left"></i> Retour à la pharmacie
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Patient Search Section -->
                        <?php if(!$patient): ?>
                        <div class="mb-4 p-4 bg-light rounded">
                            <h5 class="mb-3"><i class="fas fa-search"></i> Rechercher un Patient</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" 
                                               id="patient-search" 
                                               class="form-control" 
                                               placeholder="Cliquez ici pour voir tous les patients ou tapez pour rechercher..."
                                               autocomplete="off">
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        💡 Cliquez dans le champ pour voir tous les patients récents, ou tapez au moins 2 caractères pour rechercher
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Loading indicator -->
                            <div id="search-loading" class="mt-3 text-center" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                                <p class="mt-2">Chargement des patients...</p>
                            </div>
                            
                            <!-- No results message -->
                            <div id="no-results" class="mt-3 alert alert-warning" style="display: none;">
                                <i class="fas fa-info-circle"></i> Aucun patient trouvé. Vérifiez l'orthographe ou essayez avec le numéro de dossier.
                            </div>
                            
                            <!-- Results container with scrollable list -->
                            <div id="patient-results-container" class="mt-3" style="max-height: 500px; overflow-y: auto; display: none;">
                                <div class="alert alert-info py-2">
                                    <small><i class="fas fa-info-circle"></i> <span id="results-count">0</span> patient(s) trouvé(s)</small>
                                </div>
                                <div id="patient-results"></div>
                                <!-- Load more indicator -->
                                <div id="load-more-indicator" class="text-center py-3" style="display: none;">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                    <small class="text-muted ms-2">Chargement de plus de patients...</small>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Patient Info -->
                        <div class="alert alert-success mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5><i class="fas fa-user-check"></i> Patient Sélectionné</h5>
                                    <p class="mb-0">
                                        <strong><?php echo e($patient->name); ?> <?php echo e($patient->prenom); ?></strong><br>
                                        Dossier N°: <?php echo e($patient->numero_dossier); ?>

                                        <?php if($patient->telephone): ?>
                                        <br>Tél: <?php echo e($patient->telephone); ?>

                                        <?php endif; ?>
                                    </p>
                                </div>
                                <a href="<?php echo e(route('pharmacie.sales.patient.create')); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times"></i> Changer
                                </a>
                            </div>
                        </div>

                        <!-- Prescription Selection -->
                        <?php if($patient->ordonances && $patient->ordonances->count() > 0): ?>
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="mb-3"><i class="fas fa-file-prescription"></i> Ordonnances du Patient</h5>
                            <div class="row">
                                <?php $__currentLoopData = $patient->ordonances->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 mb-2">
                                    <div class="card <?php echo e($ordonance && $ordonance->id == $ord->id ? 'border-primary' : ''); ?>">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong><?php echo e($ord->created_at->format('d/m/Y à H:i')); ?></strong>
                                                    <p class="mb-0 small text-muted mt-1">
                                                        Par: <?php echo e($ord->user->name ?? 'N/A'); ?>

                                                    </p>
                                                    <?php if($ord->description): ?>
                                                    <p class="mb-0 small mt-1"><?php echo e(\Illuminate\Support\Str::limit($ord->description, 50)); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if(!$ordonance || $ordonance->id != $ord->id): ?>
                                                <a href="<?php echo e(route('pharmacie.sales.patient.create', ['patient_id' => $patient->id, 'ordonance_id' => $ord->id])); ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-check"></i> Utiliser
                                                </a>
                                                <?php else: ?>
                                                <span class="badge bg-success">Sélectionnée</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i> Ce patient n'a pas d'ordonnance enregistrée. Vous pouvez créer une vente directe.
                        </div>
                        <?php endif; ?>

                        <!-- Sale Form -->
                        <form action="<?php echo e(route('pharmacie.sales.patient.store')); ?>" method="POST" id="sale-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">
                            <?php if($ordonance): ?>
                            <input type="hidden" name="ordonance_id" value="<?php echo e($ordonance->id); ?>">
                            
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Ordonnance utilisée :</strong> <?php echo e($ordonance->created_at->format('d/m/Y à H:i')); ?>

                            </div>
                            <?php endif; ?>

                            <h5 class="mb-3"><i class="fas fa-pills"></i> Produits à Vendre</h5>

                            <!-- Suggested Products from Prescription -->
                            <?php if(!empty($suggestedProducts) && count($suggestedProducts) > 0): ?>
                            <div class="alert alert-warning mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-lightbulb"></i> 
                                        <strong><?php echo e(count($suggestedProducts)); ?> produit(s) suggéré(s) de l'ordonnance</strong>
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div id="items-container">
                                <!-- Pre-populated items from prescription -->
                                <?php if(!empty($suggestedProducts) && count($suggestedProducts) > 0): ?>
                                    <?php $__currentLoopData = $suggestedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $suggested): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="card mb-2 item-row">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-5">
                                                    <label class="small">Produit * 
                                                        <span class="badge bg-warning text-dark">Suggéré</span>
                                                    </label>
                                                    <select name="items[<?php echo e($index); ?>][produit_id]" class="form-control product-select" required>
                                                        <option value="">Sélectionner...</option>
                                                        <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($p->id); ?>" 
                                                                data-prix="<?php echo e($p->prix_unitaire); ?>" 
                                                                data-stock="<?php echo e($p->qte_stock); ?>"
                                                                <?php echo e(($suggested['product'] && $suggested['product']->id == $p->id) ? 'selected' : ''); ?>>
                                                            <?php echo e($p->designation); ?> (Stock: <?php echo e($p->qte_stock); ?>) - <?php echo e(number_format($p->prix_unitaire)); ?> FCFA
                                                        </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Quantité *</label>
                                                    <input type="number" 
                                                           name="items[<?php echo e($index); ?>][quantite]" 
                                                           class="form-control quantity-input" 
                                                           value="<?php echo e($suggested['suggested_qty']); ?>" 
                                                           min="1" 
                                                           required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Prix Unit.</label>
                                                    <input type="text" class="form-control prix-display" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Total</label>
                                                    <input type="text" class="form-control total-display" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="small">&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm w-100 remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <!-- Default empty row -->
                                    <div class="card mb-2 item-row">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-5">
                                                    <label class="small">Produit *</label>
                                                    <select name="items[0][produit_id]" class="form-control product-select" required>
                                                        <option value="">Sélectionner...</option>
                                                        <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($p->id); ?>" data-prix="<?php echo e($p->prix_unitaire); ?>" data-stock="<?php echo e($p->qte_stock); ?>">
                                                            <?php echo e($p->designation); ?> (Stock: <?php echo e($p->qte_stock); ?>) - <?php echo e(number_format($p->prix_unitaire)); ?> FCFA
                                                        </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Quantité *</label>
                                                    <input type="number" name="items[0][quantite]" class="form-control quantity-input" value="1" min="1" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Prix Unit.</label>
                                                    <input type="text" class="form-control prix-display" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="small">Total</label>
                                                    <input type="text" class="form-control total-display" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="small">&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm w-100 remove-item" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="text-center my-3">
                                <button type="button" id="add-item" class="btn btn-outline-primary">
                                    <i class="fas fa-plus"></i> Ajouter un produit
                                </button>
                            </div>

                            <div class="card bg-light mt-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 text-end">
                                            <h4>Total: <span id="grand-total" class="text-primary">0 FCFA</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-check"></i> Créer la Facture
                                </button>
                                <a href="<?php echo e(route('pharmacie.index')); ?>" class="btn btn-secondary btn-lg px-5">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
waitForjQuery(function() {

$(document).ready(function() {
    console.log('=== Patient Search Script Initialized ===');
    
    // ============================================
    // VARIABLES
    // ============================================
    let isLoadingPatients = false;
    let patientPage = 1;
    let hasMorePatients = true;
    let searchTimeout = null;
    let itemCounter = <?php echo e(!empty($suggestedProducts) ? count($suggestedProducts) : 1); ?>;
    
    // ============================================
    // PATIENT SEARCH INITIALIZATION
    // ============================================
    
    <?php if(!$patient): ?>
    // Focus on search triggers initial load
    $('#patient-search').on('focus', function() {
        console.log('Search input focused');
        let currentValue = $(this).val().trim();
        
        if (currentValue === '' && $('#patient-results').children().length === 0) {
            console.log('Loading all patients on focus');
            loadAllPatients();
        } else if (currentValue !== '') {
            console.log('Showing existing results');
            $('#patient-results-container').show();
        }
    });
    
    // Search input handler with debounce
    $('#patient-search').on('input', function() {
        let query = $(this).val().trim();
        console.log('Search input changed:', query);
        
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        // Reset pagination
        patientPage = 1;
        hasMorePatients = true;
        
        if (query === '') {
            // If empty, load all patients
            searchTimeout = setTimeout(function() {
                console.log('Empty search - loading all patients');
                loadAllPatients();
            }, 300);
        } else if (query.length >= 2) {
            // Search with at least 2 characters
            searchTimeout = setTimeout(function() {
                console.log('Performing search for:', query);
                performPatientSearch(query);
            }, 500);
        } else {
            // Less than 2 chars - hide results
            $('#patient-results-container').hide();
            $('#no-results').hide();
        }
    });
    
    // Load all patients initially (triggered on click/focus)
    function loadAllPatients() {
        if (isLoadingPatients) {
            console.log('Already loading patients, skipping...');
            return;
        }
        
        isLoadingPatients = true;
        console.log('Loading all patients - Page:', patientPage);
        
        $('#search-loading').show();
        $('#no-results').hide();
        
        if (patientPage === 1) {
            $('#patient-results').empty();
        }
        
        $.ajax({
            url: '<?php echo e(route("pharmacie.search-patient")); ?>',
            method: 'GET',
            data: { 
                q: '',  // Empty query for all patients
                page: patientPage 
            },
            success: function(response) {
                console.log('Received', response.length, 'patients');
                
                $('#search-loading').hide();
                
                if (response.length === 0 && patientPage === 1) {
                    $('#no-results').text('Aucun patient trouvé dans la base de données.').show();
                    $('#patient-results-container').hide();
                } else {
                    displayPatients(response);
                    $('#patient-results-container').show();
                    
                    // Check if there might be more patients
                    if (response.length < 50) {
                        hasMorePatients = false;
                    }
                }
                
                $('#load-more-indicator').hide();
                isLoadingPatients = false;
            },
            error: function(xhr, status, error) {
                console.error('Load Error:', {xhr, status, error});
                
                $('#search-loading').hide();
                $('#load-more-indicator').hide();
                
                let errorMessage = 'Erreur de chargement. ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                } else {
                    errorMessage += 'Veuillez rafraîchir la page.';
                }
                
                $('#no-results').html('<i class="fas fa-exclamation-triangle"></i> ' + errorMessage).show();
                isLoadingPatients = false;
            }
        });
    }
    
    // ============================================
    // SEARCH PATIENTS FUNCTION
    // ============================================
    
    function performPatientSearch(query) {
        if (isLoadingPatients) return;
        
        isLoadingPatients = true;
        console.log('Searching for:', query);
        
        $('#search-loading').show();
        $('#no-results').hide();
        
        if (patientPage === 1) {
            $('#patient-results').empty();
        }
        
        $.ajax({
            url: '<?php echo e(route("pharmacie.search-patient")); ?>',
            method: 'GET',
            data: { q: query },
            success: function(response) {
                console.log('Search results:', response.length);
                
                $('#search-loading').hide();
                
                if (response.length === 0) {
                    $('#no-results').show();
                    $('#patient-results-container').hide();
                } else {
                    displayPatients(response);
                    $('#patient-results-container').show();
                }
                
                isLoadingPatients = false;
            },
            error: function(xhr, status, error) {
                console.error('Search Error:', {xhr, status, error});
                
                $('#search-loading').hide();
                
                let errorMessage = 'Erreur de recherche. ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                } else {
                    errorMessage += 'Veuillez réessayer.';
                }
                
                $('#no-results').html('<i class="fas fa-exclamation-triangle"></i> ' + errorMessage).show();
                isLoadingPatients = false;
            }
        });
    }
    
    // ============================================
    // DISPLAY PATIENTS FUNCTION
    // ============================================
    
    function displayPatients(patients) {
        console.log('Displaying', patients.length, 'patients');
        
        if (!Array.isArray(patients)) {
            console.error('Invalid patients data:', patients);
            return;
        }
        
        // Update results count
        let currentCount = $('#patient-results').children().length;
        let newTotal = currentCount + patients.length;
        $('#results-count').text(newTotal);
        
        patients.forEach(function(patient) {
            let ordonancesHtml = '';
            
            if (patient.ordonances && patient.ordonances.length > 0) {
                ordonancesHtml = '<div class="mt-1"><small class="text-success"><i class="fas fa-file-prescription"></i> ' + 
                                patient.ordonances.length + ' ordonnance(s)</small></div>';
            } else {
                ordonancesHtml = '<div class="mt-1"><small class="text-muted"><i class="fas fa-info-circle"></i> Aucune ordonnance</small></div>';
            }
            
            let cardHtml = `
                <div class="card mb-2 patient-card" style="cursor: pointer; transition: all 0.2s;">
                    <div class="card-body py-2 px-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <strong style="font-size: 1.05em;">${patient.name} ${patient.prenom}</strong><br>
                                <small class="text-muted">
                                    <i class="fas fa-folder"></i> Dossier: <strong>${patient.numero_dossier}</strong>
                                    ${patient.telephone ? '<br><i class="fas fa-phone"></i> Tél: ' + patient.telephone : ''}
                                </small>
                                ${ordonancesHtml}
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="<?php echo e(route('pharmacie.sales.patient.create')); ?>?patient_id=${patient.id}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-check"></i> Sélectionner
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`;
            
            $('#patient-results').append(cardHtml);
        });
        
        // Add hover effect
        $('.patient-card').hover(
            function() { $(this).addClass('shadow-sm border-primary'); },
            function() { $(this).removeClass('shadow-sm border-primary'); }
        );
    }
    
    // ============================================
    // INFINITE SCROLL
    // ============================================
    
    $('#patient-results-container').on('scroll', function() {
        let container = $(this);
        let scrollTop = container.scrollTop();
        let scrollHeight = container[0].scrollHeight;
        let height = container.height();
        
        if (scrollTop + height >= scrollHeight - 50 && 
            hasMorePatients && 
            !isLoadingPatients) {
            patientPage++;
            $('#load-more-indicator').show();
            
            let currentQuery = $('#patient-search').val().trim();
            if (currentQuery) {
                performPatientSearch(currentQuery);
            } else {
                loadAllPatients();
            }
        }
    });
    <?php endif; ?>
    
    // ============================================
    // PRODUCT MANAGEMENT
    // ============================================
    
    // Add item button
    $('#add-item').click(function() {
        addProductRow();
    });
    
    function addProductRow() {
        let html = `
        <div class="card mb-2 item-row">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <label class="small">Produit *</label>
                        <select name="items[${itemCounter}][produit_id]" class="form-control product-select" required>
                            <option value="">Sélectionner...</option>
                            <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" data-prix="<?php echo e($p->prix_unitaire); ?>" data-stock="<?php echo e($p->qte_stock); ?>">
                                <?php echo e($p->designation); ?> (Stock: <?php echo e($p->qte_stock); ?>) - <?php echo e(number_format($p->prix_unitaire)); ?> FCFA
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small">Quantité *</label>
                        <input type="number" name="items[${itemCounter}][quantite]" class="form-control quantity-input" value="1" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small">Prix Unit.</label>
                        <input type="text" class="form-control prix-display" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="small">Total</label>
                        <input type="text" class="form-control total-display" readonly>
                    </div>
                    <div class="col-md-1">
                        <label class="small">&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm w-100 remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        
        $('#items-container').append(html);
        itemCounter++;
    }

    // Remove item
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateTotal();
        } else {
            alert('Il faut au moins un produit');
        }
    });

    // Product selection change
    $(document).on('change', '.product-select', function() {
        let selected = $(this).find(':selected');
        let prix = selected.data('prix');
        let stock = selected.data('stock');
        let row = $(this).closest('.item-row');
        
        row.find('.prix-display').val(prix ? prix.toLocaleString() : '');
        row.find('.quantity-input').attr('max', stock);
        calculateRowTotal(row);
    });

    // Quantity change
    $(document).on('input', '.quantity-input', function() {
        let row = $(this).closest('.item-row');
        let max = parseInt($(this).attr('max'));
        let val = parseInt($(this).val());
        
        if (max && val > max) {
            alert('Stock insuffisant! Maximum disponible: ' + max);
            $(this).val(max);
        }
        
        calculateRowTotal(row);
    });

    function calculateRowTotal(row) {
        let prix = parseInt(row.find('.product-select :selected').data('prix')) || 0;
        let qty = parseInt(row.find('.quantity-input').val()) || 0;
        let total = prix * qty;
        row.find('.total-display').val(total.toLocaleString());
        calculateTotal();
    }

    function calculateTotal() {
        let grandTotal = 0;
        $('.item-row').each(function() {
            let prix = parseInt($(this).find('.product-select :selected').data('prix')) || 0;
            let qty = parseInt($(this).find('.quantity-input').val()) || 0;
            grandTotal += prix * qty;
        });
        $('#grand-total').text(grandTotal.toLocaleString() + ' FCFA');
    }

    // Form validation
    $('#sale-form').submit(function(e) {
        let hasValidProduct = false;
        $('.product-select').each(function() {
            if ($(this).val()) {
                hasValidProduct = true;
            }
        });

        if (!hasValidProduct) {
            e.preventDefault();
            alert('Veuillez sélectionner au moins un produit');
            return false;
        }
        
        // Check stock
        let stockError = false;
        $('.item-row').each(function() {
            let qty = parseInt($(this).find('.quantity-input').val()) || 0;
            let max = parseInt($(this).find('.quantity-input').attr('max')) || 0;
            if (qty > max) {
                stockError = true;
            }
        });
        
        if (stockError) {
            e.preventDefault();
            alert('Veuillez vérifier les quantités - stock insuffisant');
            return false;
        }
    });

    // Initial calculation for pre-populated items
    <?php if($patient): ?>
    $('.item-row').each(function() {
        let row = $(this);
        let selected = row.find('.product-select :selected');
        let prix = selected.data('prix');
        let stock = selected.data('stock');
        
        row.find('.prix-display').val(prix ? prix.toLocaleString() : '');
        row.find('.quantity-input').attr('max', stock);
        calculateRowTotal(row);
    });
    <?php endif; ?>
    
    console.log('=== Script Fully Loaded ===');
});

});
</script>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\JUSTO TCHEUMANI\DESKTOP\CMCU\Nouveaudossier\Deployfortest\cmcuapp\cmcuapp\resources\views/admin/pharmacie/create_patient_sale.blade.php ENDPATH**/ ?>