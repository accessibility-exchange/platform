@unless(session()->missing('_token') || session()->has('language-confirmed'))
    <div x-data="modal()" x-init="showModal();">
        <template x-teleport="body">
            <div class="modal-wrapper language-modal darker" x-show="showingModal" @keydown.escape.window="hideModal">
                <div class="modal stack flex flex-col">
                    @svg('tae-logo-mono-no-text', ['class' => 'language-modal__logo'])
                    <h2>
                        <p class="text-center" lang="en">{{ __('Welcome to the Accessibility Exchange', [], 'en') }}</p>
                        <p class="text-center" lang="fr">{{ __('Welcome to the Accessibility Exchange', [], 'fr') }}
                        </p>
                    </h2>

                    <nav aria-label="{{ __('confirm language') }}">
                        <ul class="grid" role="list">
                            @foreach ($languages as $locale => $language)
                                <li class="flex flex-col">
                                    <a class="cta flex flex-col"
                                        href="{{ trans_current_route($locale, route($locale . '.welcome')) }}"
                                        hreflang="{{ get_written_language_for_signed_language($locale) }}"
                                        aria-current="{{ request()->routeIs($locale . '.*') ? 'true' : 'false' }}"
                                        rel="alternate">
                                        @if (is_signed_language($locale))
                                            @svg("tae-{$locale}", ['class' => 'signed']) <br>
                                        @endif
                                        <span>{{ $language }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>

                    <div>
                        <p class="text-center" lang="en">
                            {{ __('You can always change this by selecting the language menu.', [], 'en') }}
                        </p>
                        <p class="text-center" lang="fr">
                            {{ __('You can always change this by selecting the language menu.', [], 'fr') }}
                        </p>
                    </div>

                    {{--
                            Using two instances of the video player for desktop and mobile.
                            An alternative woudl have been to use a single player instance
                            and use JavaScript's `matchMedia` function. However, it takes
                            the size of the initial video set in the `url` property. The
                            vidoes for the two cases are different sizes/aspect ratios so only
                            one instance will appear properly if switching between the two.
                        --}}
                    <div class="stack center video--desktop w-full" x-data="vimeoPlayer({
                        url: 'https://vimeo.com/814610786',
                        byline: false,
                        dnt: true,
                        pip: true,
                        portrait: false,
                        responsive: true,
                        speed: true,
                        title: false
                    })"
                        @ended="player().setCurrentTime(0)">
                    </div>
                    <div class="stack video--mobile w-full" x-data="vimeoPlayer({
                        url: 'https://vimeo.com/814610773',
                        byline: false,
                        dnt: true,
                        pip: true,
                        portrait: false,
                        responsive: true,
                        speed: true,
                        title: false
                    })" @ended="player().setCurrentTime(0)">
                    </div>
                </div>
            </div>
        </template>
    </div>
@endunless
