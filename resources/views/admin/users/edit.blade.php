@extends('layouts.admin')
@section('title', 'CMCU | Modifier un utilisateur')
@section('breadcrumb', 'Utilisateurs / Modifier')
@section('page_title', 'Modifier utilisateur')

@section('content')
<div class="wrapper tw-flex tw-h-screen tw-overflow-hidden tw-bg-slate-50">

    @include('partials.side_bar')

    <div id="content" class="tw-flex tw-flex-col tw-flex-1 tw-min-w-0 tw-overflow-y-auto">
        @include('partials.header')

        <main class="tw-flex-1 tw-p-4 sm:tw-p-6 lg:tw-p-8">

            {{-- Page heading --}}
            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-slate-800">Modifier un Utilisateur</h1>
                    <p class="tw-text-sm tw-text-slate-500 tw-mt-1">Mettre à jour les informations du compte</p>
                </div>
                <a href="{{ route('users.index') }}"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-text-sm tw-font-medium tw-px-4 tw-py-2 tw-transition-colors tw-no-underline">
                    <i class="fas fa-arrow-left tw-text-xs"></i> Retour
                </a>
            </div>

            <div class="tw-max-w-3xl">
                <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-slate-100">
                    {{-- Card header with user info --}}
                    <div class="tw-px-6 tw-py-5 tw-bg-[#1D4ED8] tw-flex tw-items-center tw-gap-4">
                        <div class="tw-w-12 tw-h-12 tw-rounded-full tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                            <span class="tw-text-white tw-text-xl tw-font-bold tw-uppercase">{{ mb_substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h2 class="tw-text-white tw-font-semibold tw-text-base tw-mb-0">{{ $user->name }} {{ $user->prenom }}</h2>
                            <p class="tw-text-white/70 tw-text-xs tw-mt-1 tw-mb-0">Les champs marqués <span class="tw-text-red-300">*</span> sont obligatoires</p>
                        </div>
                    </div>

                    <form action="{{ route('users.update', $user->id) }}" method="POST" class="tw-p-6 tw-space-y-6">
                        @method('PATCH')
                        @csrf

                        {{-- Identité --}}
                        <div>
                            <h3 class="tw-text-xs tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold tw-mb-3">Identité</h3>
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nom <span class="tw-text-red-500">*</span></label>
                                    <input name="name" type="text" value="{{ $user->name }}" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Prénom <span class="tw-text-red-500">*</span></label>
                                    <input name="prenom" type="text" value="{{ $user->prenom }}"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Sexe <span class="tw-text-red-500">*</span></label>
                                    <div class="tw-flex tw-gap-4 tw-mt-2">
                                        <label class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-600 tw-cursor-pointer">
                                            <input type="radio" name="sexe" value="Homme" class="tw-accent-[#1D4ED8]" @if($user->sexe == 'Homme') checked @endif required> Homme
                                        </label>
                                        <label class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-slate-600 tw-cursor-pointer">
                                            <input type="radio" name="sexe" value="Femme" class="tw-accent-[#1D4ED8]" @if($user->sexe == 'Femme') checked @endif> Femme
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Téléphone <span class="tw-text-red-500">*</span></label>
                                    <input name="telephone" id="telephone" type="tel" value="{{ $user->telephone }}" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Lieu de Naissance <span class="tw-text-red-500">*</span></label>
                                    <input name="lieu_naissance" value="{{ $user->lieu_naissance }}" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Date de Naissance <span class="tw-text-red-500">*</span></label>
                                    <input name="date_naissance" type="date" value="{{ $user->date_naissance }}" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                            </div>
                        </div>

                        <hr class="tw-border-slate-100">

                        {{-- Compte & Accès --}}
                        <div>
                            <h3 class="tw-text-xs tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold tw-mb-3">Compte & Accès</h3>
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Rôle <span class="tw-text-red-500">*</span></label>
                                    <select name="roles" id="roles"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Login <span class="tw-text-red-500">*</span></label>
                                    <input name="login" type="text" value="{{ $user->login }}" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                            </div>
                        </div>

                        {{-- Informations Médicales (Médecin only) --}}
                        <div id="otherFieldDiv" style="display: {{ $user->role_id == 2 ? 'block' : 'none' }};">
                            <hr class="tw-border-slate-100 tw-mb-4">
                            <h3 class="tw-text-xs tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold tw-mb-3">Informations Médicales</h3>
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Spécialité <span class="tw-text-red-500">*</span></label>
                                    <select name="specialite" id="specialite"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                        <option value="">— Sélectionner —</option>
                                        @foreach(\App\Models\User::getSpecialites() as $key => $label)
                                        <option value="{{ $key }}" {{ $user->specialite == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">ONMC <span class="tw-text-red-500">*</span></label>
                                    <input name="onmc" id="onmc" type="text" value="{{ $user->onmc }}" placeholder="Numéro ONMC"
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                            </div>
                            <div id="autreSpecialiteDiv" style="display: {{ $user->specialite == 'Autre' ? 'block' : 'none' }};" class="tw-mt-4">
                                <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Précisez la spécialité</label>
                                <input type="text" name="specialite_autre" id="specialite_autre"
                                    value="{{ $user->specialite == 'Autre' ? $user->specialite : '' }}" placeholder="Ex: Neurochirurgien"
                                    class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                            </div>
                        </div>

                        <hr class="tw-border-slate-100">

                        {{-- Nouveau mot de passe --}}
                        <div>
                            <h3 class="tw-text-xs tw-uppercase tw-tracking-widest tw-text-slate-400 tw-font-semibold tw-mb-3">Nouveau Mot de Passe</h3>
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Nouveau Mot de Passe <span class="tw-text-red-500">*</span></label>
                                    <input name="password" id="password" type="password" placeholder="Nouveau mot de passe" required
                                        class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-slate-600 tw-mb-1.5">Confirmer Mot de Passe <span class="tw-text-red-500">*</span></label>
                                    <div class="tw-flex tw-gap-2">
                                        <input id="confirm_password" name="password_confirmation" type="password" placeholder="Confirmer" required
                                            class="tw-flex-1 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-3 tw-py-2 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-[#BFDBFE] focus:tw-border-[#1D4ED8]">
                                        <button type="button" onclick="show_password()"
                                            class="tw-flex tw-items-center tw-justify-center tw-w-10 tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-500 tw-border-0 tw-transition-colors">
                                            <i id="show_pass" class="fas fa-eye tw-text-sm"></i>
                                        </button>
                                    </div>
                                    <span id="message" class="tw-block tw-mt-1 tw-text-xs"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="tw-flex tw-gap-3 tw-pt-2">
                            <button type="submit"
                                class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-xl tw-bg-[#1D4ED8] hover:tw-bg-[#1a46c5] tw-text-white tw-font-medium tw-py-2.5 tw-transition-colors tw-border-0">
                                <i class="fas fa-save tw-text-xs"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('users.index') }}"
                                class="tw-flex-1 tw-inline-flex tw-items-center tw-justify-center tw-rounded-xl tw-bg-slate-100 hover:tw-bg-slate-200 tw-text-slate-600 tw-font-medium tw-py-2.5 tw-transition-colors tw-no-underline">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="{{ asset('vendor/js/jquery-3.2.1.slim.min.js') }}"></script>
<script>
    $('#password, #confirm_password').on('keyup', function () {
        if ($('#password').val() && $('#password').val() == $('#confirm_password').val()) {
            $('#message').html('<span class="tw-text-green-600"><i class="fas fa-check"></i> Mots de passe identiques</span>');
        } else {
            $('#message').html('<span class="tw-text-red-500"><i class="fas fa-times"></i> Mots de passe différents</span>');
        }
    });

    function show_password() {
        var x = document.getElementById('password'), y = document.getElementById('confirm_password');
        var isPass = x.type === 'password';
        x.type = y.type = isPass ? 'text' : 'password';
        $('#show_pass').toggleClass('fa-eye fa-eye-slash');
    }

    $('#roles').change(function () {
        if ($(this).val() == '2') {
            $('#otherFieldDiv').show();
            $('#specialite, #onmc').attr('required', 'required');
        } else {
            $('#otherFieldDiv').hide();
            $('#autreSpecialiteDiv').hide();
            $('#specialite, #onmc').removeAttr('required');
        }
    });

    $('#specialite').change(function () {
        if ($(this).val() == 'Autre') {
            $('#autreSpecialiteDiv').show();
            $('#specialite_autre').attr('required', 'required');
        } else {
            $('#autreSpecialiteDiv').hide();
            $('#specialite_autre').removeAttr('required');
        }
    });

    $(document).ready(function () {
        $('#roles').trigger('change');
        $('#specialite').trigger('change');
    });
</script>
@stop