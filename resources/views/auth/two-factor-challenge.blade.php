<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Two-factor authentication') }}
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors />

        <form method="POST" action="{{ localized_route('two-factor.login') }}" x-data="{ recovery: false }" novalidate>
            @csrf

            <p x-show="! recovery">
                {{ __('hearth::auth.two_factor_auth_code_intro') }}
            </p>

            <!-- Two-Factor Code -->
            <div class="field" x-show="! recovery">
                <x-hearth-label for="code" :value="__('hearth::auth.label_two_factor_auth_code')" />
                <x-hearth-input type="text" name="code" inputmode="numeric" required autofocus autocomplete="one-time-code" />
            </div>

            <p x-show="! recovery">
                <x-hearth-button type="button" class="link" @click="recovery = ! recovery">{{ __('hearth::auth.two_factor_auth_action_use_recovery_code') }}</x-hearth-button>
            </p>

            <p x-show="recovery">
                {{ __('hearth::auth.two_factor_auth_recovery_code_intro') }}
            </p>

            <!-- Recovery Code -->
            <div class="field" x-show="recovery">
                <x-hearth-label for="recovery_code" :value="__('hearth::auth.label_two_factor_auth_recovery_code')" />
                <x-hearth-input type="text" name="recovery_code" autocomplete="one-time-code" />
            </div>

            <p x-show="recovery">
                <x-hearth-button type="button" class="link" @click="recovery = ! recovery">{{ __('hearth::auth.two_factor_auth_action_use_code') }}</x-hearth-button>
            </p>

            <x-hearth-button>
                {{ __('hearth::auth.sign_in') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
