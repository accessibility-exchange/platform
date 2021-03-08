
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('consultant-profile.edit_title') }}
        </h1>
    </x-slot>

    <form action="{{ localized_route('consultant-profiles.update', $consultantProfile) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="field">
            <x-label for="name" :value="__('consultant-profile.label_name')" />
            <x-input id="name" type="name" name="name" :value="old('name', $consultantProfile->name)" required />
            </div>
        <div class="field">
            <x-label for="locality" :value="__('forms.label_locality')" />
            <x-input id="locality" type="locality" name="locality" :value="old('locality', $consultantProfile->locality)" required />
        </div>
        <div class="field">
            <x-label for="region" :value="__('forms.label_region')" />
            <x-region-select :selected="old('region', $consultantProfile->region)" required />
        </div>

        <x-button>{{ __('forms.save_changes') }}</x-button>
    </form>

    <h2>
        {{ __('consultant-profile.delete_title') }}
    </h2>

    <p>{{ __('consultant-profile.delete_intro') }}</p>

    <form action="{{ localized_route('consultant-profiles.destroy', $consultantProfile) }}" method="POST" novalidate>
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
            {{ __('consultant-profile.delete_title') }}
        </x-button>
    </form>
</x-app-layout>
