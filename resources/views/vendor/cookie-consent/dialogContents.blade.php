<div class="js-cookie-consent cookie-consent fixed inset-x-0 bottom-0">
    <div class="px-6">
        <div class="cookie-consent__content darker mx-auto">
            <div class="center:vertical stack">
                <div class="stack">
                    <h2 class="h4">{{ __('This site uses cookies to help provide a better experience.') }}</h2>
                    <x-interpretation
                        name="{{ __('This site uses cookies to help provide a better experience.', [], 'en') }}"
                        namespace="cookie_message" />
                    <p class="cookie-consent__message">
                        {{ __('Cookies are pieces of information saved about you. This helps us remember your access settings, like your language or colour contrast mode.') }}
                    </p>
                </div>
                <div>
                    <button class="js-cookie-consent-agree cookie-consent__agree">
                        {{ __('Okay') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
