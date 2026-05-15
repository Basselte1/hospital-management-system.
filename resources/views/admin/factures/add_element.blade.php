@extends('layouts.admin')
@section('title', 'CMCU | Bilan facture')
@section('content')

<body>
<div class="wrapper">
    @include('partials.side_bar')
    @include('partials.header')

    @can('view', \App\Models\User::class)

        <div class="container_fluid">
            <h1 class="text-center">FACTURES PRODUITS</h1>
            <hr>
        </div>

        <div class="container pt-3">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <i class="table_info">Les montants sont exprimés en <b>FCFA</b></i>

                    <table id="myTable"
                           class="table table-hover table-bordered dt-responsive display nowrap"
                           cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NUMÉRO</th>
                                <th>MONTANT</th>
                                <th>DATE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($factures as $facture)
                                <tr>
                                    <td>{{ $facture->id }}</td>
                                    <td>{{ $facture->numero }}</td>
                                    <td>{{ number_format($facture->prix_total, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ \Carbon\Carbon::parse($facture->created_at)->format('d/m/Y H:i') }}</td>
                                    <td style="display: inline-flex; gap: 6px; align-items: center;">

                                        {{-- Bouton Voir --}}
                                        <a href="{{ route('factures.show', $facture->id) }}"
                                           class="btn btn-primary btn-sm"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- ✅ NOUVEAU : Bouton Ajouter un élément --}}
                                        <button type="button"
                                                class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAjoutElement"
                                                data-facture-id="{{ $facture->id }}"
                                                data-facture-numero="{{ $facture->numero }}"
                                                data-patient="{{ $facture->patient }}"
                                                title="Ajouter examen ou soin">
                                            <i class="fas fa-plus"></i>
                                        </button>

                                        @can('update', \App\Models\User::class)
                                            <form action="{{ route('factures.destroy', $facture->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Supprimer la facture"
                                                        onclick="return confirm('Supprimer cette facture ?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endcan
</div>
</div>

{{-- ================================================================
     MODAL UNIQUE : Ajouter un élément à la facture
     Une seule modal pour toutes les factures — le JS injecte les données
     ================================================================ --}}
