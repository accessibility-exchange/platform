@if (flash()->message)
    <x-alert :type="flash()->class">
        <p>{{ flash()->message }}
    </x-alert>
@endif

@if(session('status') === 'verification-link-sent')
<x-alert type="success">
    <p>{{ __('auth.verification_sent') }}</p>
</x-alert>
@endif

@if(session('status') === 'profile-information-updated')
<x-alert type="success">
    <p>{{ __('user.settings_update_succeeded') }}</p>
</x-alert>
@endif

@if(session('status') === 'password-updated')
<x-alert type="success">
    <p>{{ __('auth.password_change_succeeded') }}</p>
</x-alert>
@endif

@auth
@unless(Auth::user()->hasVerifiedEmail())
    <x-alert type="info">
        <p>{{ __('auth.verification_intro') }}</p>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <x-button>
                    {{ __('auth.resend_verification_email') }}
                </x-button>
            </div>
        </form>
    </x-alert>
@endunless
@endauth


