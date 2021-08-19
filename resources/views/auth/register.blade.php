<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Create an account') }}
        </x-slot>

        <!-- Validation Errors -->
        @if ($errors->any())
            <x-hearth-alert type="error">
                <p>{{ __('hearth::auth.error_intro') }}</p>
            </x-hearth-alert>
        @endif

        <form method="POST" action="{{ localized_route('register') }}" novalidate>
            @csrf

            <x-hearth-input id="context" type="hidden" name="context" value="{{ request()->get('context') ?: 'consultant' }}" />

            <x-hearth-input id="locale" type="hidden" name="locale" value="{{ locale() ?: config('app.locale') }}" />

            <!-- Name -->
            <div class="field @error('name')field--error @enderror">
                <x-hearth-label for="name" :value="__('hearth::user.label_name')" />
                <x-hearth-input id="name" type="text" name="name" :value="old('name')" required autofocus />
                <x-field-error for="name" />
            </div>

            <!-- Email Address -->
            <div class="field @error('email')field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input id="email" type="email" name="email" :value="old('email')" required />
                <x-field-error for="email" />
            </div>

            <!-- Password -->
            <div class="field @error('password')field--error @enderror">
                <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
                <x-hearth-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-field-error for="password" />
            </div>

            <!-- Confirm Password -->
            <div class="field @error('password')field--error @enderror">
                <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />
                <x-hearth-input id="password_confirmation"
                                type="password"
                                name="password_confirmation" required />
                <x-field-error for="password" />
            </div>

            <p>
                <a href="{{ localized_route('login') }}">
                    {{ __('hearth::auth.existing_account_prompt') }}
                </a>
            </p>

            <x-hearth-button>
                {{ __('hearth::auth.create_your_account') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