<div class="modal fade" id="modalAjoutElement" tabindex="-1"
     aria-labelledby="modalAjoutElementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalAjoutElementLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Ajouter un élément — Facture N°&nbsp;<span id="modal-facture-numero"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('factures.ajouter-element') }}" method="POST"
                      id="formAjoutElement">
                    @csrf

                    {{-- Facture_id injecté automatiquement par le JS --}}
                    <input type="hidden" name="facture_id" id="input-facture-id">

                    {{-- ① Choix du type --}}
                    <div class="mb-4">
                        <label for="type-element" class="form-label fw-bold">
                            Type d'élément <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="type-element" name="type" required>
                            <option value="">-- Choisir un type --</option>
                            <option value="examen">🔬 Examen médical</option>
                            <option value="soin_infirmier">💉 Soin infirmier</option>
                        </select>
                    </div>

                    {{-- ② Sous-formulaire EXAMEN (caché par défaut) --}}
                    <div id="form-examen" class="sous-formulaire d-none border rounded p-3 bg-light mb-3">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-microscope me-1"></i> Détails de l'examen
                        </h6>

                        <div class="mb-3">
                            <label class="form-label">
                                Examen existant du patient
                                <span class="text-muted">(optionnel — pré-remplit la description)</span>
                            </label>
                            <select class="form-select" name="examen_id" id="select-examen">
                                <option value="">-- Sélectionner --</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control"
                                       name="examen_description" id="examen-description"
                                       placeholder="Ex: Numération formule sanguine">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Montant unitaire (FCFA)</label>
                                <input type="number" class="form-control"
                                       name="examen_montant_unitaire" id="examen-montant-unitaire"
                                       min="0" step="1" placeholder="5000">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Quantité</label>
                                <input type="number" class="form-control"
                                       name="examen_quantite" id="examen-quantite"
                                       min="1" value="1">
                            </div>
                        </div>

                        <div class="alert alert-info py-2 mb-0">
                            <i class="fas fa-calculator me-1"></i>
                            <strong>Total :</strong> <span id="examen-total">0</span> FCFA
                        </div>
                    </div>

                    {{-- ③ Sous-formulaire SOIN INFIRMIER (caché par défaut) --}}
                    <div id="form-soin" class="sous-formulaire d-none border rounded p-3 bg-light mb-3">
                        <h6 class="text-warning mb-3">
                            <i class="fas fa-syringe me-1"></i> Détails du soin infirmier
                        </h6>

                        <div class="mb-3">
                            <label class="form-label">
                                Soin existant du patient
                                <span class="text-muted">(optionnel)</span>
                            </label>
                            <select class="form-select" name="soin_id" id="select-soin">
                                <option value="">-- Sélectionner --</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Description du soin <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control"
                                       name="soin_description" id="soin-description"
                                       placeholder="Ex: Pansement, Injection IV...">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Montant unitaire (FCFA)</label>
                                <input type="number" class="form-control"
                                       name="soin_montant_unitaire" id="soin-montant-unitaire"
                                       min="0" step="1" placeholder="2000">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold">Quantité</label>
                                <input type="number" class="form-control"
                                       name="soin_quantite" id="soin-quantite"
                                       min="1" value="1">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Médecin prescripteur</label>
                                <input type="text" class="form-control"
                                       name="soin_medecin"
                                       placeholder="Nom du médecin">
                            </div>
                        </div>

                        <div class="alert alert-info py-2 mb-0">
                            <i class="fas fa-calculator me-1"></i>
                            <strong>Total :</strong> <span id="soin-total">0</span> FCFA
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <button type="submit" form="formAjoutElement" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Enregistrer
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('modalAjoutElement');

    // ── 1. Quand la modal s'ouvre : injecter les données de la facture cliquée
    modalEl.addEventListener('show.bs.modal', function (event) {

        const btn           = event.relatedTarget;           // bouton déclencheur
        const factureId     = btn.dataset.factureId;
        const factureNumero = btn.dataset.factureNumero;
        const patient       = btn.dataset.patient;           // nom du patient (string)

        // Afficher le numéro dans le titre de la modal
        document.getElementById('modal-facture-numero').textContent = factureNumero;

        // Injecter l'id dans le champ caché du formulaire
        document.getElementById('input-facture-id').value = factureId;

        // Réinitialiser le menu et cacher tous les sous-formulaires
        document.getElementById('type-element').value = '';
        cacherTousSousFormulaires();

        // Charger les examens et soins via AJAX si on a un patient
        if (patient) {
            chargerExamens(patient);
            chargerSoins(patient);
        }
    });

    // ── 2. Afficher le bon sous-formulaire selon le type choisi
    document.getElementById('type-element').addEventListener('change', function () {
        cacherTousSousFormulaires();

        if (this.value === 'examen') {
            document.getElementById('form-examen').classList.remove('d-none');
        } else if (this.value === 'soin_infirmier') {
            document.getElementById('form-soin').classList.remove('d-none');
        }
    });

    function cacherTousSousFormulaires() {
        document.querySelectorAll('.sous-formulaire').forEach(el => el.classList.add('d-none'));
    }

    // ── 3. Calcul automatique du total (montant × quantité)
    function bindCalculTotal(montantId, quantiteId, totalId) {
        [montantId, quantiteId].forEach(function (id) {
            document.getElementById(id).addEventListener('input', function () {
                const montant  = parseFloat(document.getElementById(montantId).value)  || 0;
                const quantite = parseFloat(document.getElementById(quantiteId).value) || 0;
                document.getElementById(totalId).textContent =
                    (montant * quantite).toLocaleString('fr-FR');
            });
        });
    }

    bindCalculTotal('examen-montant-unitaire', 'examen-quantite', 'examen-total');
    bindCalculTotal('soin-montant-unitaire',   'soin-quantite',   'soin-total');

    // ── 4. AJAX : charger les examens du patient
    function chargerExamens(patientNom) {
        const select = document.getElementById('select-examen');
        select.innerHTML = '<option value="">Chargement...</option>';

        fetch(`/api/patient-examens?patient=${encodeURIComponent(patientNom)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(function (data) {
            select.innerHTML = '<option value="">-- Sélectionner un examen --</option>';
            if (data.length === 0) {
                select.innerHTML += '<option disabled>Aucun examen enregistré</option>';
                return;
            }
            data.forEach(function (examen) {
                const opt = new Option(
                    examen.nom + (examen.description ? ' — ' + examen.description : ''),
                    examen.id
                );
                opt.dataset.description = examen.description ?? '';
                select.add(opt);
            });
        })
        .catch(() => {
            select.innerHTML = '<option value="">Erreur de chargement</option>';
        });
    }

    // Pré-remplir la description quand on choisit un examen existant
    document.getElementById('select-examen').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        document.getElementById('examen-description').value = opt?.dataset?.description ?? '';
    });

    // ── 5. AJAX : charger les soins infirmiers du patient
    function chargerSoins(patientNom) {
        const select = document.getElementById('select-soin');
        select.innerHTML = '<option value="">Chargement...</option>';

        fetch(`/api/patient-soins?patient=${encodeURIComponent(patientNom)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(function (data) {
            select.innerHTML = '<option value="">-- Sélectionner un soin --</option>';
            if (data.length === 0) {
                select.innerHTML += '<option disabled>Aucun soin enregistré</option>';
                return;
            }
            data.forEach(function (soin) {
                // SoinsInfirmier : adapte le champ d'affichage selon ta vraie colonne
                select.add(new Option(soin.type ?? ('Soin #' + soin.id), soin.id));
            });
        })
        .catch(() => {
            select.innerHTML = '<option value="">Erreur de chargement</option>';
        });
    }

});
</script>
@endpush