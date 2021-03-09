
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.edit_title') }}
        </h1>
    </x-slot>

    <form action="{{ localized_route('organizations.update', $organization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <x-label for="name" :value="__('organization.label_name')" />
            <x-input id="name" type="name" name="name" :value="old('name', $organization->name)" required />
            </div>
        <div class="field">
            <x-label for="locality" :value="__('forms.label_locality')" />
            <x-input id="locality" type="locality" name="locality" :value="old('locality', $organization->locality)" required />
        </div>
        <div class="field">
            <x-label for="region" :value="__('forms.label_region')" />
            <x-region-select :selected="old('region', $organization->region)" required />
        </div>

        <x-button>{{ __('forms.save_changes') }}</x-button>
    </form>
</x-app-layout>
