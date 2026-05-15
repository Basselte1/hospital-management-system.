{{-- ══════════════════════════════════════════════════════════════
     admin_prescription_medicale_form.blade.php  (Tailwind restyled)
     Form action set dynamically via JS (see index_prescription_medicale)
     ══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="admin_prescription_medicale_form" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content tw-rounded-2xl tw-overflow-hidden tw-border-0 tw-shadow-xl">

            {{-- Header --}}
            <div class="modal-header tw-bg-[#14B8A6] tw-border-0 tw-px-5 tw-py-4">
                <div class="tw-flex tw-items-center tw-gap-2">
                    <div class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 tw-flex tw-items-center tw-justify-center tw-shrink-0">
                        <i class="fas fa-plus tw-text-white tw-text-xs"></i>
                    </div>
                    <h5 class="modal-title tw-text-sm tw-font-semibold tw-text-white tw-mb-0">Nouveau soin</h5>
                </div>
                <button type="button" class="tw-w-7 tw-h-7 tw-rounded-lg tw-bg-white/20 hover:tw-bg-white/30 tw-text-white tw-flex tw-items-center tw-justify-center tw-border-0 tw-transition-colors" data-bs-dismiss="modal">
                    <i class="fas fa-times tw-text-xs"></i>
                </button>
            </div>

            <form id="apm_form" method="POST" action="#">
                @csrf

                {{-- Body --}}
                <div class="modal-body tw-p-5 tw-space-y-4">

                    {{-- Date --}}
                    <div>
                        <label for="date" class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-1.5">Date</label>
                        <input type="date" id="date" name="date"
                               class="tw-w-full tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-4 tw-py-2.5 tw-text-sm tw-text-slate-700 focus:tw-outline-none focus:tw-bg-white focus:tw-ring-2 focus:tw-ring-teal-200 focus:tw-border-[#14B8A6] tw-transition-all">
                    </div>

                    {{-- Périodes --}}
                    <div>
                        <label class="tw-block tw-text-xs tw-font-semibold tw-text-slate-600 tw-mb-2">Période</label>
                        <div class="tw-grid tw-grid-cols-4 tw-gap-2">
                            @foreach([
                                ['name' => 'matin',    'label' => 'M',  'title' => 'Matin'],
                                ['name' => 'apre_midi','label' => 'AM', 'title' => 'Après-midi'],
                                ['name' => 'soir',     'label' => 'S',  'title' => 'Soir'],
                                ['name' => 'nuit',     'label' => 'N',  'title' => 'Nuit'],
                            ] as $p)
                            <label for="p_{{ $p['name'] }}"
                                   class="tw-flex tw-flex-col tw-items-center tw-gap-1 tw-cursor-pointer tw-rounded-xl tw-border tw-border-slate-200 tw-bg-slate-50 tw-px-2 tw-py-2.5 tw-text-xs tw-font-semibold tw-text-slate-600 hover:tw-border-[#14B8A6] hover:tw-bg-teal-50 tw-transition-colors
                                          has-[:checked]:tw-border-[#14B8A6] has-[:checked]:tw-bg-teal-50 has-[:checked]:tw-text-teal-700"
                                   title="{{ $p['title'] }}">
                                <input type="checkbox" id="p_{{ $p['name'] }}" name="{{ $p['name'] }}"
                                       class="tw-accent-[#14B8A6] tw-w-3.5 tw-h-3.5">
                                {{ $p['label'] }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer tw-bg-slate-50 tw-border-t tw-border-slate-100 tw-px-5 tw-py-3">
                    <input type="hidden" name="prescription_id" value="">
                    <button type="submit"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-[#14B8A6] hover:tw-bg-teal-600 tw-text-white tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-border-0 tw-transition-colors tw-shadow-sm">
                        <i class="fas fa-save tw-text-xs"></i> Enregistrer
                    </button>
                    <button type="button"
                            data-bs-dismiss="modal"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-600 tw-font-semibold tw-text-sm tw-px-5 tw-py-2.5 tw-transition-colors">
                        <i class="fas fa-times tw-text-xs"></i> Fermer
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>