
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('profile.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('profiles.update', $profile) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="field">
            <x-hearth-label for="name" :value="__('profile.label_name')" />
            <x-hearth-input id="name" type="name" name="name" :value="old('name', $profile->name)" required />
            </div>
        <div class="field">
            <x-hearth-label for="locality" :value="__('forms.label_locality')" />
            <x-hearth-input id="locality" type="locality" name="locality" :value="old('locality', $profile->locality)" required />
        </div>
        <div class="field">
            <x-hearth-label for="region" :value="__('forms.label_region')" />
            <x-hearth-select id="region" name="region" :selected="old('region', $profile->region)" required :options="$regions"/>
        </div>

        <x-hearth-button>{{ __('forms.save_changes') }}</x-hearth-button>
    </form>

    <h2>
        {{ __('profile.delete_title') }}
    </h2>

    <p>{{ __('profile.delete_intro') }}</p>

    <form action="{{ localized_route('profiles.destroy', $profile) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input id="current_password" type="password" name="current_password" required />
            @error('current_password', 'destroyProfile')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-hearth-button>
            {{ __('profile.action_delete') }}
        </x-hearth-button>
    </form>
</x-app-layout>
