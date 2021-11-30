<x-app-layout>
    <x-slot name="title">{{ __('Delete account') }}</x-slot>
    <x-slot name="header">
        <p class="breadcrumb"><x-heroicon-o-chevron-left width="24" height="24" /><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></p>
        <h1>
            {{ __('Delete account') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

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
