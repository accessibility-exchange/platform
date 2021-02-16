<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="field">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="field">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="field">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">
                    <span>{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif
            </div>

            <x-button>
                {{ __('Login') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>
