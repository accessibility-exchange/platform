<footer role="contentinfo">
    <div class="center center:wide">
        <div class="switcher">
            <div class="stack">
                <!-- Brand -->
                <a class="brand" rel="home" href="{{ localized_route('welcome') }}">
                    @if(locale() == 'en')
                    <x-tae-logo-mono-en class="logo" />
                    @elseif(locale() == 'fr')
                    <x-tae-logo-mono-fr class="logo" />
                    @endif
                    <span class="visually-hidden">{{ __('app.name') }}</span>
                </a>
                <nav aria-label="{{ __('secondary') }}">
                    <ul class="stack" role="list">
                        <li><a href="#TODO">{{ __('About') }}</a></li>
                        <li><a href="#TODO">{{ __('Glossary') }}</a></li>
                        <li><a href="#TODO">{{ __('Terms of Service') }}</a></li>
                        <li><a href="#TODO">{{ __('Privacy Policy') }}</a></li>
                    </ul>
                </nav>
            </div>
            <div class="switcher grow:2">
                <section class="stack">
                    <h2>{{ __('Contact') }}</h2>
                    <address class="stack">
                    <h3>{{ __('Email') }}</h3>
                    <p><a href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">{{ settings()->get('email', 'support@accessibilityexchange.ca') }}</a></p>
                    <h3>{{ __('Call, Text, or VRS') }}</h3>
                    <p>{{ settings()->get('phone', '1-800-123-4567') }}</p>
                    <h3>{{ __('Mailing Address') }}</h3>
                    <x-markdown>
                        {{ settings()->get('address', "PO Box 1000, Station A  \nToronto, ON M5W 1E6") }}
                    </x-markdown>
                    </address>
                </section>
                <nav class="stack" aria-labelledby="social">
                    <h2 id="social">{{ __('Social Media') }}</h2>
                    <ul class="stack" role="list">
                        <li><a href="#TODO">LinkedIn</a></li>
                        <li><a href="#TODO">Twitter</a></li>
                        <li><a href="#TODO">YouTube</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>
@livewireScripts()
