<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ localized_route('register') }}">
            @csrf

            <x-hearth-input id="locale" type="hidden" name="locale" value="{{ locale() ?: config('app.locale') }}" />

            <!-- Name -->
            <div class="field">
                <x-hearth-label for="name" :value="__('hearth::user.label_name')" />

                <x-hearth-input id="name" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="field">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />

                <x-hearth-input id="email" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="field">
                <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />

                <x-hearth-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="field">
                <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />

                <x-hearth-input id="password_confirmation"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="field">
                <a href="{{ localized_route('login') }}">
                    {{ __('hearth::auth.existing_account_prompt') }}
                </a>
            </div>

            <x-hearth-button>
                {{ __('hearth::auth.create_your_account') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
