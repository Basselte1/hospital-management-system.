@extends('layouts.admin')
@section('title', 'CMCU | Liste Complète des Examens')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">📋 Tous les Examens du Système</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Tous les objets "examen" dans FactureConsultation</h5>
            <a href="{{ route('facturation.examens.index') }}" class="btn btn-primary">
                ← Retour Factures Examens
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Numéro</th>
                            <th>Motif</th>
                            <th>Patient</th>
                            <th>Montant</th>
                            <th>Reste</th>
                            <th>Date</th>
                            <th>Lignes Examens</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $examens = \App\Models\FactureConsultation::with(['patient', 'lignes'])
                                ->where(function($q){
                                    $q->where('motif', 'examen')
                                      ->orWhereHas('lignes', fn($q2)=>$q2->where('type_acte', 'like', '%examen%'));
                                })
                                ->latest()
                                ->paginate(50);
                        @endphp
                        
                        @forelse($examens as $facture)
                        <tr>
                            <td><strong>{{ $facture->id }}</strong></td>
                            <td>{{ $facture->numero }}</td>
                            <td>
                                <span class="badge bg-info">{{ $facture->motif }}</span>
@if($facture->details_motif)
                                    <br><small>{{ substr($facture->details_motif, 0, 50) }}{{ strlen($facture->details_motif) > 50 ? '...' : '' }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $facture->patient ? $facture->patient->name.' '.$facture->patient->prenom : $facture->patient_name }}
@if($facture->patient && $facture->patient->numero_dossier)
                                    <br><small class="text-muted">N°{{ $facture->patient->numero_dossier }}</small>
                                @endif
                            </td>
                            <td class="fw-bold">{{ number_format($facture->montant, 0, ',', ' ') }} FCFA</td>
                            <td class="{{ $facture->reste > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                {{ number_format($facture->reste ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                            <td>{{ $facture->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($facture->lignes->count())
                                    <ul class="mb-0 small">
@foreach(($facture->lignes ?? collect())->where('type_acte', 'like', '%examen%') as $ligne)
                                            <li>
                                                <span class="badge bg-secondary me-1">{{ $ligne->type_acte }}</span>
                                                {{ \Illuminate\Support\Str::limit($ligne->libelle, 30) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Aucune ligne examen</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">Aucun examen trouvé</h5>
                                <p class="text-muted mb-0">Aucune facture avec motif='examen' ou lignes type_acte like '%examen%'</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $examens->links() }}
            
            <div class="mt-4 p-3 bg-light">
                <h6>Résumé:</h6>
                <div>Total factures: <strong>{{ $examens->total() }}</strong></div>
                <div>Montant total: <strong>{{ number_format($examens->sum('montant'), 0, ',', ' ') }} FCFA</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection

