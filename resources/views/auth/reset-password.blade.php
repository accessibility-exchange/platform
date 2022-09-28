<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Choose a new password') }}
        </x-slot>

        <form class="stack" method="POST" action="{{ localized_route('password.update') }}" novalidate>
            @csrf

            <!-- Password Reset Token -->
            <input name="token" type="hidden" value="{{ request()->route('token') }}">
            <input name="email" type="hidden" value="{{ request()->get('email') }}">

            <h4>
                {{ __('Please choose a new password for The Accessibility Exchange') }}
            </h4>

            <!-- Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password" :value="__('New password')" />
                <div class="field__hint" id="password-hint">
                    <p>{{ __('For your security, please make sure your password has:') }}</p>
                    <ul>
                        <li>{{ __('8 characters or more') }}</li>
                        <li>{{ __('At least 1 upper case letter') }}</li>
                        <li>{{ __('At least 1 number') }}</li>
                        <li>{{ __('At least 1 special character (!@#$%^&*()-)') }}</li>
                    </ul>
                </div>
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
