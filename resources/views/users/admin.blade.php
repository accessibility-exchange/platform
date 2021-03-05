<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('user.my_account') }}
        </h1>
    </x-slot>

    <h2>{{ __('auth.change_password') }}</h2>

    <form action="{{ localized_route('user-password.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <x-label for="current_password" :value="__('auth.label_current_password')" />
            <x-input id="current_password" type="password" name="current_password" required />
            @error('current_password', 'updatePassword')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="password" :value="__('auth.label_password')" />
            <x-input id="password" type="password" name="password" required />
            @error('password', 'updatePassword')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <div class="field">
            <x-label for="password_confirmation" :value="__('auth.label_password_confirmation')" />
            <x-input id="password_confirmation" type="password" name="password_confirmation" required />
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

    <form action="{{ localized_route('users.destroy') }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-label for="current_password" :value="__('auth.label_current_password')" />
            <x-input id="current_password" type="password" name="current_password" required />
            @error('current_password', 'destroyAccount')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-button>
            {{ __('user.delete_account') }}
        </x-button>
    </form>
</x-app-layout>
