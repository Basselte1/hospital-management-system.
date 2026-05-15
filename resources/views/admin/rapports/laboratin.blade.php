@extends('layouts.admin')

@section('title', 'Rapport Laboratin - ' . $laboratin->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rapports.index') }}">Rapports</a></li>
                        <li class="breadcrumb-item active">Laboratin {{ $laboratin->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Rapport Laboratin: {{ $laboratin->name }}</h4>
            </div>
        </div>
    </div>

    {{-- Filtres dates --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET" action="{{ route('rapports.laboratin', $laboratin->id) }}">
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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-vial font-size-24"></i></div>
                    <h5>{{ $totalExamens }}</h5>
                    <p>Examens Biologiques</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="float-end"><i class="fas fa-x-ray font-size-24"></i></div>
                    <h5>{{ $totalImageries }}</h5>
                    <p>Imageries</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Onglets --}}
    <ul class="nav nav-tabs" id="laboratinTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="examens-tab" data-bs-toggle="tab" href="#examens" role="tab">Examens</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="imageries-tab" data-bs-toggle="tab" href="#imageries" role="tab">Imageries</a>
        </li>
    </ul>

    <div class="tab-content" id="laboratinTabContent">
        {{-- Examens --}}
        <div class="tab-pane fade show active" id="examens" role="tabpanel">
            @if($examens->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Examen</th>
                                    <th>Résultats</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($examens as $examen)
                                <tr>
                                    <td>{{ $examen->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $examen->prescription->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $examen->type_examen }}</td>
                                    <td>{{ Str::limit($examen->resultats, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-vial fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun examen trouvé pour cette période.</p>
            </div>
            @endif
        </div>

        {{-- Imageries --}}
        <div class="tab-pane fade" id="imageries" role="tabpanel">
            @if($imageries->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Type Imagerie</th>
                                    <th>Compte-rendu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($imageries as $imagerie)
                                <tr>
                                    <td>{{ $imagerie->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $imagerie->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $imagerie->type_imagerie }}</td>
                                    <td>{{ Str::limit($imagerie->compte_rendu, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-x-ray fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucune imagerie trouvée pour cette période.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

