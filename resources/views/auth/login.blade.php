<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Sign in') }}
        </x-slot>

        <form class="stack" method="POST" action="{{ localized_route('login-store') }}" novalidate>
            @csrf

            <!-- Email Address -->
            <div class="field @error('email') field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input name="email" type="email" :value="old('email')" required autofocus />
                <x-hearth-error for="email" />
            </div>

            <!-- Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
                <x-password-input name="password" />
                <x-hearth-error for="password" />
            </div>

            <p>
                <a href="{{ localized_route('password.request') }}">
                    {{ __('hearth::auth.forget_prompt') }}
                </a>
            </p>

            <!-- Remember Me -->
            <div class="field">
                <x-hearth-input name="remember" type="checkbox" />
                <x-hearth-label for="remember" :value="__('hearth::auth.label_remember_me')" />
            </div>

            <x-hearth-button>
                {{ __('hearth::auth.sign_in') }}
            </x-hearth-button>

                <p>
                    {{ __('Don’t have an account yet?') }} <a href="{{ localized_route('register') }}">{{ __('Create an account') }}</a>
                </p>
        </form>
    </x-auth-card>
</x-guest-layout>
