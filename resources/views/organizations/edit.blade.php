
<x-app-layout>
    <x-slot name="title">{{ __('organization.edit_title', ['name' => $organization->name]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('organization.edit_title', ['name' => $organization->name]) }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('organizations.update', $organization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('organization.label_name')" />
            <x-hearth-input id="name" type="text" name="name" :value="old('name', $organization->name)" required />
            <x-hearth-error for="name" />
        </div>
        <div class="field @error('locality') field--error @enderror">
            <x-hearth-label for="locality" :value="__('forms.label_locality')" />
            <x-hearth-input id="locality" type="text" name="locality" :value="old('locality', $organization->locality)" required />
            <x-hearth-error for="locality" />
        </div>
        <div class="field @error('region') field--error @enderror">
            <x-hearth-label for="region" :value="__('forms.label_region')" />
            <x-hearth-select id="region" name="region" :selected="old('region', $organization->region)" required :options="$regions"/>
            <x-hearth-error for="region" />
        </div>

        <button>{{ __('Save changes') }}</button>
    </form>
</x-app-layout>
