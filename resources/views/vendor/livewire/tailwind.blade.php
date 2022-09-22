<div>
    @if ($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : ($this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1))

        <nav class="flex flex-col items-center justify-between" role="navigation" aria-label="Pagination Navigation">
            <div class="w-full text-center" role="alert" aria-live="polite">
                <p>
                    {{ __('Showing :current_start to :current_end of :total results', [
                        'current_start' => $paginator->firstItem(),
                        'current_end' => $paginator->lastItem(),
                        'total' => $paginator->total(),
                    ]) }}
                </p>
            </div>

            <div class="flex w-full justify-between sm:hidden">
                <ul class="pagination flex w-full flex-row items-center justify-between" role="list">
                    <li>
                        @if ($paginator->onFirstPage())
                            <span class="spacer"></span>
                        @else
                            <a href="{{ route(RalphJSmit\Livewire\Urls\Facades\Url::currentRoute(), ['page' => $paginator->currentPage() - 1]) }}"
                                aria-label="{{ __('pagination.previous') }}"
                                wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled" rel="prev">
                                <x-heroicon-s-chevron-left class="h-5 w-5" aria-hidden="true" />
                            </a>
                        @endif
                    </li>

                    <li>
                        @if ($paginator->hasMorePages())
                            <a href="{{ route(RalphJSmit\Livewire\Urls\Facades\Url::currentRoute(), ['page' => $paginator->currentPage() + 1]) }}"
                                aria-label="{{ __('pagination.next') }}"
                                wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled" rel="prev">
                                <x-heroicon-s-chevron-right class="h-5 w-5" aria-hidden="true" />
                            </a>
                        @else
                            <span class="spacer"></span>
                        @endif
                    </li>
                </ul>
            </div>

            <div class="hidden w-full space-y-6 sm:flex sm:flex-col sm:items-center sm:justify-between">
                <ul class="pagination flex flex-row items-center justify-center gap-2" role="list">
                    @if (!$paginator->onFirstPage())
                        {{-- Previous Page Link --}}
                        <li>
                            <a href="{{ route(RalphJSmit\Livewire\Urls\Facades\Url::currentRoute(), ['page' => $paginator->currentPage() - 1]) }}"
                                aria-label="{{ __('pagination.previous') }}"
                                wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled" rel="prev">
                                <x-heroicon-s-chevron-left class="h-5 w-5" aria-hidden="true" />
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li><span class="spacer">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <li
                                    wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                                    <a href="{{ route(RalphJSmit\Livewire\Urls\Facades\Url::currentRoute(), ['page' => $page]) }}"
                                        wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        @if ($page == $paginator->currentPage()) aria-current="page" @endif>
                                        <span class="visually-hidden">{{ __('page') }}</span> {{ $page }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        {{-- Next Page Link --}}
                        <li>
                            <a href="{{ route(RalphJSmit\Livewire\Urls\Facades\Url::currentRoute(), ['page' => $paginator->currentPage() + 1]) }}"
                                aria-label="{{ __('pagination.next') }}"
                                wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled" rel="prev">
                                <x-heroicon-s-chevron-right class="h-5 w-5" aria-hidden="true" />
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    @endif
</div>
