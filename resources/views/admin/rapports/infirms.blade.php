@extends('layouts.admin')

@section('title', 'Rapport Infirmier - ' . $infirmier->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rapports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Infirmier {{ $infirmier->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Rapport Infirmier: {{ $infirmier->name }}</h4>
            </div>
        </div>
    </div>

    {{-- Filtres dates --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET" action="{{ route('rapports.infirmier', $infirmier->id) }}">
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
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-notes-medical font-size-24"></i></div>
                    <h5>{{ $totalSoins }}</h5>
                    <p>Soins Infirmiers</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-pills font-size-24"></i></div>
                    <h5>{{ $totalAdminPM }}</h5>
                    <p>Admin PM</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Onglets --}}
    <ul class="nav nav-tabs" id="infirmierTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="soins-tab" data-bs-toggle="tab" href="#soins" role="tab">Soins Infirmiers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="adminpm-tab" data-bs-toggle="tab" href="#adminpm" role="tab">Administrations PM</a>
        </li>
    </ul>

    <div class="tab-content" id="infirmierTabContent">
        {{-- Soins Infirmiers --}}
        <div class="tab-pane fade show active" id="soins" role="tabpanel">
            @if($soins->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Type Soin</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($soins as $soin)
                                <tr>
                                    <td>{{ $soin->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $soin->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $soin->type_soin }}</td>
                                    <td>{{ Str::limit($soin->notes, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-notes-medical fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun soin infirmier trouvé pour cette période.</p>
            </div>
            @endif
        </div>

        {{-- Admin PM --}}
        <div class="tab-pane fade" id="adminpm" role="tabpanel">
            @if($adminPM->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Médicament</th>
                                    <th>Dosage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($adminPM as $admin)
                                <tr>
                                    <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $admin->prescription_medicale->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $admin->prescription_medicale->medicament }}</td>
                                    <td>{{ $admin->dosage_administre }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucune administration PM trouvée pour cette période.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

