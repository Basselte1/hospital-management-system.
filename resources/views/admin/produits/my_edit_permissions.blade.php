@extends('layouts.admin')
@section('title', 'CMCU | Mes Permissions de Modification')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="fw-bold text-primary">
                    <i class="fas fa-user-lock"></i> Mes Permissions de Modification
                </h1>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h4>{{ $editRequests->where('status', 'approved')->where('can_edit', true)->count() }}</h4>
                        <p class="mb-0"><i class="fas fa-check-circle"></i> Permissions Actives</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm bg-warning text-dark">
                    <div class="card-body text-center">
                        <h4>{{ $editRequests->where('status', 'pending')->count() }}</h4>
                        <p class="mb-0"><i class="fas fa-clock"></i> En Attente</p>
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
        </div>

        @if($editRequests->count() == 0)
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Vous n'avez aucune demande de permission
        </div>
        @else

        <!-- Permissions List -->
        @foreach($editRequests as $request)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-{{ $request->getStatusColor() }} text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="{{ $request->getStatusIcon() }}"></i>
                            {{ $request->produit->designation }}
                        </h5>
                        <small>Demandé le {{ $request->created_at->format('d/m/Y à H:i') }}</small>
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
                        <p><strong>Stock actuel:</strong> {{ $request->produit->qte_stock }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Statut:</strong> 
                            <span class="badge bg-{{ $request->getStatusColor() }}">
                                {{ $request->getStatusLabel() }}
                            </span>
                        </p>
                        @if($request->reviewed_at)
                        <p><strong>Traité par:</strong> {{ $request->reviewedBy->name ?? 'N/A' }}</p>
                        <p><strong>Traité le:</strong> {{ $request->reviewed_at->format('d/m/Y à H:i') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Reason -->
                <div class="alert alert-light mb-3">
                    <strong><i class="fas fa-comment"></i> Votre raison:</strong><br>
                    {{ $request->reason }}
                </div>

                @if($request->review_comment)
                <div class="alert alert-{{ $request->status === 'approved' ? 'success' : 'danger' }}">
                    <strong>Réponse:</strong> {{ $request->review_comment }}
                </div>
                @endif

                <!-- Actions -->
                <div class="d-flex gap-2">
                    @if($request->isActive())
                    <a href="{{ route('produits.edit', $request->produit->id) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier le Produit
                    </a>
                    @elseif($request->isPending())
                    <span class="text-muted">
                        <i class="fas fa-clock"></i> En attente d'approbation...
                    </span>
                    @endif

                    <a href="{{ route('produits.edit', $request->produit->id) }}" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye"></i> Voir le Produit
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-3">
            {{ $editRequests->links() }}
        </div>
        @endif

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux Produits
            </a>
        </div>
        </main>
    </div>
</div>

@endsection