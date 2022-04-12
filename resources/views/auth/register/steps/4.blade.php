<form class="stack" method="POST" action="{{ localized_route('register-store') }}" novalidate>
    @csrf

    <x-hearth-input id="locale" type="hidden" name="locale" value="{{ locale() ?: config('app.locale') }}" />

    <!-- Password -->
    <div class="field @error('password') field--error @enderror stack">
        <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
        <x-hearth-hint for="password">
            {{ __('Passwords must be at least eight characters in length and include at least one uppercase letter, at least one number, and at least one special character.') }}
        </x-hearth-hint>
        <x-password-input name="password" autocomplete="new-password" />
        <x-hearth-error for="password" />
    </div>

    <!-- Confirm Password -->
    <div class="field @error('password') field--error @enderror stack">
        <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />
        <x-password-input name="password_confirmation" />
        <x-hearth-error for="password" />
    </div>

    <p class="repel">
        <a class="cta secondary" href="{{ localized_route('register', ['step' => 3]) }}">{{ __('Back') }}</a>

        <x-hearth-button>
            {{ __('Create account') }}
        </x-hearth-button>
    </p>
</form>
