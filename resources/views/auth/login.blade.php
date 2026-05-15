@extends('layouts.login_layout')

@section('content')

<div class="tw-min-h-screen tw-flex tw-items-stretch">

    {{-- ── LEFT PANEL — Branding ─────────────────────────────── --}}
    <div class="tw-hidden lg:tw-flex tw-flex-col tw-justify-between tw-w-[42%] tw-shrink-0 tw-p-10 tw-relative tw-overflow-hidden">

        {{-- Decorative circles --}}
        <div class="tw-absolute tw-top-[-80px] tw-left-[-80px] tw-w-72 tw-h-72 tw-rounded-full tw-bg-[#1D4ED8]/30 tw-pointer-events-none"></div>
        <div class="tw-absolute tw-bottom-[-60px] tw-right-[-60px] tw-w-56 tw-h-56 tw-rounded-full tw-bg-[#14B8A6]/20 tw-pointer-events-none"></div>

        {{-- Logo + name --}}
        <div class="tw-relative tw-flex tw-items-center tw-gap-3">
            <div class="tw-w-10 tw-h-10 tw-rounded-xl tw-bg-white tw-flex tw-items-center tw-justify-center tw-shadow-lg tw-shrink-0">
                <i class="fas fa-hospital tw-text-[#1D4ED8] tw-text-lg"></i>
            </div>
            <div>
                <span class="tw-font-bold tw-text-white tw-text-lg tw-leading-none">CMCU</span>
                <p class="tw-text-[#BFDBFE] tw-text-[11px] tw-mb-0 tw-mt-0.5 tw-leading-none">Portail Médical</p>
            </div>
        </div>

        {{-- Centre: hospital info --}}
        <div class="tw-relative tw-flex-1 tw-flex tw-flex-col tw-justify-center tw-py-8">
            <img src="{{ asset('admin/images/logo.jpg') }}"
                 alt="CMCU Logo"
                 class="tw-w-24 tw-h-24 tw-rounded-2xl tw-object-cover tw-shadow-lg tw-mb-6 tw-ring-2 tw-ring-white/20">

            <h1 class="tw-text-white tw-text-2xl tw-font-bold tw-leading-snug tw-mb-3">
                Centre Médico-Chirurgical<br>d'Urologie
            </h1>
            <p class="tw-text-[#BFDBFE] tw-text-sm tw-leading-relaxed tw-mb-6">
                Chirurgie Mini-Invasive · Vallée Manga Bell
            </p>

            {{-- Contact pills --}}
            <div class="tw-flex tw-flex-col tw-gap-2">
                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-text-xs tw-text-[#BFDBFE]">
                    <i class="fas fa-map-marker-alt tw-text-[#14B8A6] tw-w-4 tw-text-center"></i>
                    Douala-Bali, Cameroun
                </span>
                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-text-xs tw-text-[#BFDBFE]">
                    <i class="fas fa-phone tw-text-[#14B8A6] tw-w-4 tw-text-center"></i>
                    (+237) 233 423 389 · 674 068 988
                </span>
                <span class="tw-inline-flex tw-items-center tw-gap-2 tw-text-xs tw-text-[#BFDBFE]">
                    <i class="fas fa-globe tw-text-[#14B8A6] tw-w-4 tw-text-center"></i>
                    www.cmcu-cm.com
                </span>
            </div>
        </div>

        {{-- Footer --}}
        <p class="tw-relative tw-text-xs tw-text-white/30">
            © {{ date('Y') }} CMCU — Tous droits réservés
        </p>
    </div>

    {{-- ── RIGHT PANEL — Login form ──────────────────────────── --}}
    <div class="tw-flex-1 tw-flex tw-items-center tw-justify-center tw-p-6 sm:tw-p-10 tw-bg-white/5 tw-backdrop-blur-sm">

        <div class="tw-w-full tw-max-w-sm">

            {{-- Mobile logo (hidden on lg) --}}
            <div class="lg:tw-hidden tw-flex tw-items-center tw-gap-3 tw-mb-8 tw-justify-center">
                <div class="tw-w-9 tw-h-9 tw-rounded-xl tw-bg-white tw-flex tw-items-center tw-justify-center tw-shadow">
                    <i class="fas fa-hospital tw-text-[#1D4ED8] tw-text-base"></i>
                </div>
                <span class="tw-font-bold tw-text-white tw-text-lg">CMCU</span>
            </div>

            {{-- Flash messages --}}
            @include('flash::message')

            {{-- Validation errors --}}
            @if ($errors->any())
            <div class="tw-bg-red-500/10 tw-border tw-border-red-500/30 tw-rounded-xl tw-px-4 tw-py-3 tw-mb-5">
                <p class="tw-text-red-300 tw-text-xs tw-font-medium tw-mb-1">
                    <i class="fas fa-exclamation-circle tw-mr-1"></i>
                    Erreur de connexion
                </p>
                @foreach ($errors->all() as $error)
                    <p class="tw-text-red-300 tw-text-xs tw-mb-0">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            {{-- Card --}}
            <div class="tw-bg-white tw-rounded-2xl tw-shadow-xl tw-p-8">

                <h2 class="tw-text-xl tw-font-bold tw-text-slate-800 tw-mb-1">Connexion</h2>
                <p class="tw-text-sm tw-text-slate-400 tw-mb-6">Entrez vos identifiants pour accéder au portail</p>

                <form action="{{ route('login') }}" method="POST" class="tw-space-y-5">
                    @csrf

                    {{-- Login --}}
                    <div>
                        <label for="login" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-1.5">
                            Nom d'utilisateur
                        </label>
                        <div class="tw-relative">
                            <span class="tw-absolute tw-left-3.5 tw-top-1/2 tw--translate-y-1/2 tw-text-slate-400 tw-pointer-events-none">
                                <i class="far fa-user tw-text-sm"></i>
                            </span>
                            <input
                                type="text"
                                id="login"
                                name="login"
                                value="{{ old('login') }}"
                                placeholder="Nom d'utilisateur"
                                required
                                autocomplete="username"
                                class="login-input tw-block tw-w-full tw-pl-10 tw-pr-4 tw-py-2.5 tw-text-sm tw-text-slate-800 tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50 tw-transition-all tw-duration-150"
                            >
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-uppercase tw-tracking-wide tw-mb-1.5">
                            Mot de passe
                        </label>
                        <div class="password-wrapper">
                            <span class="tw-absolute tw-left-3.5 tw-top-1/2 tw--translate-y-1/2 tw-text-slate-400 tw-pointer-events-none">
                                <i class="fas fa-lock tw-text-sm"></i>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••••••"
                                required
                                autocomplete="current-password"
                                class="login-input tw-block tw-w-full tw-pl-10 tw-pr-10 tw-py-2.5 tw-text-sm tw-text-slate-800 tw-border tw-border-slate-200 tw-rounded-xl tw-bg-slate-50 tw-transition-all tw-duration-150"
                            >
                            <i class="fas fa-eye toggle-pw" id="togglePassword"></i>
                        </div>
                    </div>

                    {{-- Remember + forgot --}}
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                            <input
                                type="checkbox"
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                                class="tw-w-4 tw-h-4 tw-rounded tw-border-slate-300 tw-accent-[#1D4ED8]"
                            >
                            <span class="tw-text-xs tw-text-slate-500">Se souvenir de moi</span>
                        </label>
                        <a href="#" class="tw-text-xs tw-text-[#1D4ED8] hover:tw-underline tw-no-underline tw-transition-colors">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="tw-w-full tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-5 tw-py-3 tw-bg-[#1D4ED8] tw-text-white tw-text-sm tw-font-semibold tw-rounded-xl hover:tw-bg-[#1a46c5] tw-transition-colors tw-duration-150 tw-shadow-sm tw-border-0 tw-cursor-pointer">
                        <i class="fas fa-sign-in-alt tw-text-xs"></i>
                        Se connecter
                    </button>

                </form>

                {{-- Help --}}
                <p class="tw-text-center tw-text-xs tw-text-slate-400 tw-mt-5 tw-mb-0">
                    Problème de connexion ?
                    <a href="#" class="tw-text-[#1D4ED8] hover:tw-underline tw-no-underline">Contacter le support</a>
                </p>

            </div>

        </div>
    </div>
</div>

<script>
(function() {
    var toggle = document.getElementById('togglePassword');
    var field  = document.getElementById('password');
    if (!toggle || !field) return;
    toggle.addEventListener('click', function() {
        var isPassword = field.type === 'password';
        field.type = isPassword ? 'text' : 'password';
        toggle.classList.toggle('fa-eye', !isPassword);
        toggle.classList.toggle('fa-eye-slash', isPassword);
    });
})();
</script>

@endsection