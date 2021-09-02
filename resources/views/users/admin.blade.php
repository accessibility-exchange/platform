<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('hearth::user.account') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2>{{ __('hearth::auth.change_password') }}</h2>

    <form action="{{ localized_route('user-password.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field @error('current_password', 'updatePassword') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input id="current_password" type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="updatePassword" />
        </div>

        <div class="field @error('password', 'updatePassword') field--error @enderror">
            <x-hearth-label for="password" :value="__('hearth::auth.label_password')" />
            <x-hearth-input id="password" type="password" name="password" required />
            <x-hearth-error for="password" bag="updatePassword" />
        </div>

        <div class="field @error('password_confirmation', 'updatePassword') field--error @enderror">
            <x-hearth-label for="password_confirmation" :value="__('hearth::auth.label_password_confirmation')" />
            <x-hearth-input id="password_confirmation" type="password" name="password_confirmation" required />
            <x-hearth-error for="password_confirmation" bag="updatePassword" />
        </div>

        <x-hearth-button>
            {{ __('hearth::auth.change_password') }}
        </x-hearth-button>
    </form>

    @if(Laravel\Fortify\Features::canManageTwoFactorAuthentication())
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
@endforeach</pre>
            @endif

            <form action="{{ route('two-factor.regenerate') }}" method="post" @submit.prevent="submitForm">
                @csrf

                <x-hearth-button>
                    {{ __('hearth::user.action_regenerate_two_factor_auth_recovery_codes') }}
                </x-hearth-button>
            </form>

            <form action="{{ route('two-factor.disable') }}" method="post" @submit.prevent="submitForm">
                @csrf
                @method('DELETE')

                <x-hearth-button>
                    {{ __('hearth::user.action_disable_two_factor_auth') }}
                </x-hearth-button>
            </form>
        @else
            <p>{{ __('hearth::user.two_factor_auth_not_enabled') }}</p>

            <form action="{{ route('two-factor.enable') }}" method="post" @submit.prevent="submitForm">
                @csrf

                <x-hearth-button>
                    {{ __('hearth::user.action_enable_two_factor_auth') }}
                </x-hearth-button>
            </form>
        @endif
    </x-hearth-password-confirmation>
    @endif

    <h2>{{ __('hearth::user.delete_account') }}</h2>

    <p>{{ __('hearth::user.delete_account_intro') }}</p>

    <form action="{{ localized_route('users.destroy') }}" method="post" novalidate>
        @csrf
        @method('delete')

        <div class="field @error('current_password', 'destroyAccount') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input id="current_password" type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyAccount" />
        </div>

        <x-hearth-button>
            {{ __('hearth::user.action_delete_account') }}
        </x-hearth-button>
    </form>
</x-app-layout>
