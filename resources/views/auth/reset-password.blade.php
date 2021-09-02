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

        <!-- Validation Errors -->
        @if ($errors->any())
        <x-hearth-alert type="error">
            <p>{{ __('hearth::auth.error_intro') }}</p>
        </x-hearth-alert>
        @endif

        <form method="POST" action="{{ localized_route('password.update') }}" novalidate>
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <div class="field @error('email') field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input type="email" name="email" value="{{ old('email', request()->get('email')) }}" required autofocus />
                <x-hearth-error for="email" />
            </div>

            <!-- Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
                <x-hearth-input type="password" name="password" required />
                <x-hearth-error for="password" />
            </div>

            <!-- Confirm Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />
                <x-hearth-input type="password" name="password_confirmation" required />
                <x-hearth-error for="password" />
            </div>

            <x-hearth-button>
                {{ __('hearth::auth.reset_submit') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
