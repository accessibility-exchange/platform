<form class="stack" method="POST" action="{{ localized_route('register-store') }}" novalidate>
    @csrf

    <x-hearth-input id="locale" type="hidden" name="locale" value="{{ locale() ?: config('app.locale') }}" />

    <!-- Password -->
    <div class="field @error('password') field--error @enderror stack">
        <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
        <x-hearth-input type="password" name="password" required autocomplete="new-password" />
        <x-hearth-error for="password" />
    </div>

    <!-- Confirm Password -->
    <div class="field @error('password') field--error @enderror stack">
        <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />
        <x-hearth-input type="password" name="password_confirmation" required />
        <x-hearth-error for="password" />
    </div>

    <p class="repel">
        <a class="cta" href="{{ localized_route('register', ['step' => 2]) }}">{{ __('Back') }}</a>

        <x-hearth-button>
            {{ __('Next') }}
        </x-hearth-button>
    </p>
</form>
