<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ localized_route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <div class="field">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />

                <x-hearth-input id="email" type="email" name="email" :value="old('email', request()->get('email'))" required autofocus />
            </div>

            <!-- Password -->
            <div class="field">
                <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />

                <x-hearth-input id="password" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="field">
                <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />

                <x-hearth-input id="password_confirmation"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <x-hearth-button>
                {{ __('hearth::auth.reset_submit') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
