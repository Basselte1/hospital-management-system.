// Enhanced Devis Management with Products
function waitForjQuery(callback) {
    if (typeof jQuery !== 'undefined') {
        callback();
    } else {
        setTimeout(function() { waitForjQuery(callback); }, 100);
    }
}

waitForjQuery(function() {
    (function($) {
        'use strict';
        
        let procedureCounter = 0;
        let productCounter = 0;
        let elementsCache = [];
        let productsCache = [];

        $(document).ready(function() {
            console.log('Enhanced devis management initializing...');
            initializeDevisModal();
            loadDevisElements();
            loadProducts();
            bindEventHandlers();
        });

        /**
         * Load devis elements for autocomplete
         */
        function loadDevisElements() {
            $.ajax({
                url: '/admin/devis-elements/actifs',
                method: 'GET',
                success: function(data) {
                    elementsCache = data;
                    console.log('Loaded devis elements:', data.length);
                },
                error: function(xhr) {
                    console.error('Failed to load devis elements:', xhr);
                }
            });
        }

        /**
         * Load products for autocomplete
         */
        function loadProducts() {
            // Get all active products
            $.ajax({
                url: '/admin/autocomplete',
                method: 'GET',
                data: { query: '' },
                success: function(data) {
                    // This returns product names, we need full product data
                    // Better to create a dedicated endpoint
                    console.log('Products loaded');
                },
                error: function(xhr) {
                    console.error('Failed to load products:', xhr);
                }
            });
        }

        /**
         * Initialize devis modal
         */
        /**
         * Initialize devis modal
         */
        function initializeDevisModal() {
            // Edit button click — fetch data via AJAX
            $(document).on('click', '.btn-edit-devis', function() {
                const deviId = $(this).data('id');
                resetForm();
                
                // Show modal with a loading state
                $('#devisModalLabel').text('Chargement...');
                $('#devisModal').modal('show');
                $('#devis_save').hide();

                $.ajax({
                    url: '/admin/devis/' + deviId + '/show',
                    method: 'GET',
                    success: function(devi) {
                        setupEditMode(devi);
                    },
                    error: function(xhr) {
                        console.error('Erreur lors du chargement du devis:', xhr);
                        alert('Impossible de charger les données du devis.');
                        $('#devisModal').modal('hide');
                    }
                });
            });

            // Create button click
            $(document).on('click', '[data-mode="create"]', function() {
                resetForm();
                setupCreateMode();
            });

            // Print button click — fetch data via AJAX then setup print mode
            $(document).on('click', '[data-mode="print"]', function(e) {
                e.preventDefault();
                const deviId = $(this).data('id');
                resetForm();

                $('#devisModalLabel').text('Chargement...');
                $('#devisModal').modal('show');
                $('#devis_save').hide();
                $('#devis_export').hide();

                $.ajax({
                    url: '/admin/devis/' + deviId + '/show',
                    method: 'GET',
                    success: function(devi) {
                        setupPrintMode(devi);
                    },
                    error: function(xhr) {
                        console.error('Erreur lors du chargement du devis pour impression:', xhr);
                        alert('Impossible de charger les données du devis.');
                        $('#devisModal').modal('hide');
                    }
                });
            });

            $('#devisModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        }

        /**
         * Setup create mode
         */
        function setupCreateMode() {
            $('#devisModalLabel').text('Nouveau Devis');
            $('#devis_form').attr('action', '/admin/devis');
            $('#form_method').val('POST');
            $('#devi_id').val('');
            $('#devis_save').show();
            $('#devis_export').hide();
            enableFormFields();
            addEmptyProcedure();
            addEmptyProcedure();
        }

        /**
         * Setup edit mode
         */
        function setupEditMode(devi) {
            $('#devisModalLabel').text('Modifier le Devis #' + devi.code);
            $('#devis_form').attr('action', '/admin/devis/' + devi.id);
            $('#form_method').val('PUT');
            $('#devi_id').val(devi.id);
            $('#devis_save').show();
            $('#devis_export').hide();
            
            loadDevisData(devi);
            enableFormFields();
        }

        /**
         * Setup print mode
         */
        function setupPrintMode(devi) {
            $('#devisModalLabel').text('Aperçu avant impression — Devis #' + devi.code);
            $('#devi_id').val(devi.id);
            $('#devis_save').hide();
            
            loadDevisData(devi);
            disableFormFields();

            // Show a dedicated print button that navigates directly
            $('#devis_export').off('click.print').on('click.print', function() {
                window.location.href = '/admin/devis/print/' + devi.id;
            }).show().text('🖨 Imprimer');
        }

        /**
         * Load devis data into form
         */
        function loadDevisData(devi) {
            // Set patient — trigger change for Select2 compatibility
            $('#patient_id').val(devi.patient_id).trigger('change');
            $('#nom_devis').val(devi.nom);
            $('#code_devis').val(devi.code);
            $('#acces_devis').val(devi.acces);

            // Load line items
            if (devi.ligne_devis && devi.ligne_devis.length > 0) {
                devi.ligne_devis.forEach(function(ligne) {
                    if (ligne.type === 'procedure') {
                        addProcedureLine(ligne.element, ligne.quantite, ligne.prix_u);
                    } else {
                        addProductLine(
                            ligne.produit_id,
                            ligne.element,
                            ligne.type,
                            ligne.quantite,
                            ligne.prix_u,
                            0
                        );
                    }
                });
            } else {
                addEmptyProcedure();
            }

            // Load hospitalization data
            $('#nbr_chambre').val(devi.nbr_chambre || 0);
            $('#pu_chambre').val(devi.pu_chambre || 30000);
            $('#nbr_visite').val(devi.nbr_visite || 0);
            $('#pu_visite').val(devi.pu_visite || 10000);
            $('#nbr_ami_jour').val(devi.nbr_ami_jour || 0);
            $('#pu_ami_jour').val(devi.pu_ami_jour || 9000);

            calculateHospitalization();
            calculateTotals();
        }

        /**
         * Reset form
         */
        function resetForm() {
            $('#devis_form')[0].reset();
            $('#procedures_container').empty();
            $('#products_container').empty();
            procedureCounter = 0;
            productCounter = 0;
            calculateTotals();
        }

        /**
         * Enable form fields
         */
        function enableFormFields() {
            $('#devis_form input, #devis_form select, #devis_form textarea').prop('disabled', false);
            $('#ajouter_procedure, #ajouter_product, #import_patient_products').show();
            $('.remove-line').show();
        }

        /**
         * Disable form fields
         */
        function disableFormFields() {
            $('#devis_form input, #devis_form select, #devis_form textarea').prop('disabled', true);
            $('#ajouter_procedure, #ajouter_product, #import_patient_products').hide();
            $('.remove-line').hide();
        }

        /**
         * Add empty procedure line
         */
        function addEmptyProcedure() {
            addProcedureLine('', 1, 0);
        }

        /**
         * Add procedure line
         */
        function addProcedureLine(element = '', quantite = 1, prixU = 0) {
            const lineHtml = `
                <div class="row mb-2 procedure-line" data-index="${procedureCounter}">
                    <div class="col-sm-1 text-center align-self-center">
                        <small class="ligne-num">${procedureCounter + 1}</small>
                    </div>
                    <div class="col-sm-4 position-relative">
                        <input type="text" 
                               name="ligneDevi[proc_${procedureCounter}][element]" 
                               class="form-control procedure-input" 
                               value="${element}" 
                               placeholder="Commencez à taper..." 
                               data-index="${procedureCounter}"
                               required>
                        <input type="hidden" name="ligneDevi[proc_${procedureCounter}][type]" value="procedure">
                    </div>
                    <div class="col-sm-2">
                        <input type="number" 
                               name="ligneDevi[proc_${procedureCounter}][quantite]" 
                               class="form-control quantite" 
                               value="${quantite}" 
                               min="1" 
                               required>
                    </div>
                    <div class="col-sm-2">
                        <input type="number" 
                               name="ligneDevi[proc_${procedureCounter}][prix_u]" 
                               class="form-control prix_u" 
                               value="${prixU}" 
                               min="0" 
                               required>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" 
                               class="form-control prix-total" 
                               value="${quantite * prixU}" 
                               readonly>
                    </div>
                    <div class="col-sm-1 text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-procedure">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#procedures_container').append(lineHtml);
            procedureCounter++;
            renumberLines('.procedure-line');
            updateCounts();
            calculateTotals();
        }

        /**
         * Add product line
         */
        function addProductLine(produitId = null, nom = '', type = 'medication', quantite = 1, prixU = 0, stock = 0) {
            const typeOptions = `
                <option value="medication" ${type === 'medication' ? 'selected' : ''}>Médicament</option>
                <option value="material" ${type === 'material' ? 'selected' : ''}>Matériel</option>
                <option value="anesthesie" ${type === 'anesthesie' ? 'selected' : ''}>Anesthésie</option>
            `;
            
            const stockClass = stock < quantite ? 'text-danger' : 'text-success';
            
            const lineHtml = `
                <div class="row mb-2 product-line" data-index="${productCounter}">
                    <div class="col-sm-1 text-center align-self-center">
                        <small class="ligne-num">${productCounter + 1}</small>
                    </div>
                    <div class="col-sm-3 position-relative">
                        <input type="text" 
                               name="ligneDevi[prod_${productCounter}][element]" 
                               class="form-control product-input" 
                               value="${nom}" 
                               placeholder="Rechercher produit..." 
                               data-index="${productCounter}"
                               required>
                        <input type="hidden" 
                               name="ligneDevi[prod_${productCounter}][produit_id]" 
                               class="produit-id"
                               value="${produitId || ''}">
                    </div>
                    <div class="col-sm-2">
                        <select name="ligneDevi[prod_${productCounter}][type]" class="form-select form-select-sm product-type">
                            ${typeOptions}
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" 
                               class="form-control form-control-sm stock-display ${stockClass}" 
                               value="${stock}" 
                               readonly>
                    </div>
                    <div class="col-sm-1">
                        <input type="number" 
                               name="ligneDevi[prod_${productCounter}][quantite]" 
                               class="form-control quantite" 
                               value="${quantite}" 
                               min="1" 
                               required>
                    </div>
                    <div class="col-sm-2">
                        <input type="number" 
                               name="ligneDevi[prod_${productCounter}][prix_u]" 
                               class="form-control prix_u" 
                               value="${prixU}" 
                               min="0" 
                               required>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" 
                               class="form-control prix-total" 
                               value="${quantite * prixU}" 
                               readonly>
                    </div>
                    <div class="col-sm-1 text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#products_container').append(lineHtml);
            productCounter++;
            renumberLines('.product-line');
            updateCounts();
            calculateTotals();
        }

        /**
         * Renumber lines
         */
        function renumberLines(selector) {
            $(selector).each(function(index) {
                $(this).find('.ligne-num').text(index + 1);
            });
        }

        /**
         * Update counts badges
         */
        function updateCounts() {
            $('#procedures_count').text($('.procedure-line').length);
            $('#products_count').text($('.product-line').length);
        }

        /**
         * Bind event handlers
         */
        function bindEventHandlers() {
            // Add procedure button
            $(document).on('click', '#ajouter_procedure', function() {
                addEmptyProcedure();
            });

            // Add product button
            $(document).on('click', '#ajouter_product', function() {
                addProductLine();
            });

            // Remove procedure
            $(document).on('click', '.remove-procedure', function() {
                if ($('.procedure-line').length > 1) {
                    $(this).closest('.procedure-line').remove();
                    renumberLines('.procedure-line');
                    updateCounts();
                    calculateTotals();
                } else {
                    alert('Au moins une procédure est requise');
                }
            });

            // Remove product
            $(document).on('click', '.remove-product', function() {
                $(this).closest('.product-line').remove();
                renumberLines('.product-line');
                updateCounts();
                calculateTotals();
            });

            // Procedure autocomplete
            $(document).on('input', '.procedure-input', function() {
                const input = $(this);
                const query = input.val().toLowerCase();

                if (query.length < 2) {
                    hideAutocomplete();
                    return;
                }

                const matches = elementsCache.filter(function(element) {
                    return element.nom.toLowerCase().includes(query) || 
                           (element.code && element.code.toLowerCase().includes(query));
                });

                showProcedureAutocomplete(input, matches);
            });

            // Product autocomplete (search in produits table)
            $(document).on('input', '.product-input', function() {
                const input = $(this);
                const query = input.val();

                if (query.length < 2) {
                    hideAutocomplete();
                    return;
                }

                // Search products via AJAX
                $.ajax({
                    url: '/admin/autocomplete',
                    method: 'GET',
                    data: { query: query },
                    success: function(products) {
                        showProductAutocomplete(input, products);
                    }
                });
            });

            // Quantity and price changes
            $(document).on('change', '.quantite, .prix_u', function() {
                const row = $(this).closest('.procedure-line, .product-line');
                const quantite = parseFloat(row.find('.quantite').val()) || 0;
                const prixU = parseFloat(row.find('.prix_u').val()) || 0;
                row.find('.prix-total').val((quantite * prixU).toFixed(0));
                
                // Check stock for products
                if (row.hasClass('product-line')) {
                    const stock = parseFloat(row.find('.stock-display').val()) || 0;
                    if (quantite > stock) {
                        row.find('.stock-display').removeClass('text-success').addClass('text-danger');
                        alert('Attention: Stock insuffisant!');
                    } else {
                        row.find('.stock-display').removeClass('text-danger').addClass('text-success');
                    }
                }
                
                calculateTotals();
            });

            // Hospitalization calculations
            $(document).on('change', '#nbr_chambre, #pu_chambre, #nbr_visite, #pu_visite, #nbr_ami_jour, #pu_ami_jour', function() {
                calculateHospitalization();
            });

            // Import patient products
            $(document).on('click', '#import_patient_products', function() {
                const patientId = $('#patient_id').val();
                
                if (!patientId) {
                    alert('Veuillez d\'abord sélectionner un patient');
                    return;
                }
                
                $.ajax({
                    url: '/admin/devis/patient-products/' + patientId,
                    method: 'GET',
                    success: function(response) {
                        if (response.success && response.products.length > 0) {
                            response.products.forEach(function(product) {
                                addProductLine(
                                    product.produit_id,
                                    product.element,
                                    product.type,
                                    product.quantite,
                                    product.prix_u,
                                    product.stock_available
                                );
                            });
                            
                            // Switch to products tab
                            $('#products-tab').click();
                            
                            alert(response.products.length + ' produit(s) importé(s)');
                        } else {
                            alert('Aucun produit consommé trouvé pour ce patient');
                        }
                    },
                    error: function() {
                        alert('Erreur lors de l\'importation des produits');
                    }
                });
            });

            // Save button
            $('#devis_save').on('click', function() {
                if ($('#devis_form')[0].checkValidity()) {
                    $('#devis_form').submit();
                } else {
                    $('#devis_form')[0].reportValidity();
                }
            });

            // Export button
            $('#devis_export').on('click', function() {
                const patientId = $('#patient_id').val();
                
                // Validate patient selection
                if (!patientId) {
                    alert('Veuillez sélectionner un patient avant d\'exporter');
                    return;
                }
                
                // **NEW: Validate required fields before export**
                const nomDevis = $('#nom_devis').val();
                if (!nomDevis) {
                    alert('Le nom du devis est obligatoire');
                    return;
                }
                
                // Check if there are procedure lines
                if ($('.procedure-line').length === 0 && $('.product-line').length === 0) {
                    alert('Veuillez ajouter au moins un élément au devis');
                    return;
                }
                
                const totalGeneral = parseInt($('#total_general').text().replace(/\s/g, '')) || 0;
                const montantEnLettre = NumberToLetter(totalGeneral);
                
                // Get CSRF token
                const token = $('meta[name="csrf-token"]').attr('content') || $('#csrf_token').val();
                
                // Create form
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '/admin/devis/export/' + encodeURIComponent(montantEnLettre)
                });
                
                // Add CSRF token
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': token
                }));
                
                // **CRITICAL: Add patient_id**
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'patient_id',
                    'value': patientId
                }));
                
                // **NEW: Explicitly add all hospitalization fields with default values**
                const hospitalizationFields = {
                    'nbr_chambre': $('#nbr_chambre').val() || '0',
                    'nbr_visite': $('#nbr_visite').val() || '0',
                    'nbr_ami_jour': $('#nbr_ami_jour').val() || '0',
                    'pu_chambre': $('#pu_chambre').val() || '30000',
                    'pu_visite': $('#pu_visite').val() || '10000',
                    'pu_ami_jour': $('#pu_ami_jour').val() || '9000',
                    'nom_devis': nomDevis,
                    'code_devis': $('#code_devis').val() || '',
                    'acces_devis': $('#acces_devis').val() || 'acte'
                };
                
                // Add each hospitalization field
                $.each(hospitalizationFields, function(name, value) {
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': name,
                        'value': value
                    }));
                });
                
                // **NEW: Add line items properly structured**
                let lineIndex = 0;
                
                // Add procedure lines
                $('.procedure-line').each(function() {
                    const element = $(this).find('[name*="[element]"]').val();
                    const quantite = $(this).find('[name*="[quantite]"]').val();
                    const prixU = $(this).find('[name*="[prix_u]"]').val();
                    
                    if (element && quantite && prixU) {
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][element]`,
                            'value': element
                        }));
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][quantite]`,
                            'value': quantite
                        }));
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][prix_u]`,
                            'value': prixU
                        }));
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][type]`,
                            'value': 'procedure'
                        }));
                        lineIndex++;
                    }
                });
                
                // Add product lines
                $('.product-line').each(function() {
                    const element = $(this).find('[name*="[element]"]').val();
                    const quantite = $(this).find('[name*="[quantite]"]').val();
                    const prixU = $(this).find('[name*="[prix_u]"]').val();
                    const type = $(this).find('[name*="[type]"]').val() || 'material';
                    const produitId = $(this).find('.produit-id').val() || '';
                    
                    if (element && quantite && prixU) {
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][element]`,
                            'value': element
                        }));
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][quantite]`,
                            'value': quantite
                        }));
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][prix_u]`,
                            'value': prixU
                        }));
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': `ligneDevi[${lineIndex}][type]`,
                            'value': type
                        }));
                        if (produitId) {
                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': `ligneDevi[${lineIndex}][produit_id]`,
                                'value': produitId
                            }));
                        }
                        lineIndex++;
                    }
                });
                
                // **NEW: Ensure at least one line item**
                if (lineIndex === 0) {
                    alert('Veuillez ajouter au moins un élément au devis');
                    return;
                }
                
                // Debug log
                console.log('Exporting devis with data:', {
                    patient_id: patientId,
                    nom_devis: nomDevis,
                    total_lines: lineIndex,
                    hospitalization: hospitalizationFields
                });
                
                // Append to body and submit
                form.appendTo('body').submit();
            });

            // Hide autocomplete on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).hasClass('procedure-input') && !$(e.target).hasClass('product-input')) {
                    hideAutocomplete();
                }
            });
        }

        /**
         * Show procedure autocomplete
         */
        function showProcedureAutocomplete(input, suggestions) {
            const container = $('#autocomplete-suggestions');
            container.empty();

            if (suggestions.length === 0) {
                hideAutocomplete();
                return;
            }

            const offset = input.offset();
            container.css({
                top: offset.top + input.outerHeight(),
                left: offset.left,
                width: input.outerWidth() * 2,
                display: 'block'
            });

            const index = input.data('index');

            suggestions.forEach(function(element) {
                const item = $(`
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>${element.nom}</strong>
                                ${element.code ? '<span class="badge bg-secondary ms-2">' + element.code + '</span>' : ''}
                            </div>
                            <span class="text-primary">${formatNumber(element.prix_unitaire)} FCFA</span>
                        </div>
                    </a>
                `);

                item.on('click', function(e) {
                    e.preventDefault();
                    selectProcedure(index, element);
                    hideAutocomplete();
                });

                container.append(item);
            });
        }

        /**
         * Show product autocomplete
         */
        function showProductAutocomplete(input, productNames) {
            // This needs enhancement - we need full product data
            // For now, just show names and fetch details on selection
            hideAutocomplete(); // Temporary
        }

        /**
         * Hide autocomplete
         */
        function hideAutocomplete() {
            $('#autocomplete-suggestions').hide().empty();
        }

        /**
         * Select procedure from autocomplete
         */
        function selectProcedure(index, element) {
            const row = $(`.procedure-line[data-index="${index}"]`);
            row.find('.procedure-input').val(element.nom);
            row.find('.prix_u').val(element.prix_unitaire);
            
            const quantite = parseFloat(row.find('.quantite').val()) || 1;
            row.find('.quantite').trigger('change');
        }

        /**
         * Calculate hospitalization total
         */
        function calculateHospitalization() {
            const prixChambre = (parseFloat($('#nbr_chambre').val()) || 0) * 
                               (parseFloat($('#pu_chambre').val()) || 0);
            const prixVisite = (parseFloat($('#nbr_visite').val()) || 0) * 
                              (parseFloat($('#pu_visite').val()) || 0);
            const prixAmiJour = (parseFloat($('#nbr_ami_jour').val()) || 0) * 
                               (parseFloat($('#pu_ami_jour').val()) || 0);

            $('#prix_chambre').val(formatNumber(prixChambre));
            $('#prix_visite').val(formatNumber(prixVisite));
            $('#prix_ami_jour').val(formatNumber(prixAmiJour));

            const totalHosp = prixChambre + prixVisite + prixAmiJour;
            $('#total_hospitalisation').text(formatNumber(totalHosp));

            calculateTotals();
        }

        /**
         * Calculate all totals
         */
        function calculateTotals() {
            // Calculate procedures total
            let totalProcedures = 0;
            $('.procedure-line .prix-total').each(function() {
                totalProcedures += parseFloat($(this).val()) || 0;
            });
            $('#total_procedures').text(formatNumber(totalProcedures));
            $('#grand_total_procedures').text(formatNumber(totalProcedures));

            // Calculate products total
            let totalProducts = 0;
            $('.product-line .prix-total').each(function() {
                totalProducts += parseFloat($(this).val()) || 0;
            });
            $('#total_products').text(formatNumber(totalProducts));
            $('#grand_total_products').text(formatNumber(totalProducts));

            // Get hospitalization total
            const totalHosp = parseFloat($('#total_hospitalisation').text().replace(/\s/g, '')) || 0;

            // Calculate grand total
            const totalGeneral = totalProcedures + totalProducts + totalHosp;
            $('#total_general').text(formatNumber(totalGeneral));
        }

        /**
         * Format number with spaces
         */
        function formatNumber(num) {
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        }
        
    })(jQuery);
});