<div class="contact-bar">
    <div class="wrapper">
        <div class="contact-methods" x-data="{'open': false}" @click.away="open = false">
            <button x-bind:aria-expanded="open.toString()" x-on:click="open = !open" @keyup.escape.window="open = false" class="button--borderless hidden--lg-n-above">
                {{ __('Contact us') }} <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" />
            </button>
            <div x-bind:class="open ? '' : 'hidden--lg-n-below'">
                <div>
                    <x-heroicon-o-phone aria-hidden="true" height="20" width="20" />&nbsp;<span class="semibold">{{ __('Call, text, VRS') }}:</span>&nbsp;{{ settings()->get('phone', '1-800-123-4567') }}
                </div>

                <div>
                    <x-heroicon-o-mail aria-hidden="true" height="20" width="20" />&nbsp;<span class="semibold">{{ __('Email') }}:</span>&nbsp;<a href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">
                        {{ settings()->get('email', 'support@accessibilityexchange.ca') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="exit">
            @auth
            <form method="POST" action="{{ localized_route('exit') }}">
                @csrf
                <button type="submit">
                    {{ __('Quick exit') }}
                </button>
            </form>
            @else
            <a class="cta" rel="nofollow noopener noreferrer" href="https://weather.com">
                {{ __('Quick exit') }}
            </a>
            @endauth
        </div>
    </div>
</div>
