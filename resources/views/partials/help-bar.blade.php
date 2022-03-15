<div class="help-bar">
    <div class="center center:wide cluster justify:between">
        <div x-data="{open: false}" @click.away="open = false">
            <button class="borderless" x-bind:aria-expanded="open.toString()" x-on:click="open = !open" @keyup.escape.window="open = false">
                {{ __('Get help') }} <x-heroicon-o-chevron-down class="indicator" aria-hidden="true" />
            </button>
            <div class="responsive-switcher">
                <div>
                    <x-heroicon-o-question-mark-circle aria-hidden="true" height="20" width="20" />&nbsp;<a href="#">{{ __('Help center') }}</a>
                </div>
                <div>
                    <x-heroicon-o-phone aria-hidden="true" height="20" width="20" />&nbsp;<span class="weight:semibold">{{ __('Call, text, VRS') }}:</span>&nbsp;{{ settings()->get('phone', '1-800-123-4567') }}
                </div>
                <div>
                    <x-heroicon-o-mail aria-hidden="true" height="20" width="20" />&nbsp;<span class="weight:semibold">{{ __('Email') }}:</span>&nbsp;<a href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">
                        {{ settings()->get('email', 'support@accessibilityexchange.ca') }}
                    </a>
                </div>
            </div>
        </div>
        <div>
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
