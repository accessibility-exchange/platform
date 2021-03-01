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

    <form action="{{ localized_route('user-profile-information.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="name">{{ __('user.label_name') }}</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" />
        </div>

        <div class="field">
            <label for="email">{{ __('forms.label_email') }}</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" />
        </div>

        <div class="field">
            <label for="locale">{{ __('user.label_locale') }}</label>
            <x-locale-select :selected="$user->locale" />
        </div>
        <button type="submit">{{ __('forms.save_changes') }}</button>
    </form>

    <form action="{{ localized_route('users.destroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">{{ __('user.delete_account') }}</button>
    </form>
</x-app-layout>
