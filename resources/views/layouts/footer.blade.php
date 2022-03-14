<footer class="center center:wide" role="contentinfo">
    <div class="switcher">
        <div class="stack stack:small">
            @include('components.brand')
            <nav class="stack" aria-label="secondary">
                <ul role="list">
                <li><a href="#TODO">{{ __('About') }}</a></li>
                <li><a href="#TODO">{{ __('Glossary') }}</a></li>
                <li><a href="#TODO">{{ __('Terms of Service') }}</a></li>
                <li><a href="#TODO">{{ __('Privacy Policy') }}</a></li>
                </ul>
            </nav>
        </div>
        <div class="switcher">
            <section class="stack stack:small">
                <h2>{{ __('Contact') }}</h2>
                <address class="stack stack:small">
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
            <nav class="stack stack:small" aria-labelledby="social">
                <h2 id="social">{{ __('Social Media') }}</h2>
                <ul role="list">
                <li><a href="#TODO">LinkedIn</a></li>
                <li><a href="#TODO">Twitter</a></li>
                <li><a href="#TODO">YouTube</a></li>
                </ul>
            </nav>
        </div>
    </div>
</footer>
@livewireScripts()
