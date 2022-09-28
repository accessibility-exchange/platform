<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Reset your password') }}
        </x-slot>

        <div class="stack">
            <h4>
                {{ __('Please reset your password for The Accessibility Exchange') }}
            </h4>
            <p>
                {{ __('If you have forgotten your password, please enter the email address that you used to sign up for The Accessibility Exchange. We will email you a link that will let you choose a new password.') }}
            </p>
        </div>

        <form class="stack" method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <!-- Email Address -->
            <div class="field @error('email') field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input name="email" type="email" :value="old('email')" required autofocus />
                <x-hearth-error for="email" />
            </div>

            <button>
                {{ __('hearth::auth.forgot_submit') }}
            </button>
        </form>
    </x-auth-card>
</x-guest-layout>
