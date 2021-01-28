<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                Accessibility in Action
            </a>
        </x-slot>

        <div>
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="field">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <x-button>
                {{ __('Email Password Reset Link') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>
