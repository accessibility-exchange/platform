<div>
    @if ($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : ($this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1))

        <nav class="flex items-center justify-between" role="navigation" aria-label="Pagination Navigation">
            {{-- <div class="flex flex-1 justify-between sm:hidden"> --}}
            {{-- <span> --}}
            {{-- @if ($paginator->onFirstPage()) --}}
            {{-- <span class="inline-flex rounded-full px-0 py-0"> --}}
            {{-- <x-heroicon-s-chevron-left class="text-graphite-7" /> --}}
            {{-- <span class="sr-only">{{ __('pagination.previous') }}</span> --}}
            {{-- </span> --}}
            {{-- @else --}}
            {{-- <button class="borderless inline-flex rounded-full px-0 py-0" --}}
            {{-- wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" --}}
            {{-- dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"> --}}
            {{-- <x-heroicon-s-chevron-left class="text-graphite-7" /> --}}
            {{-- <span class="sr-only">{{ __('pagination.previous') }}</span> --}}
            {{-- </button> --}}
            {{-- @endif --}}
            {{-- </span> --}}

            {{-- <span> --}}
            {{-- @if ($paginator->hasMorePages()) --}}
            {{-- <button class="borderless inline-flex rounded-full px-0 py-0" --}}
            {{-- wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" --}}
            {{-- dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"> --}}
            {{-- <x-heroicon-s-chevron-right class="text-graphite-7" /> --}}
            {{-- <span class="sr-only">{{ __('pagination.next') }}</span> --}}
            {{-- </button> --}}
            {{-- @else --}}
            {{-- <span class="inline-flex rounded-full px-0 py-0"> --}}
            {{-- <x-heroicon-s-chevron-right class="text-graphite-7" /> --}}
            {{-- <span class="sr-only">{{ __('pagination.next') }}</span> --}}
            {{-- </span> --}}
            {{-- @endif --}}
            {{-- </span> --}}
            {{-- </div> --}}

            <div class="mt-12 hidden w-full space-y-6 sm:flex sm:flex-col sm:items-center sm:justify-between">
                <div class="w-full text-center">
                    <p class="">
                        {{ __('Showing :current_start to :current_end of :total results', [
                            'current_start' => $paginator->firstItem(),
                            'current_end' => $paginator->lastItem(),
                            'total' => $paginator->total(),
                        ]) }}
                    </p>

                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span aria-hidden="true">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button aria-label="{{ __('pagination.previous') }}"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="prev">
                                    <x-heroicon-s-chevron-left class="text-graphite-7" />
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span class="px-4">{{ $element }}</span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span
                                        wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span>{{ $page }}</span>
                                            </span>
                                        @else
                                            <button class="rounded-full underline"
                                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button aria-label="{{ __('pagination.next') }}"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span aria-hidden="true">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
