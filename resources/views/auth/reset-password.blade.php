<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                Accessibility in Action
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="field">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="field">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="field">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <x-button>
                {{ __('Reset Password') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>
