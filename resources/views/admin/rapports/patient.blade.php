@extends('layouts.admin')

@section('title', 'Bilan Patient - ' . $patient->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rapports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Bilan {{ $patient->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Bilan Médical Patient: {{ $patient->name }} {{ $patient->prenom }}</h4>
            </div>
        </div>
    </div>

    {{-- Filtres dates --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET" action="{{ route('rapports.patient', $patient->id) }}">
                <div class="row">
                    <div class="col-md-6">
                        <input type="date" name="start-date" class="form-control" value="{{ $startDate }}" max="{{ $endDate }}">
                    </div>
                    <div class="col-md-6">
                        <input type="date" name="end-date" class="form-control" value="{{ $endDate }}" min="{{ $startDate }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2 w-100">Filtrer</button>
            </form>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-file-invoice font-size-24"></i></div>
                    <h5>{{ $factures->count() }}</h5>
                    <p>Factures</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-coins font-size-24"></i></div>
                    <h5>{{ number_format($totalCumule, 0, ',', ' ') }} FCFA</h5>
                    <p>Montant Total</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Historique Factures Journalières</h4>
                    @if($factures->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Avance</th>
                                        <th>Reste</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($factures as $facture)
                                    <tr>
                                        <td>{{ $facture->date_facture->format('d/m/Y') }}</td>
                                        <td>{{ number_format($facture->total_montant, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($facture->total_avance, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($facture->total_montant - $facture->total_avance, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <a href="{{ route('factures.journalieres.pdf', $facture->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-print"></i> PDF
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Aucune facture trouvée pour cette période.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

