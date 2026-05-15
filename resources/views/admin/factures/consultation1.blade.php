@extends('layouts.admin')

@section('title', 'CMCU | Factures Consultation')

@section('content')

<body>
    <div class="wrapper">
        @include('partials.side_bar')
        @include('partials.header')
        
        @can('view', \App\Models\User::class)
        <div class="container_fluid">
            <h1 class="text-center">FACTURES DE CONSULTATION</h1>
            <hr>
            
            <!-- Messages de succès/erreur -->
            @if(session('success'))
            
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="container pt-3">
                <!-- Filtres de recherche -->
                <div class="row">
                    <form action="{{ route('search.date') }}" method="post" class="w-100">
                        @csrf
                        <div class="d-flex align-items-end flex-wrap gap-3">
                            <div>
                                <label for="start-date" class="form-label">Date de début</label>
                                <input type="date" id="start-date" name="start-date" 
                                       class="form-control" 
                                       value="{{ request('start-date', $startDate->format('Y-m-d')) }}">
                            </div>
                            
                            <div>
                                <label for="end-date1" class="form-label">Date de fin</label>
                                <input type="date" id="end-date1" name="end-date" 
                                       class="form-control"
                                       value="{{ request('end-date', $endDate->format('Y-m-d')) }}">
                            </div>
                            
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                @if (isset($factureConsultations))
                <div class="col-lg-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="mb-0">
                            Période du <strong>{{ $startDate->format('d/m/Y') }}</strong> 
                            au <strong>{{ $endDate->format('d/m/Y') }}</strong>
                            <span class="badge bg-primary ms-2">{{ $factureConsultations->total() }} facture(s)</span>
                        </p>
                    </div>
                    
                    <div class="table-responsive">
                        <i class="table_info text-muted">Les montants sont exprimés en <b>FCFA</b></i>
                        
                        <table id="myTable" class="table table-hover table-bordered display" cellspacing="0" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th>N°</th>
                                    <th>ACTIONS</th>
                                    <th>PATIENT</th>
                                    <th>MOTIF</th>
                                    <th>MONTANT</th>
                                    <th>PART ASS.</th>
                                    <th>PART PATIENT</th>
                                    <th>AVANCÉ</th>
                                    <th>RESTE</th>
                                    <th>MODE PAIEMENT</th>
                                    <th>MÉDECIN</th>
                                    <th>DATE</th>
                                    <th>
                                        STATUT
                                        <br>
                                        <select id="statut-filter" class="form-select form-select-sm">
                                            <option value="">Tous les statuts</option>
                                            <option value="soldée">Soldées</option>
                                            <option value="non soldée">Non soldées</option>
                                        </select>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($factureConsultations as $facture)
                                <tr class="{{ $facture->isSoldee() ? 'table-success' : '' }}">
                                    <td><strong>{{ $facture->numero }}</strong></td>
                                    <td>
                                     
                                        <div class="btn-group" role="group">
                                            @if($facture->isProforma())
                                                {{-- Facture non soldée → proforma : bouton orange avec avertissement --}}
                                                <a class="btn btn-warning btn-sm" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Imprimer la PROFORMA (reste: {{ number_format($facture->reste, 0, ',', ' ') }} FCFA)" 
                                                   href="{{ route('factures.consultation_pdf', $facture->id) }}">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            @else
                                                {{-- Facture soldée → vraie facture : bouton vert --}}
                                                <a class="btn btn-success btn-sm" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Imprimer la facture soldée" 
                                                   href="{{ route('factures.consultation_pdf', $facture->id) }}">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @endif
                                            <a  class="btn btn-info btn-sm" href="{{ route('factures.apercu_consultation', $facture->id) }}"
                                                title=" voir l'apercu de la facture" data-bs-toggle= "tooltip">
                                                <i class ="fas fa-eye"></i>
                                            </a>

                                            {{-- Bouton Ajouter un élément — visible dans les DEUX cas --}}
                                            <button type="button"
                                                class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAjoutElement"
                                                data-facture-id="{{ $facture->id }}"
                                                data-facture-numero="{{ $facture->numero }}"
                                                data-patient-id="{{ $facture->patient_id }}"
                                                title="Ajouter un élément à la facture"
                                                data-medecin="{{ $facture->medecin_r ?? '' }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            
                                            @can('update', $facture)
                                                @if($facture->isModifiable())
                                                    <!-- Bouton de modification (bleu) pour factures non soldées -->
                                                    <button type="button" 
                                                            class="btn btn-info btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            title="Modifier la facture" 
                                                            data-bs-target="#edit_facture_modal" 
                                                            data-id-facture="{{ $facture->id }}" 
                                                            data-nom="{{ $facture->patient_display_name }}" 
                                                            data-montant="{{ $facture->montant }}" 
                                                            data-reste="{{ $facture->reste }}" 
                                                            data-mode_paiement="{{ $facture->mode_paiement }}" 
                                                            data-prise_en_charge="{{ optional($facture->patient)->prise_en_charge ?? 0 }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    @can('delete', $facture)
                                                        <!-- Bouton de suppression pour factures non soldées -->
                                                        <form action="{{ route('factures.destroy', $facture->id) }}" 
                                                            method="post" 
                                                            class="d-inline">
                                                            @csrf 
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-danger btn-sm" 
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Supprimer la facture" 
                                                                    onclick="return confirm('Voulez-vous vraiment supprimer cette facture ?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @else
                                                    <!-- Boutons désactivés pour factures soldées -->
                                                    <button class="btn btn-secondary btn-sm" 
                                                            disabled 
                                                            data-bs-toggle="tooltip" 
                                                            title="Modification impossible : facture soldée">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    @can('delete', $facture)
                                                    <button class="btn btn-secondary btn-sm" 
                                                            disabled 
                                                            data-bs-toggle="tooltip" 
                                                            title="Suppression impossible : facture soldée">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @endcan
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                    <td>{{ $facture->patient_display_name }}</td>
                                    <td>{{ $facture->details_motif ?? 'Consultation' }}</td>
                                    <td class="text-end">{{ number_format($facture->montant, 0, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($facture->assurancec, 0, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($facture->assurec, 0, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($facture->avance, 0, ',', ' ') }}</td>
                                    <td class="text-end">
                                        <strong class="{{ $facture->reste > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($facture->reste, 0, ',', ' ') }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{ $facture->mode_paiement === 'bon de prise en charge' ? 'BPC' : ucfirst($facture->mode_paiement) }}
                                        @if($facture->mode_paiement_info_sup)
                                            @foreach (preg_split("/[\/]{2} /", $facture->mode_paiement_info_sup, 0, PREG_SPLIT_NO_EMPTY) as $info_sup)
                                                <br><small class="text-muted">{{ $info_sup }}</small>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $facture->medecin_r }}</td>
                                    <td style="white-space: nowrap">{{ $facture->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($facture->isSoldee())
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Soldée
                                            </span>
                                            @if($facture->is_printed && $facture->printed_at)
                                                <br><small class="text-muted">
                                                    Imprimée le {{ $facture->printed_at->format('d/m/Y') }}
                                                </small>
                                            @else
                                                <br><small class="text-muted fst-italic">  </small>
                                            @endif
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exclamation-circle me-1"></i>Non soldée
                                            </span>
                                            <br><small class="text-dark fst-italic">
                                                <i class="fas fa-file-invoice me-1"></i>Proforma
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                  @include('admin.factures.partials.modal-ajouter-element')
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">TOTAL:</th>
                                    <th class="text-end">{{ number_format($factureConsultations->sum('montant'), 0, ',', ' ') }}</th>
                                    <th class="text-end">{{ number_format($factureConsultations->sum('assurancec'), 0, ',', ' ') }}</th>
                                    <th class="text-end">{{ number_format($factureConsultations->sum('assurec'), 0, ',', ' ') }}</th>
                                    <th class="text-end">{{ number_format($factureConsultations->sum('avance'), 0, ',', ' ') }}</th>
                                    <th class="text-end text-danger">{{ number_format($factureConsultations->sum('reste'), 0, ',', ' ') }}</th>
                                    <th colspan="4"></th>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <!-- Formulaire pour imprimer le bilan -->
                        <form class="mb-3 mt-4" method="POST" action="{{ route('bilan_consultation.pdf') }}">
                            @csrf
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-file-pdf me-2"></i>Générer un bilan journalier
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label for="day" class="form-label">Date</label>
                                            <select name="day" id="day" class="form-select" required>
                                                <option value="">Choisir une date</option>
                                                @foreach($lists as $list)
                                                    <option value="{{ $list }}">{{ Carbon\Carbon::parse($list)->format('d/m/Y') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="service" class="form-label">Service</label>
                                            <select name="service" id="service" class="form-select" required>
                                                <option value="Tout" selected>Tous les services</option>
                                                <option value="Consultation">Consultation</option>
                                                <option value="Acte">Acte</option>
                                                <option value="Examen">Examen</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-print me-2"></i>Imprimer le bilan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Pagination 
                        <div class="d-flex justify-content-center">
                            {{ $factureConsultations->links() }}
                        </div>-->
                    </div>
                </div>
                @endif 
            </div>
        </div>
        
        <!-- Modal de modification -->
        <div id="edit_facture_modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="edit_facture_modallabel">Nouveau versement</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="edit_facture_form" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Important:</strong> Cette facture n'est pas encore soldée. Vous pouvez la modifier.
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="montant" class="form-label">Montant total</label>
                                        <input name="montant" id="montant" class="form-control" type="number" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="part_patient" class="form-label">Part patient</label>
                                        <input name="part_patient" id="part_patient" class="form-control" type="number" min="0" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reste" class="form-label">Reste à payer</label>
                                        <input name="reste" id="reste" class="form-control" type="number" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="percu" class="form-label">
                                            Montant versé <span class="text-danger">*</span>
                                        </label>
                                        <input name="percu" id="percu" class="form-control" type="number" min="0" placeholder="0" required>
                                        <small class="text-muted">Entrez le montant que le patient verse maintenant</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mode_paiement" class="form-label">Mode de paiement</label>
                                        <select name="mode_paiement" id="mode_paiement" class="form-select" required>
                                            <optgroup label="Monnaie électronique">
                                                <option value="orange money">Orange Money</option>
                                                <option value="mtn mobile money">MTN Mobile Money</option>
                                            </optgroup>
                                            <optgroup label="Autres moyens">
                                                <option value="espèce">Espèce</option>
                                                <option value="chèque">Chèque</option>
                                                <option value="virement">Virement</option>
                                                <option value="bon de prise en charge">Bon de prise en charge</option>
                                                <option value="autre">Autre</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    
                                    <!-- Champs conditionnels pour chèque -->
                                    <div id="cheque_fields" class="d-none">
                                        <div class="mb-3">
                                            <label for="num_cheque" class="form-label">N° Chèque</label>
                                            <input type="text" name="num_cheque" id="num_cheque" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="emetteur_cheque" class="form-label">Émetteur</label>
                                            <input type="text" name="emetteur_cheque" id="emetteur_cheque" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="banque_cheque" class="form-label">Banque</label>
                                            <input type="text" name="banque_cheque" id="banque_cheque" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <!-- Champ conditionnel pour BPC -->
                                    <div id="bpc_fields" class="d-none">
                                        <div class="mb-3">
                                            <label for="emetteur_bpc" class="form-label">Émetteur BPC</label>
                                            <input type="text" name="emetteur_bpc" id="emetteur_bpc" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Fermer
                            </button>
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save me-1"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        @endcan
    </div>
</body>

@endsection
      
@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    $(document).ready(function() {
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // DataTables
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        
        // Gestion du modal de modification
        $('#edit_facture_modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id_facture = button.data('id-facture');
            var mode_paiement = button.data('mode_paiement');
            var montant_facture = button.data('montant');
            var reste = button.data('reste');
            var prise_en_charge = button.data('prise_en_charge');
            
            $('#montant').val(montant_facture);
            $('#mode_paiement').val(mode_paiement);
            $('#reste').val(reste);
            
            if (isNaN(prise_en_charge)) {
                $('#montant').attr('data-prise_en_charge', 0);
                $('#part_patient').val(montant_facture);
            } else {
                $('#montant').attr('data-prise_en_charge', prise_en_charge);
                $('#part_patient').val(montant_facture * (100 - prise_en_charge) / 100);
            }
            
            $('#edit_facture_form').attr("action", "{{ url('admin/factures-consultation') }}" + "/" + id_facture);
        });
        
        // Calcul automatique de la part patient
        $('#montant').on('change', function() {
            var PEC = $(this).data('prise_en_charge');
            var montant = $(this).val();
            $('#part_patient').val(montant * (100 - PEC) / 100);
        });
        
        // Afficher/masquer les champs selon le mode de paiement
        $('#mode_paiement').on('change', function() {
            var value = $(this).val();
            $('#cheque_fields').addClass('d-none');
            $('#bpc_fields').addClass('d-none');
            
            if (value === 'chèque') {
                $('#cheque_fields').removeClass('d-none');
            } else if (value === 'bon de prise en charge') {
                $('#bpc_fields').removeClass('d-none');
            }
        });
        
        // Auto-dismiss des alertes après 5 secondes
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Filtrage par statut
        $('#statut-filter').on('change', function() {
            var selectedValue = $(this).val();
            var table = $('#myTable');
            var rows = table.find('tbody tr');

            rows.each(function() {
                var row = $(this);
                var statusCell = row.find('td:last-child');
                var badge = statusCell.find('.badge');
                var isSoldee = badge.hasClass('bg-success');
                var isNonSoldee = badge.hasClass('bg-warning');

                if (selectedValue === '') {
                    // Afficher toutes les lignes
                    row.show();
                } else if (selectedValue === 'soldée' && isSoldee) {
                    row.show();
                } else if (selectedValue === 'non soldée' && isNonSoldee) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        });
    });
});
</script>
@endsection