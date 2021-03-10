<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ localized_route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="field">
                <x-label for="email" :value="__('forms.label_email')" />

                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="field">
                <x-label for="password" :value="__('auth.label_password')" />

                <x-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="field">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">
                    <span>{{ __('auth.label_remember_me') }}</span>
                </label>
            </div>

            @if (Route::has('en.password.request'))
            <p>
                <a href="{{ localized_route('password.request') }}">
                    {{ __('auth.forget_prompt') }}
                </a>
            </p>
            @endif

            <x-button>
                {{ __('auth.sign_in') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>
