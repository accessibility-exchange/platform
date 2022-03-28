<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
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
        <x-auth-validation-errors />

        <form class="stack" method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <!-- Email Address -->
            <div class="field @error('email') field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input type="email" name="email" :value="old('email')" required autofocus />
                <x-hearth-error for="email" />
            </div>

            <x-hearth-button>
                {{ __('hearth::auth.forgot_submit') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
