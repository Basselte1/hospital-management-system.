@extends('layouts.admin')

@section('title', 'CMCU | Modifier Facture — ' . $facture->numero)

@section('content')
<div class="wrapper">
    @include('partials.side_bar')
    @include('partials.header')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('factures.consultation') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <h4 class="mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Modifier Facture #{{ $facture->numero }}
                    </h4>
                    <a href="{{ route('factures.apercu_consultation', $facture->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i>Aperçu
                    </a>
                </div>

                {{-- Alerts --}}
                @if($facture->isSoldee() && $facture->is_printed)
                    <div class="alert alert-danger">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Facture déjà soldée</strong> - Modification limitée
                    </div>
                @elseif($facture->reste <= 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Facture soldée - Enregistrement crée une réimpression
                    </div>
                @endif

                {{-- Form --}}
                <div class="card shadow">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-bold">Détails Facture</h5>
                    </div>
                    <form method="POST" action="{{ route('factures.consultation.update', $facture->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Patient Info (read-only) --}}
                            <div class="row mb-4 p-3 bg-light rounded">
                                <div class="col-md-8">
                                    <h6 class="text-muted mb-1"><i class="fas fa-user me-2"></i>Patient</h6>
                                    <strong>{{ $facture->patient->name ?? $facture->patient_name }} 
                                        ({{ $facture->patient->numero_dossier ?? 'N/A' }})</strong>
                                </div>
                                <div class="col-md-4 text-end">
                                    <h6 class="text-muted mb-1">Montant initial</h6>
                                    <span class="h5 text-primary fw-bold">
                                        {{ number_format($facture->montant, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </div>

                            {{-- Row 1: Mode paiement --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mode de paiement <span class="text-danger">*</span></label>
                                    <select name="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="espèce" {{ old('mode_paiement', $facture->mode_paiement) == 'espèce' ? 'selected' : '' }}>Espèce</option>
                                        <option value="chèque" {{ old('mode_paiement', $facture->mode_paiement) == 'chèque' ? 'selected' : '' }}>Chèque</option>
                                        <option value="orange money" {{ old('mode_paiement', $facture->mode_paiement) == 'orange money' ? 'selected' : '' }}>Orange Money</option>
                                        <option value="mtn mobile money" {{ old('mode_paiement', $facture->mode_paiement) == 'mtn mobile money' ? 'selected' : '' }}>MTN Mobile Money</option>
                                        <option value="virement" {{ old('mode_paiement', $facture->mode_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                                        <option value="bon de prise en charge" {{ old('mode_paiement', $facture->mode_paiement) == 'bon de prise en charge' ? 'selected' : '' }}>Bon de prise en charge</option>
                                    </select>
                                    @error('mode_paiement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Chèque details (conditional) --}}
                                <div class="col-md-6" id="cheque-details" style="display: {{ old('mode_paiement', $facture->mode_paiement) == 'chèque' ? 'block' : 'none' }};">
                                    <label class="form-label fw-semibold">Détails chèque</label>
                                    <div class="row">
                                        <div class="col-4">
                                            <input type="text" name="num_cheque" class="form-control" 
                                                placeholder="N° chèque" value="{{ old('num_cheque') }}"
                                                {{ old('mode_paiement', $facture->mode_paiement) == 'chèque' ? '' : 'disabled' }}>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" name="emetteur_cheque" class="form-control" 
                                                placeholder="Émetteur" value="{{ old('emetteur_cheque') }}"
                                                {{ old('mode_paiement', $facture->mode_paiement) == 'chèque' ? '' : 'disabled' }}>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" name="banque_cheque" class="form-control" 
                                                placeholder="Banque" value="{{ old('banque_cheque') }}"
                                                {{ old('mode_paiement', $facture->mode_paiement) == 'chèque' ? '' : 'disabled' }}>
                                        </div>
                                    </div>
                                </div>

                                {{-- BPC details (conditional) --}}
                                <div class="col-md-6" id="bpc-details" style="display: {{ old('mode_paiement', $facture->mode_paiement) == 'bon de prise en charge' ? 'block' : 'none' }};">
                                    <label class="form-label fw-semibold">Émetteur BPC</label>
                                    <input type="text" name="emetteur_bpc" class="form-control" 
                                        placeholder="Nom émetteur BPC" value="{{ old('emetteur_bpc') }}"
                                        {{ old('mode_paiement', $facture->mode_paiement) == 'bon de prise en charge' ? '' : 'disabled' }}>
                                </div>
                            </div>

                            {{-- Row 2: Montants --}}
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Montant total <span class="text-danger">*</span></label>
                                    <input type="number" name="montant" class="form-control @error('montant') is-invalid @enderror" 
                                        value="{{ old('montant', $facture->montant) }}" required min="0" step="0.01">
                                    @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Montant perçu <span class="text-danger">*</span></label>
                                    <input type="number" name="percu" class="form-control @error('percu') is-invalid @enderror" 
                                        value="{{ old('percu', 0) }}" required min="0" step="0.01">
                                    @error('percu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Reste à payer <span class="text-danger">*</span></label>
                                    <input type="number" name="reste" class="form-control @error('reste') is-invalid @enderror" 
                                        value="{{ old('reste', $facture->reste) }}" required min="0" step="0.01" readonly>
                                    @error('reste') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Devise (optional) --}}
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label">Devise</label>
                                    <select name="devise" class="form-select">
                                        <option value="XAF" {{ old('devise', $facture->devise ?? 'XAF') == 'XAF' ? 'selected' : '' }}>XAF</option>
                                        <option value="EUR" {{ old('devise', $facture->devise) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="DOLLAR" {{ old('devise', $facture->devise) == 'DOLLAR' ? 'selected' : '' }}>USD</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Taux conversion</label>
                                    <input type="number" name="taux_conversion" class="form-control" 
                                        value="{{ old('taux_conversion', $facture->taux_conversion) }}" step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Montant devise</label>
                                    <input type="number" name="montant_devise" class="form-control" 
                                        value="{{ old('montant_devise', $facture->montant_devise) }}" step="0.01">
                                </div>
                            </div>

                            {{-- Summary Card --}}
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Récapitulatif</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4 border-end">
                                            <div class="text-muted small">Assurance ({{ $facture->patient->prise_en_charge ?? 0 }}%)</div>
                                            <div class="h6 fw-bold text-primary">
                                                {{ number_format($facture->assurancec ?? 0, 0, ',', ' ') }} FCFA
                                            </div>
                                        </div>
                                        <div class="col-md-4 border-end">
                                            <div class="text-muted small">Patient</div>
                                            <div class="h6 fw-bold text-success">
                                                {{ number_format($facture->assurec ?? 0, 0, ',', ' ') }} FCFA
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-muted small">Total perçu</div>
                                            <div class="h6 fw-bold {{ ($facture->avance ?? 0) >= ($facture->assurec ?? 0) ? 'text-success' : 'text-warning' }}">
                                                {{ number_format($facture->avance ?? 0, 0, ',', ' ') }} FCFA
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="card-footer bg-light text-end">
                            <a href="{{ route('factures.consultation') }}" class="btn btn-secondary me-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modePaiement = document.querySelector('[name=\"mode_paiement\"]');
    const chequeDetails = document.getElementById('cheque-details');
    const bpcDetails = document.getElementById('bpc-details');

    function toggleDetails() {
        const value = modePaiement.value;
        chequeDetails.style.display = value === 'chèque' ? 'block' : 'none';
        chequeDetails.querySelectorAll('input').forEach(input => input.disabled = value !== 'chèque');
        
        bpcDetails.style.display = value === 'bon de prise en charge' ? 'block' : 'none';
        bpcDetails.querySelector('input').disabled = value !== 'bon de prise en charge';
    }

    modePaiement.addEventListener('change', toggleDetails);
    toggleDetails(); // Initial state
});
</script>

@endsection

