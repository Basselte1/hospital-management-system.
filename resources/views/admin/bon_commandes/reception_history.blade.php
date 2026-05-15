{{-- resources/views/admin/bon_commandes/reception_history.blade.php --}}
@extends('layouts.master')

@section('title', 'Historique des Réceptions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i> Historique des Réceptions de Stock
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('bon-commandes.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Retour aux Bons de Commande
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    {{-- Filters --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par BC, fournisseur...">
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="dateFrom" class="form-control" placeholder="Date début">
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="dateTo" class="form-control" placeholder="Date fin">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary btn-block" onclick="resetFilters()">
                                <i class="fas fa-redo"></i> Réinitialiser
                            </button>
                        </div>
                    </div>

                    {{-- Statistics Cards --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalReceptions ?? 0 }}</h3>
                                    <p>Total Réceptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $completeReceptions ?? 0 }}</h3>
                                    <p>Réceptions Complètes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $partialReceptions ?? 0 }}</h3>
                                    <p>Réceptions Partielles</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $thisMonthReceptions ?? 0 }}</h3>
                                    <p>Ce Mois</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Receptions Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="receptionsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>N° BC</th>
                                    <th>Date Réception</th>
                                    <th>Fournisseur</th>
                                    <th>Type</th>
                                    <th>Reçu Par</th>
                                    <th>Articles</th>
                                    <th>Montant</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receptions ?? [] as $reception)
                                <tr>
                                    <td>
                                        <strong>{{ $reception->bonCommande->numero_bon ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($reception->reception_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $reception->bonCommande->fournisseur_nom ?? 'N/A' }}</td>
                                    <td>
                                        @if($reception->reception_type === 'complete')
                                            <span class="badge badge-success">Complète</span>
                                        @else
                                            <span class="badge badge-warning">Partielle</span>
                                        @endif
                                    </td>
                                    <td>{{ $reception->receivedBy->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $reception->items_count ?? 0 }} produits</span>
                                    </td>
                                    <td><strong>{{ number_format($reception->total_amount ?? 0, 0, ',', ' ') }} FCFA</strong></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('bon-commandes.reception-details', $reception->id) }}" 
                                               class="btn btn-info" 
                                               title="Voir Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-primary" 
                                                    onclick="printReception({{ $reception->id }})"
                                                    title="Imprimer">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Aucune réception de stock enregistrée</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(isset($receptions) && $receptions->hasPages())
                    <div class="mt-3">
                        {{ $receptions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printReception(id) {
    window.open(`/admin/bon-commandes/reception/${id}/print`, '_blank');
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    filterTable();
}

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const table = document.getElementById('receptionsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let row of rows) {
        let showRow = true;
        const text = row.textContent.toLowerCase();
        
        // Search filter
        if (searchTerm && !text.includes(searchTerm)) {
            showRow = false;
        }
        
        // Date filters (implementation depends on your date format)
        // Add date filtering logic here if needed
        
        row.style.display = showRow ? '' : 'none';
    }
}

document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('dateFrom').addEventListener('change', filterTable);
document.getElementById('dateTo').addEventListener('change', filterTable);
</script>
@endpush
@endsection

























