{{--
    Pagination view for Tailwind.
    Used by: $paginator->links('tailwind.blade.php')
--}}

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination">
        <ul class="tw-flex tw-items-center tw-gap-1 tw-text-sm">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="tw-opacity-50">
                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white">
                        «
                    </span>
                </li>
            @else
                <li>
                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50"
                        rel="prev"
                        aria-label="Previous"
                    >
                        «
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="tw-inline-flex tw-items-center tw-justify-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-bg-[#1D4ED8] tw-text-white tw-border tw-border-[#1D4ED8]">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a
                                    href="{{ $url }}"
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50 tw-text-slate-700"
                                >
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @else
                    {{-- Typically the "..." element --}}
                    @if ($element === '...')
                        <li class="tw-opacity-70">
                            <span class="tw-inline-flex tw-items-center tw-justify-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white">
                                …
                            </span>
                        </li>
                    @endif
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white hover:tw-bg-slate-50"
                        rel="next"
                        aria-label="Next"
                    >
                        »
                    </a>
                </li>
            @else
                <li class="tw-opacity-50">
                    <span class="tw-inline-flex tw-items-center tw-justify-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-border tw-border-slate-200 tw-bg-white">
                        »
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif

