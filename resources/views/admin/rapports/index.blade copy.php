@extends('layouts.admin')

@section('title', 'Rapports - CMCU App')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rapports</li>
                    </ol>
                </div>
                <h4 class="page-title">Rapports</h4>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- RAPPORT MEDECIN --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-white mb-1 font-size-16">Rapport Médecin</p>
                            <form method="GET" action="{{ route('rapports.medecin', ['id' => '__ID__']) }}" class="rapports-form">
                                <div class="mb-2">
                                    <select name="medecin_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                                        <option value="">Sélectionner un médecin...</option>
                                        @foreach($medecins as $medecin)
                                        <option value="{{ $medecin->id }}">{{ $medecin->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <i class="fas fa-user-md text-white-50 align-self-center font-size-24"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- RAPPORT PATIENT --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-white mb-1 font-size-16">Rapport Patient (Bilan)</p>
                            <form method="GET" action="{{ route('rapports.patient', ['id' => '__ID__']) }}" class="rapports-form">
                                <div class="mb-2">
                                    <select name="patient_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                                        <option value="">Sélectionner un patient...</option>
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} {{ $patient->prenom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <i class="fas fa-user-injured text-white-50 align-self-center font-size-24"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- RAPPORT INFIRMIER --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-white mb-1 font-size-16">Rapport Infirmier</p>
                            <form method="GET" action="{{ route('rapports.infirmier', ['id' => '__ID__']) }}" class="rapports-form">
                                <div class="mb-2">
                                    <select name="infirmier_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                                        <option value="">Sélectionner un infirmier...</option>
                                        @foreach($infirmiers as $infirmier)
                                        <option value="{{ $infirmier->id }}">{{ $infirmier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <i class="fas fa-user-nurse text-white-50 align-self-center font-size-24"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- RAPPORT LABORATIN --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-white mb-1 font-size-16">Rapport Laboratin</p>
                            <form method="GET" action="{{ route('rapports.laboratin', ['id' => '__ID__']) }}" class="rapports-form">
                                <div class="mb-2">
                                    <select name="laboratin_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                                        <option value="">Sélectionner laborantin...</option>
                                        @foreach($laboratins as $laboratin)
                                        <option value="{{ $laboratin->id }}">{{ $laboratin->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <i class="fas fa-flask text-white-50 align-self-center font-size-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.rapports-form select').forEach(select => {
        select.addEventListener('change', function() {
            const id = this.value;
            if (id) {
                const action = this.closest('form').action.replace('__ID__', id);
                this.closest('form').action = action;
                this.closest('form').submit();
            }
        });
    });
    </script>
</div>
@endsection

