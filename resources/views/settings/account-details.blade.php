<x-app-layout>
    <x-slot name="title">{{ __('Account Details') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Account Details') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2>{{ __('Change account email') }}</h2>

    <p>{{ __('This is the email you use to sign into the website.') }}</p>

    <form class="stack" action="{{ localized_route('user-profile-information.update') }}" method="POST" novalidate>
        @csrf
        @method('put')

        <div class="field @error('email', 'updateProfileInformation') field--error @enderror">
            <x-hearth-label for="email" :value="__('Email')" />
            <x-hearth-input name="email" type="email" :value="old('email', $user->email)" required />
            <x-hearth-error for="email" bag="updateProfileInformation" />
        </div>

        <button>
            {{ __('Change email') }}
        </button>
    </form>

    <h2>{{ __('Change password') }}</h2>

    <form class="stack" action="{{ localized_route('user-password.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field @error('current_password', 'updatePassword') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('Current password')" />
            <x-password-input name="current_password" />
            <x-hearth-error for="current_password" bag="updatePassword" />
        </div>

        <div class="field @error('password', 'updatePassword') field--error @enderror">
            <x-hearth-label for="password" :value="__('New password')" />
            <div class="field__hint" id="password-hint">
                <p>{{ __('For your security, please make sure your password has:') }}</p>
                <ul>
                    <li>{{ __('8 characters or more') }}</li>
                    <li>{{ __('At least 1 upper case letter') }}</li>
                    <li>{{ __('At least 1 number') }}</li>
                    <li>{{ __('At least 1 special character (!@#$%^&*)') }}</li>
                </ul>
            </div>
            <x-password-input name="password" hinted />
            <x-hearth-error for="password" bag="updatePassword" />
        </div>

        <div class="field @error('password_confirmation', 'updatePassword') field--error @enderror">
            <x-hearth-label for="password_confirmation" :value="__('Please confirm new password')" />
            <x-password-input name="password_confirmation" />
            <x-hearth-error for="password_confirmation" bag="updatePassword" />
        </div>

        <button>
            {{ __('hearth::auth.change_password') }}
        </button>
    </form>

    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <x-hearth-password-confirmation>
            <h2>{{ __('hearth::user.two_factor_auth') }}</h2>

            <p><em>{{ __('hearth::user.two_factor_auth_intro') }}</em></p>

            @if ($user->twoFactorAuthEnabled())
                <p>{{ __('hearth::user.two_factor_auth_enabled') }}</p>

                @if (session('status') == 'two-factor-authentication-enabled')
                    <p>{{ __('hearth::user.two_factor_auth_qr_code') }}</p>
                    <div>{!! request()->user()->twoFactorQrCodeSvg() !!}</div>
                @endif
                @if (session('status') == 'two-factor-authentication-enabled' || session('status') == 'recovery-codes-generated')
                    <p>{{ __('hearth::user.two_factor_auth_recovery_codes') }}</p>
                    <pre>
@foreach (request()->user()->recoveryCodes() as $code)
{{ $code }}
@endforeach
</pre>
                @endif

                <form action="{{ route('two-factor.regenerate') }}" method="post" @submit.prevent="submitForm">
                    @csrf

                    <button>
                        {{ __('hearth::user.action_regenerate_two_factor_auth_recovery_codes') }}
                    </button>
                </form>

                <form action="{{ route('two-factor.disable') }}" method="post" @submit.prevent="submitForm">
                    @csrf
                    @method('DELETE')

                    <button>
                        {{ __('hearth::user.action_disable_two_factor_auth') }}
                    </button>
                </form>
            @else
                <p>{{ __('hearth::user.two_factor_auth_not_enabled') }}</p>

                <form action="{{ route('two-factor.enable') }}" method="post" @submit.prevent="submitForm">
                    @csrf

                    <button>
                        {{ __('hearth::user.action_enable_two_factor_auth') }}
                    </button>
                </form>
            @endif
        </x-hearth-password-confirmation>
    @endif
</x-app-layout>
