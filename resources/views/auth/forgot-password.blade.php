<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Reset your password') }}
        </x-slot>

        <div>
            {{ __('hearth::auth.forgot_intro') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        @if ($errors->any())
            <x-hearth-alert type="error">
                <p>{{ __('hearth::auth.error_intro') }}</p>
            </x-hearth-alert>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="field @error('email')field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                <x-field-error for="email" />
            </div>

            <x-hearth-button>
                {{ __('hearth::auth.forgot_submit') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
