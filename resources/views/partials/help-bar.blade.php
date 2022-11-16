<div class="help-bar darker">
    <div class="center center:wide cluster justify:between">
        <div x-data="{ open: false }" @click.away="open = false">
            <button class="borderless" x-bind:aria-expanded="open.toString()" x-on:click="open = !open"
                @keyup.escape.window="open = false" x-cloak>
                {{ __('Get help') }}
                @svg('heroicon-o-chevron-down', 'indicator')
            </button>
            <div class="responsive-switcher">
                <div>
                    @svg('heroicon-o-phone')&nbsp;<span
                        class="font-semibold">{!! __('Call or :vrs', [
                            'vrs' =>
                                '<a href="https://srvcanadavrs.ca/en/resources/resource-centre/vrs-basics/register/" rel="external">' .
                                __('VRS') .
                                '</a>',
                        ]) !!}:</span>&nbsp;{{ phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA') }}
                </div>
                <div>
                    @svg('heroicon-o-mail')&nbsp;<span class="font-semibold">{{ __('Email') }}:</span>&nbsp;<a
                        href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">
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
                <a class="cta" href="https://weather.com" rel="nofollow noopener noreferrer">
                    {{ __('Quick exit') }}
                </a>
            @endauth
        </div>
    </div>
</div>
