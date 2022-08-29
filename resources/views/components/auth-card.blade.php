<div class="stack hero">
    <div class="stack center">
        <!-- Validation Errors -->
        <x-auth-validation-errors />

        <!-- Session Status -->
        @if (session('status') == 'verification-link-sent')
            <x-hearth-alert type="success">
                {{ __('hearth::auth.verification_sent') }}
            </x-hearth-alert>
        @else
            <x-auth-session-status :status="session('status')" />
        @endif
    </div>
    <div class="auth-card center">
        <div class="stack">
            <a href="{{ localized_route('welcome') }}" rel="home">
                <x-tae-logo class="logo" role="presentation" />
                <x-tae-logo-mono class="logo logo--themeable" role="presentation" />
                <span class="visually-hidden">{{ __('app.name') }}</span>
            </a>
            <h1 class="align:center">{{ $title }}</h1>
            {{ $slot }}
        </div>
    </div>
</div>
