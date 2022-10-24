<form class="stack" method="POST" action="{{ localized_route('register-store') }}" novalidate>
    @csrf

    <x-hearth-input id="locale" name="locale" type="hidden" value="{{ locale() ?: config('app.locale') }}" />

    <!-- Password -->
    <div class="field @error('password') field--error @enderror stack">
        <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
        <div class="field__hint" id="password-hint">
            <p>{{ __('For your security, please make sure your password has:') }}</p>
            <ul>
                <li>{{ __('8 characters or more') }}</li>
                <li>{{ __('At least 1 upper case letter') }}</li>
                <li>{{ __('At least 1 number') }}</li>
                <li>{{ __('At least 1 special character (!@#$%^&*()-)') }}</li>
            </ul>
        </div>
        <x-password-input name="password" autocomplete="new-password" hinted />
        <x-hearth-error for="password" />
    </div>

    <!-- Confirm Password -->
    <div class="field @error('password') field--error @enderror stack">
        <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />
        <x-password-input name="password_confirmation" />
        <x-hearth-error for="password" />
    </div>

    <div class="stack">
        <div class="field @error('accepted_terms_of_service') field--error @enderror">
            <x-hearth-checkbox name="accepted_terms_of_service" required />
            <label for="accepted_terms_of_service">
                {{ __('I agree to the ') }}
                <a class="with-icon" href="{{ localized_route('about.terms-of-service') }}" target="_blank">
                    <span class="underline">{{ __('terms of service') }}</span>
                    <x-heroicon-o-arrow-top-right-on-square class="ml-1 h-5 w-5" role="presentation"
                        aria-hidden="true" />
                    <span class="visually-hidden"> ({{ __('Opens in new tab') }}) </span>
                </a>
                {{ __('for using The Accessibility Exchange') }}
            </label>
            <x-hearth-error for="accepted_terms_of_service" />
        </div>
        <div class="field @error('accepted_privacy_policy') field--error @enderror">
            <x-hearth-checkbox name="accepted_privacy_policy" required />
            <label for="accepted_privacy_policy">
                {{ __('I agree to the ') }}
                <a class="with-icon" href="{{ localized_route('about.privacy-policy') }}" target="_blank">
                    <span class="underline">{{ __('privacy policy') }}</span>
                    <x-heroicon-o-arrow-top-right-on-square class="ml-1 h-5 w-5" role="presentation"
                        aria-hidden="true" />
                    <span class="visually-hidden"> ({{ __('Opens in new tab') }}) </span>
                </a>
                {{ __('for using The Accessibility Exchange') }}
            </label>
            <x-hearth-error for="accepted_privacy_policy" />
        </div>
    </div>

    <p class="repel">
        <a class="cta secondary" href="{{ localized_route('register', ['step' => 3]) }}">{{ __('Back') }}</a>

        <button>
            {{ __('Create account') }}
        </button>
    </p>
</form>
