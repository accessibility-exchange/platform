<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Choose new password') }}
        </x-slot>

        <form class="stack" method="POST" action="{{ localized_route('password.update') }}" novalidate>
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">
            <input type="hidden" name="email" value="{{ request()->get('email') }}">

            <!-- Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password" :value="__('New password')" />
                <x-hearth-hint for="password">{{ __('Passwords must be at least eight characters in length and include at least one uppercase letter, at least one number, and at least one special character.') }}</x-hearth-hint>
                <x-password-input name="password" hinted />
                <x-hearth-error for="password" />
            </div>

            <!-- Confirm Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password_confirmation" :value="__('Confirm new password')" />
                <x-password-input name="password_confirmation" />
                <x-hearth-error for="password" />
            </div>

            <button>
                {{ __('Save new password') }}
            </button>
        </form>
    </x-auth-card>
</x-guest-layout>
