@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Factures Journalières</h3>
                </div>
                <div class="card-body">
                    {{-- Filtres --}}
                    <form method="GET" class="row mb-4">
                        <div class="col-md-3">
                            <label>Patient</label>
                            <select name="patient_id" class="form-control">
                                <option value="">Tous patients</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" {{ request('patient_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} {{ $p->prenom }} ({{ $p->numero_dossier ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Date</label>
                            <input type="date" name="date" value="{{ $date }}" class="form-control">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </form>

                    {{-- Actions --}}
                    @if($dailyFactures->first())
                        <div class="mb-3">
                            <a href="{{ route('patients.index') }}" class="btn btn-secondary">← Patients</a>
                            <form method="POST" action="{{ route('factures.journalieres.generer', $dailyFactures->first()->patient_id) }}" class="d-inline">
                                @csrf
                                <input type="date" name="date_facture" value="{{ now()->format('Y-m-d') }}" class="form-control d-inline-block w-auto" style="width: 160px;">
                                <button type="submit" class="btn btn-success">Générer Facture {{ now()->format('d/m') }}</button>
                            </form>
                        </div>
                    @endif

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Lignes</th>
                                    <th>Total</th>
                                    <th>Avance</th>
                                    <th>Reste</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailyFactures as $f)
                                    <tr>
                                        <td><strong>{{ $f->date_facture->format('d/m/Y') }}</strong></td>
                                        <td>{{ $f->patient->name ?? '[Supprimé]' }} {{ $f->patient->prenom }}</td>
                                        <td>{{ $f->medecin_principal->name ?? '-' }}</td>
                                        <td>
                                            <details>
                                                <summary>{{ $f->lignes_count }} lignes</summary>
                                                <ul class="small">
                                                    @foreach($f->lignes as $l)
                                                        <li>{{ $l->type }}: {{ $l->description }} ({{ $l->quantite }} x {{ number_format($l->montant_unitaire,0,',',' ') }} = {{ number_format($l->montant_total,0,',',' ') }})</li>
                                                    @endforeach
                                                </ul>
                                            </details>
                                        </td>
                                        <td><strong>{{ number_format($f->total_montant, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>{{ number_format($f->total_avance, 0, ',', ' ') }} FCFA</td>
                                        <td class="fw-bold {{ $f->total_reste > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($f->total_reste, 0, ',', ' ') }} FCFA</td>
                                        <td>{!! $f->statut_badge !!}</td>
                                        <td>
                                            @if($f->isImprimable())
                                                <a href="{{ route('factures.journalieres.pdf', $f) }}" class="btn btn-sm btn-info" target="_blank">PDF</a>
                                            @endif
                                            <a href="#" class="btn btn-sm btn-warning">Payer</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i>Aucune facture journalière</i><br>
                                            <a href="{{ route('patients.index') }}">Créer patient pour tester →</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $dailyFactures->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

