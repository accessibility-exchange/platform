<h2>{{ __('Need some support?') }}</h2>

<p>{{ __('Get help creating your account by contacting us.') }}</p>

<h3>{{ __('Email') }}</h3>

<p>
    <a href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">
        {{ settings()->get('email', 'support@accessibilityexchange.ca') }}
    </a><br />
    {{ __('Average response time: :time', ['time' => __(':number days', ['number' => 2])]) }}
</p>

<h3>{{ __('Call, text, and video relay service') }}</h3>
<p>
    {{ __('Phone or text message') }}<br />
    <a href="tel:{{ settings()->get('phone', '1-800-123-4567') }}" >
        {{ settings()->get('phone', '1-800-123-4567') }}
    </a>
</p>
<p>
    {{ __('Video relay service') }}<br />
    <a href="tel:{{ settings()->get('vrs', '1-800-123-4567') }}" >
        {{ settings()->get('vrs', '1-800-123-4567') }}
    </a>
</p>

<p>
    <a class="button" href="{{ localized_route('welcome') }}">{{ __('Back') }}</a>
    <a class="button" href="{{ localized_route('register', ['step' => 1]) }}">{{ __('Get started') }}</a>
</p>
