<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('user.my_profile') }}
        </h1>
    </x-slot>

    @unless($user->hasVerifiedEmail())
    <x-alert type="info" :title="__('auth.verification_required')">
        <p>{{ __('auth.updated_verification_sent') }}</p>
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

    @if(session('status') === 'profile-information-updated')
    <x-alert type="success" :title="__('user.profile_updated')">
        <p>{{ __('user.profile_updated_message') }}</p>
    </x-alert>
    @endif

    <h2>{{ __('user.update_profile') }}</h2>

    <form action="{{ localized_route('user-profile-information.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="field">
            <x-label for="name" :value="__('user.label_name')" />
            <x-input id="name" type="name" name="name" :value="$user->name" required novalidated />
            @error('name', 'updateProfileInformation')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="email" :value="__('forms.label_email')" />
            <x-input id="email" type="email" name="email" :value="$user->email" required novalidated />
            @error('email', 'updateProfileInformation')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="locale" :value="__('user.label_locale')" />
            <x-locale-select :selected="$user->locale" />
        </div>
        <x-button>
            {{ __('forms.save_changes') }}
        </x-button>
    </form>

    <h2>{{ __('auth.change_password') }}</h2>

    <form action="{{ localized_route('user-password.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="field">
            <x-label for="current_password" :value="__('auth.label_current_password')" />
            <x-input id="current_password" type="password" name="current_password" required novalidated />
            @error('current_password', 'updatePassword')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="password" :value="__('auth.label_password')" />
            <x-input id="password" type="password" name="password" required novalidated />
            @error('password', 'updatePassword')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>



        <div class="field">
            <x-label for="password_confirmation" :value="__('auth.label_password_confirmation')" />
            <x-input id="password_confirmation" type="password" name="password_confirmation" required novalidated />
            @error('password_confirmation', 'updatePassword')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-button>
            {{ __('auth.change_password') }}
        </x-button>
    </form>

    <h2>{{ __('user.delete_account') }}</h2>

    <p>{{ __('user.delete_account_message') }}</p>

    <form action="{{ localized_route('users.destroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <x-button>
            {{ __('user.delete_account') }}
        </x-button>
    </form>
</x-app-layout>
