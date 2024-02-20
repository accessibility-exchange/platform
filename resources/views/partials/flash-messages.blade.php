@spaceless
    <x-live-region>
        @if (flash()->message)
            <x-hearth-alert :type="Str::before(flash()->class, '|')" :title="Str::contains(flash()->message, __('You are previewing your')) ? __('Draft') : null">
                <p>{{ flash()->message }}</p>
                @if (Str::contains(flash()->class, '|'))
                    <x-interpretation name="{{ __(flash()->class, [], 'en') }}" namespace="flash_messages" />
                @endif
            </x-hearth-alert>
        @endif

        @if (session('status') === 'verification-link-sent')
            <x-hearth-alert type="success">
                <x-interpretation name="hearth::auth.verification_sent" namespace="flash_messages" />
                <p>{{ __('hearth::auth.verification_sent') }}</p>
            </x-hearth-alert>
        @endif

        @if (session('status') === 'password-updated')
            <x-hearth-alert type="success">
                <x-interpretation name="hearth::auth.password_change_succeeded" namespace="flash_messages" />
                <p>{{ __('hearth::auth.password_change_succeeded') }}</p>
            </x-hearth-alert>
        @endif

        @if (session('status') === 'two-factor-authentication-enabled')
            <x-hearth-alert type="success">
                <x-interpretation name="hearth::user.two_factor_auth_enabled" namespace="flash_messages" />
                <p>{{ __('hearth::user.two_factor_auth_enabled') }}</p>
            </x-hearth-alert>
        @endif

        @if (session('status') === 'recovery-codes-generated')
            <x-hearth-alert type="success">
                <x-interpretation name="hearth::user.two_factor_auth_recovery_codes_regenerated"
                    namespace="flash_messages" />
                <p>{{ __('hearth::user.two_factor_auth_recovery_codes_regenerated') }}</p>
            </x-hearth-alert>
        @endif

        @if (session('status') === 'two-factor-authentication-disabled')
            <x-hearth-alert type="success">
                <x-interpretation name="hearth::user.two_factor_auth_disabled" namespace="flash_messages" />
                <p>{{ __('hearth::user.two_factor_auth_disabled') }}</p>
            </x-hearth-alert>
        @endif

        @auth
            @unless (Auth::user()->hasVerifiedEmail())
                <x-hearth-alert type="notice" x-show="true" :dismissable="false">
                    <x-interpretation name="hearth::auth.verification_intro" namespace="flash_messages" />
                    <p>{{ __('hearth::auth.verification_intro') }}</p>
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div>
                            <button>
                                {{ __('hearth::auth.resend_verification_email') }}
                            </button>
                        </div>
                    </form>
                </x-hearth-alert>
            @endunless
        @endauth
    </x-live-region>
@endspaceless
