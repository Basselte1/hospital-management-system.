@extends('layouts.admin')

@section('title', 'CMCU | Vente Pharmacie')

@section('content')

<style>
    .patient-search-wrapper {
        position: relative;
    }
    
    .patient-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 400px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .patient-item {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .patient-item:hover {
        background-color: #f8f9fa;
    }
    
    .patient-item.selected {
        background-color: #e3f2fd;
    }
    
    .patient-name {
        font-weight: 600;
        color: #333;
    }
    
    .patient-info {
        font-size: 0.85rem;
        color: #666;
    }
    
    .ordonance-date {
        font-size: 0.8rem;
        color: #007bff;
    }
    
    .prescription-details {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .medication-row {
        background: white;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 6px;
        border-left: 4px solid #28a745;
    }
    
    .medication-row.not-found {
        border-left-color: #dc3545;
    }
    
    .medication-row.low-stock {
        border-left-color: #ffc107;
    }
    
    .stock-warning {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 10px;
    }
    
    .stock-warning.not-found {
        background-color: #dc3545;
        color: white;
    }
    
    .stock-warning.low-stock {
        background-color: #ffc107;
        color: #000;
    }
    
    .stock-info {
        color: #28a745;
        font-weight: 600;
    }
    
    .add-to-cart-btn {
        margin-top: 10px;
    }
</style>

<div class="wrapper">
    @include('partials.side_bar')
    @include('partials.header')
    
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-prescription-bottle-alt"></i> Vente Pharmacie - Patients</h3>
                        </div>
                        <div class="card-body">
                            <!-- Patient Search -->
                            <div class="row mb-4">
                                <div class="col-md-8 offset-md-2">
                                    <div class="patient-search-wrapper">
                                        <label for="patient-search" class="form-label">
                                            <i class="fas fa-search"></i> Rechercher un patient
                                        </label>
                                        <input 
                                            type="text" 
                                            id="patient-search" 
                                            class="form-control form-control-lg" 
                                            placeholder="Cliquez pour voir la liste des patients avec ordonnances..."
                                            autocomplete="off"
                                        />
                                        <div class="patient-list" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selected Patient Info -->
                            <div id="selected-patient-info" style="display: none;">
                                <div class="alert alert-info">
                                    <h5><i class="fas fa-user"></i> Patient sélectionné: <span id="patient-name-display"></span></h5>
                                    <p class="mb-0">Numéro dossier: <strong id="patient-numero"></strong></p>
                                </div>
                            </div>
                            
                            <!-- Prescription Details -->
                            <div id="prescription-container" style="display: none;">
                                <div class="prescription-details">
                                    <h4 class="mb-3">
                                        <i class="fas fa-prescription"></i> Ordonnance 
                                        <span id="ordonance-date" class="ordonance-date"></span>
                                    </h4>
                                    <div id="medications-list"></div>
                                    
                                    <div class="mt-4 text-end">
                                        <button type="button" class="btn btn-success btn-lg" id="validate-sale">
                                            <i class="fas fa-check-circle"></i> Valider la vente
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- No prescription message -->
                            <div id="no-prescription" class="alert alert-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i> Ce patient n'a pas d'ordonnance récente.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/js/jquery-2.2.0.min.js') }}"></script>
<script>
$(document).ready(function() {
    let selectedPatientId = null;
    let selectedOrdonanceId = null;
    let selectedItems = [];
    
    // Load patients with recent prescriptions
    function loadPatients(searchTerm = '') {
        $.ajax({
            url: '{{ route("pharmacie.patient.prescriptions") }}',
            method: 'GET',
            data: { search: searchTerm },
            success: function(patients) {
                displayPatientList(patients);
            },
            error: function() {
                alert('Erreur lors du chargement des patients');
            }
        });
    }
    
    // Display patient list
    function displayPatientList(patients) {
        const listDiv = $('.patient-list');
        listDiv.empty();
        
        if (patients.length === 0) {
            listDiv.html('<div class="patient-item">Aucun patient trouvé</div>');
        } else {
            $.each(patients, function(index, patient) {
                const patientDiv = $('<div class="patient-item"></div>');
                patientDiv.attr('data-patient-id', patient.id);
                patientDiv.attr('data-ordonance-id', patient.last_ordonance_id);
                
                const nameDiv = $('<div class="patient-name"></div>').text(patient.name + ' ' + patient.prenom);
                const infoDiv = $('<div class="patient-info"></div>').html(
                    'Dossier: ' + patient.numero_dossier + 
                    (patient.telephone ? ' | Tél: ' + patient.telephone : '')
                );
                const dateDiv = $('<div class="ordonance-date"></div>').text(
                    'Dernière ordonnance: ' + patient.ordonance_date
                );
                
                patientDiv.append(nameDiv).append(infoDiv).append(dateDiv);
                listDiv.append(patientDiv);
            });
        }
        
        listDiv.show();
    }
    
    // Patient search input events
    $('#patient-search').on('focus click', function() {
        loadPatients($(this).val());
    });
    
    $('#patient-search').on('input', function() {
        const searchTerm = $(this).val();
        if (searchTerm.length >= 2 || searchTerm.length === 0) {
            loadPatients(searchTerm);
        }
    });
    
    // Select patient
    $(document).on('click', '.patient-item', function() {
        selectedPatientId = $(this).data('patient-id');
        selectedOrdonanceId = $(this).data('ordonance-id');
        
        const patientName = $(this).find('.patient-name').text();
        const patientNumero = $(this).find('.patient-info').text().split('Dossier: ')[1].split(' |')[0];
        
        $('.patient-list').hide();
        $('#patient-search').val(patientName);
        
        $('#patient-name-display').text(patientName);
        $('#patient-numero').text(patientNumero);
        $('#selected-patient-info').show();
        
        loadPrescription(selectedOrdonanceId);
    });
    
    // Load prescription details with stock verification
    function loadPrescription(ordonanceId) {
        $.ajax({
            url: '{{ route("pharmacie.prescription-details", ":id") }}'.replace(':id', ordonanceId),
            method: 'GET',
            success: function(data) {
                if (data.medications && data.medications.length > 0) {
                    displayPrescription(data);
                    $('#prescription-container').show();
                    $('#no-prescription').hide();
                } else {
                    $('#prescription-container').hide();
                    $('#no-prescription').show();
                }
            },
            error: function() {
                alert('Erreur lors du chargement de l\'ordonnance');
            }
        });
    }
    
    // Display prescription with stock info
    function displayPrescription(data) {
        const medicationsDiv = $('#medications-list');
        medicationsDiv.empty();
        selectedItems = [];
        
        $('#ordonance-date').text('du ' + data.ordonance_date);
        
        $.each(data.medications, function(index, med) {
            const medDiv = $('<div class="medication-row"></div>');
            
            let stockClass = '';
            let stockWarning = '';
            
            if (!med.produit_found) {
                medDiv.addClass('not-found');
                stockWarning = '<span class="stock-warning not-found"><i class="fas fa-times-circle"></i> Aucun médicament trouvé</span>';
            } else if (med.stock_insuffisant) {
                medDiv.addClass('low-stock');
                stockWarning = '<span class="stock-warning low-stock"><i class="fas fa-exclamation-triangle"></i> Rupture de stock (Disponible: ' + med.stock_disponible + ')</span>';
            } else {
                stockWarning = '<span class="stock-info"><i class="fas fa-check-circle"></i> Stock: ' + med.stock_disponible + '</span>';
            }
            
            const medContent = `
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <strong>${med.medicament}</strong>
                        ${stockWarning}
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Posologie:</small><br>
                        ${med.description}
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Quantité prescrite:</small><br>
                        <strong>${med.quantite}</strong>
                    </div>
                    <div class="col-md-3">
                        ${med.produit_found && !med.stock_insuffisant ? `
                            <div class="input-group">
                                <input type="number" class="form-control quantity-input" 
                                       data-index="${index}" 
                                       data-produit-id="${med.produit_id}"
                                       data-prix="${med.prix_unitaire}"
                                       data-designation="${med.medicament}"
                                       value="${Math.min(med.quantite, med.stock_disponible)}" 
                                       min="1" 
                                       max="${med.stock_disponible}">
                                <button class="btn btn-success add-item" data-index="${index}">
                                    <i class="fas fa-cart-plus"></i> Ajouter
                                </button>
                            </div>
                            <small class="text-muted">Prix unitaire: ${med.prix_unitaire} FCFA</small>
                        ` : `
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-ban"></i> Non disponible
                            </button>
                        `}
                    </div>
                </div>
            `;
            
            medDiv.html(medContent);
            medicationsDiv.append(medDiv);
            
            // Pre-add available items to cart
            if (med.produit_found && !med.stock_insuffisant) {
                selectedItems.push({
                    produit_id: med.produit_id,
                    designation: med.medicament,
                    quantite: Math.min(med.quantite, med.stock_disponible),
                    prix_unitaire: med.prix_unitaire
                });
            }
        });
    }
    
    // Add item to cart
    $(document).on('click', '.add-item', function() {
        const index = $(this).data('index');
        const input = $(`.quantity-input[data-index="${index}"]`);
        const quantity = parseInt(input.val());
        
        if (quantity > 0) {
            const item = {
                produit_id: input.data('produit-id'),
                designation: input.data('designation'),
                quantite: quantity,
                prix_unitaire: parseFloat(input.data('prix'))
            };
            
            // Update or add item
            const existingIndex = selectedItems.findIndex(i => i.produit_id === item.produit_id);
            if (existingIndex >= 0) {
                selectedItems[existingIndex] = item;
            } else {
                selectedItems.push(item);
            }
            
            $(this).html('<i class="fas fa-check"></i> Ajouté').removeClass('btn-success').addClass('btn-info');
            setTimeout(() => {
                $(this).html('<i class="fas fa-cart-plus"></i> Ajouter').removeClass('btn-info').addClass('btn-success');
            }, 1000);
        }
    });
    
    // Validate sale
    $('#validate-sale').on('click', function() {
        if (selectedItems.length === 0) {
            alert('Aucun article sélectionné');
            return;
        }
        
        const saleData = {
            patient_id: selectedPatientId,
            ordonance_id: selectedOrdonanceId,
            items: selectedItems,
            _token: '{{ csrf_token() }}'
        };
        
        $.ajax({
            url: '{{ route("pharmacie.create-sale") }}',
            method: 'POST',
            data: saleData,
            success: function(response) {
                alert('Vente créée avec succès! Numéro: ' + response.numero_vente);
                // Redirect to payment page
                window.location.href = '{{ route("pharmacie.sales.list") }}?statut_paiement=en_attente';
            },
            error: function(xhr) {
                alert('Erreur lors de la création de la vente: ' + (xhr.responseJSON?.message || 'Erreur inconnue'));
            }
        });
    });
    
    // Close patient list when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.patient-search-wrapper').length) {
            $('.patient-list').hide();
        }
    });
});
</script>

@endsection