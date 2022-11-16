<div>
    @if ($paginator->hasPages())
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
                            <a href="{{ $paginator->previousPageUrl() }}" aria-label="{{ __('pagination.previous') }}"
                                rel="prev">
                                @svg('heroicon-s-chevron-left')
                            </a>
                        @endif
                    </li>

                    <li>
                        @if ($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}" aria-label="{{ __('pagination.next') }}"
                                rel="prev">
                                @svg('heroicon-s-chevron-right')
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
                            <a href="{{ $paginator->previousPageUrl() }}" aria-label="{{ __('pagination.previous') }}"
                                rel="prev">
                                @svg('heroicon-s-chevron-left')
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
                                <li>
                                    <a href="{{ $url }}"
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
                            <a href="{{ $paginator->nextPageUrl() }}" aria-label="{{ __('pagination.next') }}"
                                rel="prev">
                                @svg('heroicon-s-chevron-right')
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    @endif
</div>
