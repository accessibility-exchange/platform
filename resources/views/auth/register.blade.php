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

            <x-input id="locale" type="hidden" name="locale" value="{{ locale() ?: 'en' }}" />

            <!-- Name -->
            <div class="field">
                <x-label for="name" :value="__('user.label_name')" />

                <x-input id="name" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="field">
                <x-label for="email" :value="__('forms.label_email')" />

                <x-input id="email" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="field">
                <x-label for="password" :value="__('auth.label_password')" />

                <x-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="field">
                <x-label for="password_confirmation" :value="__('auth.label_password_confirmation')" />

                <x-input id="password_confirmation"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="field">
                <a href="{{ localized_route('login') }}">
                    {{ __('auth.existing_account_prompt') }}
                </a>
            </div>

            <x-button>
                {{ __('auth.create_your_account') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>
