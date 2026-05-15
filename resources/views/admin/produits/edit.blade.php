@extends('layouts.admin')
@section('title', 'CMCU | Modifier un produit')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">
    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Modifier un Produit</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Mise à jour des informations du produit</p>
                </div>
                <a href="{{ route('produits.index') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-teal-700">
                <i class="fas fa-check-circle tw-text-[#14B8A6]"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-red-50 tw-border tw-border-red-200 tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-red-600">
                <i class="fas fa-exclamation-circle tw-text-red-500"></i> {{ session('error') }}
            </div>
            @endif
            @if(session('info'))
            <div class="tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-[#BFDBFE]/40 tw-border tw-border-[#BFDBFE] tw-px-4 tw-py-3 tw-mb-6 tw-text-sm tw-text-[#1D4ED8]">
                <i class="fas fa-info-circle tw-text-[#1D4ED8]"></i> {{ session('info') }}
            </div>
            @endif

            <div class="tw-max-w-2xl">

                @if($canEditDirectly)
                {{-- ── EDIT FORM ─────────────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-5 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <i class="fas fa-box-open tw-text-white"></i>
                        </div>
                        <div>
                            <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">{{ $produit->designation }}</h2>
                            <p class="tw-text-white/70 tw-text-xs tw-mt-0.5 tw-mb-0">Les champs <span class="tw-text-red-300">*</span> sont obligatoires</p>
                        </div>
                    </div>

                    @if($activeEditPermission)
                    <div class="tw-mx-6 tw-mt-5 tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-bg-teal-50 tw-border tw-border-teal-200 tw-px-4 tw-py-3 tw-text-sm tw-text-teal-700">
                        <i class="fas fa-check-circle tw-text-[#14B8A6]"></i>
                        Permission accordée le {{ $activeEditPermission->reviewed_at->format('d/m/Y à H:i') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('produits.update', $produit) }}" class="tw-p-6 tw-space-y-5">
                        @csrf @method('PATCH')

                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Désignation <span class="tw-text-red-500">*</span></label>
                            <input type="text" name="designation" value="{{ old('designation', $produit->designation) }}" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('designation') tw-border-red-400 @enderror">
                            @error('designation')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Catégorie <span class="tw-text-red-500">*</span></label>
                            <select name="categorie" id="categorie" required
                                class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                <option value="PHARMACEUTIQUE" {{ old('categorie', $produit->categorie) == 'PHARMACEUTIQUE' ? 'selected' : '' }}>PHARMACEUTIQUE</option>
                                <option value="MATERIEL" {{ old('categorie', $produit->categorie) == 'MATERIEL' ? 'selected' : '' }}>MATÉRIEL</option>
                                <option value="ANESTHESISTE" {{ old('categorie', $produit->categorie) == 'ANESTHESISTE' ? 'selected' : '' }}>ANESTHÉSISTE</option>
                            </select>
                        </div>

                        <hr class="tw-border-slate-100">

                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité en stock <span class="tw-text-red-500">*</span></label>
                                <input type="number" name="qte_stock" value="{{ old('qte_stock', $produit->qte_stock) }}" required min="0"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('qte_stock') tw-border-red-400 @enderror">
                                @error('qte_stock')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Quantité d'alerte <span class="tw-text-red-500">*</span></label>
                                <input type="number" name="qte_alerte" value="{{ old('qte_alerte', $produit->qte_alerte) }}" required min="0"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('qte_alerte') tw-border-red-400 @enderror">
                                @error('qte_alerte')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prix unitaire (FCFA) <span class="tw-text-red-500">*</span></label>
                            <div class="tw-relative">
                                <input type="number" name="prix_unitaire" value="{{ old('prix_unitaire', $produit->prix_unitaire) }}" required min="0"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-pr-16 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('prix_unitaire') tw-border-red-400 @enderror">
                                <span class="tw-absolute tw-right-3 tw-top-1/2 -tw-translate-y-1/2 tw-text-xs tw-text-slate-400 tw-font-medium">FCFA</span>
                            </div>
                            @error('prix_unitaire')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="tw-flex tw-gap-3 tw-pt-2">
                            <button type="submit"
                                class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-border-0">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer
                            </button>
                            <a href="{{ route('produits.index') }}"
                               class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>

                @else
                {{-- ── PERMISSION REQUEST ─────────────────────────── --}}
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100 tw-overflow-hidden">
                    <div class="tw-px-6 tw-py-5 tw-bg-amber-500">
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white/20 tw-flex tw-items-center tw-justify-center">
                                <i class="fas fa-lock tw-text-white"></i>
                            </div>
                            <div>
                                <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">Accès restreint</h2>
                                <p class="tw-text-white/80 tw-text-xs tw-mt-0.5 tw-mb-0">Une permission est requise pour modifier ce produit</p>
                            </div>
                        </div>
                    </div>

                    {{-- Product info read-only --}}
                    <div class="tw-p-6">
                        <h3 class="tw-text-xs tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold tw-mb-3">Informations actuelles</h3>
                        <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-mb-6">
                            @foreach([
                                ['Désignation', $produit->designation],
                                ['Catégorie', $produit->categorie],
                                ['Quantité en stock', $produit->qte_stock],
                                ['Seuil d\'alerte', $produit->qte_alerte],
                                ['Prix unitaire', number_format($produit->prix_unitaire, 0, ',', ' ') . ' FCFA'],
                            ] as [$label, $value])
                            <div class="tw-flex tw-items-start tw-gap-2">
                                <div class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-[#1D4ED8] tw-mt-1.5 tw-shrink-0"></div>
                                <div>
                                    <p class="tw-text-xs tw-text-slate-400 tw-mb-0">{{ $label }}</p>
                                    <p class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">{{ $value }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr class="tw-border-slate-100 tw-mb-5">
                        <h3 class="tw-text-xs tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold tw-mb-3">Demander la permission de modification</h3>

                        <form method="POST" action="{{ route('produits.edit-permissions.request', $produit) }}" class="tw-space-y-4">
                            @csrf
                            <div>
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">
                                    Raison de la modification <span class="tw-text-red-500">*</span>
                                </label>
                                <textarea name="reason" rows="4" required
                                    placeholder="Expliquez pourquoi vous devez modifier ce produit..."
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8] @error('reason') tw-border-red-400 @enderror">{{ old('reason') }}</textarea>
                                <p class="tw-text-xs tw-text-slate-400 tw-mt-1">Cette demande sera examinée par un Admin ou Gestionnaire</p>
                                @error('reason')<p class="tw-text-xs tw-text-red-500 tw-mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="tw-flex tw-gap-3">
                                <button type="submit"
                                    class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-amber-500 hover:tw-bg-amber-600 tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-border-0">
                                    <i class="fas fa-paper-plane tw-text-xs"></i> Demander la Permission
                                </button>
                                <a href="{{ route('produits.index') }}"
                                   class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Active Permissions (Admin only) --}}
                @can('approveEditRequests', \App\Models\Produit::class)
                @if($produit->activeEditPermissions->count() > 0)
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-[#BFDBFE] tw-mt-5">
                    <div class="tw-px-5 tw-py-3.5 tw-border-b tw-border-slate-100 tw-flex tw-items-center tw-gap-2">
                        <i class="fas fa-users tw-text-[#1D4ED8]"></i>
                        <h3 class="tw-text-sm tw-font-semibold tw-text-slate-700 tw-mb-0">Permissions actives pour ce produit</h3>
                    </div>
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-text-sm">
                            <thead>
                                <tr class="tw-bg-slate-50 tw-border-b tw-border-slate-100">
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Utilisateur</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Accordée le</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-text-slate-500 tw-font-semibold tw-uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-slate-100">
                                @foreach($produit->activeEditPermissions as $permission)
                                <tr>
                                    <td class="tw-px-4 tw-py-3 tw-font-medium tw-text-slate-700">{{ $permission->requestedBy->name }}</td>
                                    <td class="tw-px-4 tw-py-3 tw-text-slate-500">{{ $permission->reviewed_at->format('d/m/Y H:i') }}</td>
                                    <td class="tw-px-4 tw-py-3 tw-text-center">
                                        <form action="{{ route('produits.edit-permissions.revoke', $permission->id) }}" method="POST" class="tw-inline"
                                              onsubmit="return confirm('Révoquer cette permission ?')">
                                            @csrf
                                            <button type="submit" class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-lg tw-bg-amber-50 hover:tw-bg-amber-100 tw-text-amber-700 tw-text-xs tw-font-medium tw-px-3 tw-py-1.5 tw-border-0 tw-transition-colors">
                                                <i class="fas fa-ban tw-text-xs"></i> Révoquer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @endcan

            </div>
        </main>
    </div>
</div>
@endsection