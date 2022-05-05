<x-app-layout>
    <x-slot name="title">{{ __('Basic information') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Basic information') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('user-profile-information.update') }}" method="POST" novalidate>
        @csrf

        @method('PUT')

        <div class="field @error('name', 'updateProfileInformation') field--error @enderror">
            <x-hearth-label for="name" :value="__('hearth::user.label_name')" />
            <x-hearth-input id="name" type="text" name="name" :value="old('name', $user->name)" required />
            <x-hearth-error for="name" bag="updateProfileInformation" />
        </div>

        <div class="field @error('email', 'updateProfileInformation') field--error @enderror">
            <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
            <x-hearth-input id="email" type="email" name="email" :value="old('email', $user->email)" required />
            <x-hearth-error for="email" bag="updateProfileInformation" />
        </div>

        <div class="field @error('locale', 'updateProfileInformation') field--error @enderror">
            <x-hearth-label for="locale" :value="__('hearth::user.label_locale')" />
            <x-hearth-locale-select name="locale" :selected="old('locale', $user->locale)" />
            <x-hearth-error for="locale" bag="updateProfileInformation" />
        </div>

        <button>
            {{ __('Save changes') }}
        </button>
    </form>
</x-app-layout>
