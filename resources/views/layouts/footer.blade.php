<footer class="darker" role="contentinfo">
    <div class="center center:wide">
        <div class="switcher">
            <div class="stack">
                <!-- Brand -->
                <a class="brand" href="{{ localized_route('welcome') }}" rel="home">
                    @if (locale() == 'en' || locale() == 'asl')
                        @svg('tae-logo-mono-en', ['class' => 'logo-footer'])
                    @elseif(locale() == 'fr' || locale() == 'lsq')
                        @svg('tae-logo-mono-fr', ['class' => 'logo-footer'])
                    @endif
                    <span class="visually-hidden">{{ __('app.name') }}</span>
                </a>
                <nav aria-label="{{ __('secondary') }}">
                    <ul class="stack" role="list">
                        <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a>
                        </li>
                        {{-- TODO: Add glossary feature --}}
                        {{-- <li><a href="">{{ __('Glossary') }}</a></li> --}}
                        <li><a href="{{ localized_route('about.terms-of-service') }}">{{ __('Terms of Service') }}</a>
                        </li>
                        <li><a href="{{ localized_route('about.privacy-policy') }}">{{ __('Privacy Policy') }}</a></li>
                    </ul>
                </nav>
            </div>
            <div class="switcher grow:2">
                <div class="stack" id="contact" tabindex="-1">
                    <h2>{{ __('Contact') }}</h2>
                    <address class="stack">
                        <h3>{{ __('Email') }}</h3>
                        <p><a
                                href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">{{ settings()->get('email', 'support@accessibilityexchange.ca') }}</a>
                        </p>
                        <h3>{!! __('Call or :vrs', [
                            'vrs' =>
                                '<a href="https://srvcanadavrs.ca/en/resources/resource-centre/vrs-basics/register/" rel="external">' .
                                __('VRS') .
                                '</a>',
                        ]) !!}</h3>
                        <p>{{ phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA') }}</p>
                        <h3>{{ __('Mailing Address') }}</h3>
                        {!! nl2br(
                            settings()->get(
                                'address',
                                "The Accessibility Exchange ℅ IRIS\n1 University Avenue, 3rd Floor\nToronto, ON M5J 2P1",
                            ),
                        ) !!}
                    </address>
                </div>
                <nav class="stack" aria-labelledby="social">
                    <h2 id="social">{{ __('Social Media') }}</h2>
                    <ul class="stack" role="list">
                        <li><a href="{{ settings()->get('linkedin', 'https://www.linkedin.com/company/the-accessibility-exchange/') }}"
                                rel="external">LinkedIn</a></li>
                        <li><a href="{{ settings()->get('facebook', 'https://www.facebook.com/AccessXchange') }}"
                                rel="external">Facebook</a></li>
                        <li><a href="{{ settings()->get('twitter', 'https://twitter.com/AccessXchange') }}"
                                rel="external">Twitter</a></li>
                        <li><a href="{{ settings()->get('youtube', 'https://www.youtube.com/channel/UC-mIk4Xk04wF4urFSKZQOAA') }}"
                                rel="external">YouTube</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>
@livewireScripts()
@stack('livewireScripts')
@stack('infusionScripts')
