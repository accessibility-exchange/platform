<div class="stack hero">
    <div class="stack center">
        <!-- Validation Errors -->
        <x-auth-validation-errors />

        <!-- Session Status -->
        @if (session('status') == 'verification-link-sent')
            <x-live-region>
                <x-hearth-alert type="success">
                    <x-interpretation name="{{ __('hearth::auth.verification_sent', [], 'en') }}" namespace="auth_card" />
                    {{ __('hearth::auth.verification_sent') }}
                </x-hearth-alert>
            </x-live-region>
        @else
            <x-auth-session-status :status="session('status')" />
        @endif
    </div>
    <div class="auth-card center">
        <div class="stack">
            <a class="brand" href="{{ localized_route('welcome') }}" rel="home">
                @svg('tae-logo', ['class' => 'logo'])
                @svg('tae-logo-mono', ['class' => 'logo logo--themeable'])
                <span class="visually-hidden">{{ __('app.name') }}</span>
            </a>
            <h1 class="text-center">{{ $title }}</h1>
            {{ $slot }}
        </div>
    </div>
</div>
