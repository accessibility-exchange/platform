<div class="wrapper">
    <ul role="list">
        <li>
            <strong>{{ __('Call') }}<span class="sep"> • </span>{{ __('Text') }}<span class="sep"> • </span>{{ __('VRS') }}</strong> {{ settings()->get('phone', '1-800-123-4567') }}
        </li>

        <li>
            <a href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">
                {{ settings()->get('email', 'support@accessibilityexchange.ca') }}
            </a>
        </li>
        <li class="exit">
            @auth
            <form method="POST" action="{{ localized_route('exit') }}">
                @csrf
                <button type="submit">
                    {{ __('Quick exit') }}
                </button>
            </form>
            @else
            <a class="button" rel="nofollow noopener noreferrer" href="https://weather.com">
                <x-heroicon-o-logout aria-hidden="true" />
                {{ __('Quick exit') }}
            </a>
            @endauth
        </li>
    </ul>
</div>
