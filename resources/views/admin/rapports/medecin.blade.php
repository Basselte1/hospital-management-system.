@extends('layouts.admin')

@section('title', 'Rapport Médecin - ' . $medecin->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rapports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Médecin {{ $medecin->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Rapport Médecin: {{ $medecin->name }}</h4>
            </div>
        </div>
    </div>

    {{-- Filtres dates --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET" action="{{ route('rapports.medecin', $medecin->id) }}">
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
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-calendar-day font-size-24"></i></div>
                    <h5>{{ $rapports->flatten()->count() }}</h5>
                    <p>Consultations</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-money-bill-wave font-size-24"></i></div>
                    <h5>{{ number_format($totalCA, 0, ',', ' ') }} FCFA</h5>
                    <p>CA Total</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Détails par période</h4>
                    @if($rapports->count() > 0)
                        @foreach($rapports as $mois => $moisRapports)
                            <h5 class="mt-4 mb-2">{{ \Carbon\Carbon::createFromFormat('Y-m', $mois)->translatedFormat('MMMM YYYY') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Patient</th>
                                            <th>Montant</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($moisRapports as $rapport)
                                        <tr>
                                            <td>{{ $rapport->date_facture->format('d/m/Y') }}</td>
                                            <td>{{ $rapport->patient->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($rapport->lignes_sum_montant_total ?? 0, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                                <a href="{{ route('factures.journalieres.pdf', $rapport->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-print"></i> PDF
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-4">Aucun rapport trouvé pour cette période.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

