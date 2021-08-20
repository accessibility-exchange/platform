<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'Accessibility in Action') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Confirm your password') }}
        </x-slot>

        <div>
            {{ __('hearth::auth.confirm_intro') }}
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
        <x-hearth-alert type="error">
            <p>{{ __('hearth::auth.error_intro') }}</p>
        </x-hearth-alert>
        @endif


        <form method="POST" action="{{ localized_route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div class="field @error('password')field--error @enderror">
                <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
                <input id="password" type="password" name="password" required autocomplete="current-password" @error('password')aria-describedby="password-error"@enderror />
                <x-field-error for="password" />
            </div>

            <x-hearth-button>
                {{ __('hearth::auth.action_confirm') }}
            </x-hearth-button>
        </form>
    </x-auth-card>
</x-guest-layout>
