@extends('layouts.admin')
@section('title', 'Facturation - Actes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Factures Actes</h4>
            </div>
        </div>
    </div>

    @include('partials.flash')

    {{-- KPIs --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="float-end">{{ $factures->total() }}</div>
                    <span>Total Actes</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="float-end">{{ number_format($factures->sum('montant_total'), 0, ',', ' ') }} FCFA</div>
                    <span>Montant Total</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="float-end">{{ number_format($factures->sum('avance'), 0, ',', ' ') }} FCFA</div>
                    <span>Encaissé</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="float-end">{{ number_format($factures->sum('reste'), 0, ',', ' ') }} FCFA</div>
                    <span>Reste à Payer</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <form method="GET">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher patient..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="{{ route('facturation.actes.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Patient</th>
                            <th>Montant</th>
                            <th>Avance</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($factures as $facture)
                        <tr>
                            <td><strong>{{ $facture->numero }}</strong></td>
                            <td>{{ $facture->patient_name }} ({{ $facture->patient->numero_dossier ?? '' }})</td>
                            <td>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($facture->avance, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="{{ $facture->reste == 0 ? 'badge bg-success' : 'badge bg-warning' }}">
                                    {{ number_format($facture->reste, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $facture->statut == 'Soldée' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $facture->statut }}
                                </span>
                            </td>
                            <td>{{ $facture->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('facturation.actes.show', $facture) }}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('facturation.actes.print', $facture) }}" class="btn btn-sm btn-success">Imprimer</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Aucune facture d'acte trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $factures->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- Bouton Créer --}}
    <div class="text-end mt-3">
        <a href="{{ route('facturation.actes.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Nouvelle Facture Acte
        </a>
    </div>
</div>
@endsection

