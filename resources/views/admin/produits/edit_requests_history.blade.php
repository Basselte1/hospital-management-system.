@extends('layouts.admin')
@section('title', 'CMCU | Historique des Permissions')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-history"></i> Historique des Permissions de Modification
                </h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h4>{{ $editRequests->where('status', 'approved')->count() }}</h4>
                        <p class="mb-0"><i class="fas fa-check-circle"></i> Approuvées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-danger text-white">
                    <div class="card-body text-center">
                        <h4>{{ $editRequests->where('status', 'rejected')->count() }}</h4>
                        <p class="mb-0"><i class="fas fa-times-circle"></i> Rejetées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-secondary text-white">
                    <div class="card-body text-center">
                        <h4>{{ $editRequests->where('can_edit', false)->where('status', 'approved')->count() }}</h4>
                        <p class="mb-0"><i class="fas fa-ban"></i> Révoquées</p>
                    </div>
                </div>
            </div>
        </div>

        @if($editRequests->count() == 0)
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Aucune permission traitée
        </div>
        @else

        <!-- Permissions List -->
        @foreach($editRequests as $request)
        <div class="card shadow-sm mb-3">
            <div class="card-header {{ $request->getStatusColor() === 'success' ? 'bg-success' : ($request->getStatusColor() === 'danger' ? 'bg-danger' : 'bg-secondary') }} text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="{{ $request->getStatusIcon() }}"></i>
                            {{ $request->produit->designation }}
                        </h5>
                        <small>
                            Demandé par: {{ $request->requestedBy->name }} 
                            le {{ $request->created_at->format('d/m/Y à H:i') }}
                        </small>
                    </div>
                    <span class="badge bg-light text-dark">
                        {{ $request->getStatusLabel() }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Produit:</strong> {{ $request->produit->designation }}</p>
                        <p><strong>Catégorie:</strong> {{ $request->produit->categorie }}</p>
                        <p><strong>Demandeur:</strong> {{ $request->requestedBy->name }} ({{ $request->requestedBy->role->name ?? 'N/A' }})</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Traité par:</strong> {{ $request->reviewedBy->name ?? 'N/A' }}</p>
                        <p><strong>Date de traitement:</strong> 
                            {{ $request->reviewed_at ? $request->reviewed_at->format('d/m/Y à H:i') : 'N/A' }}
                        </p>
                        @if($request->isRevoked())
                        <p><strong>Révoqué par:</strong> {{ $request->revokedBy->name ?? 'N/A' }}</p>
                        <p><strong>Date de révocation:</strong> 
                            {{ $request->revoked_at ? $request->revoked_at->format('d/m/Y à H:i') : 'N/A' }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Reason -->
                <div class="alert alert-light mb-3">
                    <strong><i class="fas fa-comment"></i> Raison de la demande:</strong><br>
                    {{ $request->reason }}
                </div>

                @if($request->review_comment)
                <div class="alert alert-{{ $request->status === 'approved' ? 'success' : 'danger' }}">
                    <strong>Commentaire:</strong> {{ $request->review_comment }}
                </div>
                @endif

                <!-- Actions -->
                <div class="d-flex gap-2">
                    <a href="{{ route('produits.edit', $request->produit->id) }}" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Voir le produit
                    </a>

                    @if($request->isActive())
                    <form action="{{ route('produits.edit-permissions.revoke', $request->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm" 
                                onclick="return confirm('Révoquer cette permission ?')">
                            <i class="fas fa-ban"></i> Révoquer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-3">
            {{ $editRequests->links() }}
        </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="text-center mt-4">
            <a href="{{ route('produits.edit-permissions.pending') }}" class="btn btn-warning">
                <i class="fas fa-clock"></i> Demandes en attente
            </a>
            <a href="{{ route('produits.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        </main>
    </div>
</div>

@endsection