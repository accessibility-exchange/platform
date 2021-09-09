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

        <x-hearth-alert :title="__('Need some support?')">
            <p>{!! __('Email <a href="mailto:support@accessibility-in-action.ca">support@accessibility-in-action.ca</a> or call 1-(800) 123-4567 for help creating your account.') !!}</p>
        </x-hearth-alert>

        <form method="POST" action="{{ localized_route('register-store') }}" novalidate>
            @csrf

            <x-hearth-input id="context" type="hidden" name="context" value="{{ request()->get('context') ?: 'consultant' }}" />

            <x-hearth-input id="locale" type="hidden" name="locale" value="{{ locale() ?: config('app.locale') }}" />

            <!-- Name -->
            <div class="field @error('name') field--error @enderror">
                <x-hearth-label for="name" :value="__('hearth::user.label_name')" />
                <x-hearth-input type="text" name="name" value="{{ old('name') }}" required autofocus />
                <x-hearth-error for="name" />
            </div>

            <!-- Email Address -->
            <div class="field @error('email') field--error @enderror">
                <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
                <x-hearth-input type="email" name="email" value="{{ old('email') }}" required />
                <x-hearth-error for="email" />
            </div>

            <!-- Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
                <x-hearth-input type="password" name="password" required autocomplete="new-password" />
                <x-hearth-error for="password" />
            </div>

            <!-- Confirm Password -->
            <div class="field @error('password') field--error @enderror">
                <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />
                <x-hearth-input type="password" name="password_confirmation" required />
                <x-hearth-error for="password" />
            </div>

            <!-- Access Needs -->
            <div class="field @error('access') field--error @enderror">
                <x-hearth-label for="access" :value="__('Access support needs (optional)')" />
                <textarea id="access" name="access" aria-describedby="access-hint @error('password') access-error @enderror"></textarea>
                <p class="field__hint" id="access-hint">{{ __('Please let us know if you need any access support to use this website.') }}
                <x-hearth-error for="access" />
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
