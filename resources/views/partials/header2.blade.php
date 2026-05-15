{{-- ── Topbar / Header Partial ─────────────────────────────────────── --}}
<header id="topbar"
    class="tw-flex tw-items-center tw-h-16 tw-bg-white tw-border-b tw-border-slate-200 tw-shadow-sm tw-px-4">

    {{-- ── LEFT: Sidebar Toggle ──────────────────────────────────── --}}
    <button id="sidebarCollapse" type="button"
        class="tw-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-lg tw-bg-[#1D4ED8] tw-text-white hover:tw-bg-[#1a46c5] tw-transition-colors tw-duration-150 tw-shrink-0 tw-border-0">
        <i class="fas fa-bars tw-text-sm"></i>
    </button>

    {{-- ── CENTER spacer ─────────────────────────────────────────── --}}
    <div class="tw-flex-1"></div>

    {{-- ── RIGHT: bare user icon → dropdown ────────────────────── --}}
    <div class="tw-relative" id="userDropdownWrapper">

        <button type="button" id="userDropdownBtn"
            class="tw-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-lg hover:tw-bg-slate-100 tw-border-0 tw-bg-transparent tw-text-slate-600 hover:tw-text-slate-800 tw-transition-colors tw-duration-150 tw-cursor-pointer"
            aria-expanded="false"
            title="{{ Auth::user()->name }}">
            <i class="far fa-user tw-text-lg"></i>
        </button>

        {{-- Dropdown panel --}}
        <div id="userDropdownMenu"
            class="tw-absolute tw-right-0 tw-top-full tw-mt-2 tw-w-64 tw-bg-white tw-rounded-xl tw-shadow-lg tw-border tw-border-slate-200 tw-overflow-hidden tw-z-50 tw-hidden">

            {{-- User info header --}}
            <div class="tw-p-4 tw-bg-gradient-to-br tw-from-[#1D4ED8] tw-to-[#1a46c5]">
                <div class="tw-flex tw-items-center tw-gap-3">
                    <div class="tw-w-11 tw-h-11 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-shrink-0"
                         style="background:rgba(255,255,255,.2)">
                        <span class="tw-text-white tw-text-lg tw-font-bold tw-uppercase tw-leading-none">
                            {{ mb_substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </span>
                    </div>
                    <div class="tw-min-w-0">
                        <p class="tw-text-white tw-font-semibold tw-text-sm tw-mb-0 tw-leading-tight tw-truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="tw-text-white/70 tw-text-xs tw-mb-0 tw-mt-0.5 tw-truncate">
                            {{ Auth::user()->login ?? Auth::user()->email }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Profile link --}}
            <div class="tw-py-1">
                <a href="{{ route('users.profile', Auth::user()->id) }}"
                    class="tw-flex tw-items-center tw-gap-3 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 hover:tw-bg-slate-50 tw-transition-colors tw-duration-150 tw-no-underline">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-[#BFDBFE] tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="far fa-user tw-text-[#1D4ED8] tw-text-xs"></i>
                    </div>
                    <span>Mon profil</span>
                </a>
            </div>

            {{-- Logout --}}
            <div class="tw-border-t tw-border-slate-100 tw-py-1">
                <button type="button"
                    onclick="document.getElementById('topbar-logout-form').submit()"
                    class="tw-w-full tw-flex tw-items-center tw-gap-3 tw-px-4 tw-py-2.5 tw-text-sm tw-text-red-600 hover:tw-bg-red-50 tw-transition-colors tw-duration-150 tw-border-0 tw-bg-transparent tw-cursor-pointer">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-red-50 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-sign-out-alt tw-text-red-500 tw-text-xs"></i>
                    </div>
                    <span>Déconnexion</span>
                </button>
                <form id="topbar-logout-form" action="{{ route('logout') }}" method="POST" class="tw-hidden">
                    @csrf
                </form>
            </div>

        </div>
    </div>

</header>

<script>
(function () {
    var btn     = document.getElementById('userDropdownBtn');
    var menu    = document.getElementById('userDropdownMenu');
    var wrapper = document.getElementById('userDropdownWrapper');
    if (!btn || !menu) return;

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = !menu.classList.contains('tw-hidden');
        menu.classList.toggle('tw-hidden', isOpen);
        btn.setAttribute('aria-expanded', String(!isOpen));
    });

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target)) {
            menu.classList.add('tw-hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>