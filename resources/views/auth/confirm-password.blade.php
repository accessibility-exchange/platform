<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                Accessibility in Action
            </a>
        </x-slot>

        <div>
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div class="field">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <x-button>
                {{ __('Confirm') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>
