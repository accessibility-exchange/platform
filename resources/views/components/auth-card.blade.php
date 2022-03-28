<div class="stack hero">
    <div class="center center:text">
        <div class="stack">
            <a href="{{ localized_route('welcome') }}" rel="home">
                <x-tae-logo class="logo" />
                <x-tae-logo-mono class="logo logo--themeable" />
                <span class="visually-hidden">{{ __('app.name') }}</span>
            </a>
            <h1 class="align-center">{{ $title }}</h1>
            {{ $slot }}
        </div>
    </div>
</div>
