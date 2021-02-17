<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <div>
            {{ __('auth.forgot_intro') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="field">
                <x-label for="email" :value="__('forms.label_email')" />

                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <x-button>
                {{ __('auth.forgot_submit') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>
