
<x-app-wide-layout>
    <x-slot name="title">{{ $regulatedOrganization->name }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $regulatedOrganization->name }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <x-translation-manager :model="$regulatedOrganization" />

    <form class="stack" action="{{ localized_route('regulated-organizations.update', $regulatedOrganization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <h2>{{ __('Organization information') }}</h2>

        <div class="field @error('name') field--error @enderror">
            <x-translatable-input name="name" :model="$regulatedOrganization" :label="__('Federally regulated organization name')" required />
        </div>

        <fieldset>
            <legend class="h3">{{ __('Your headquarters location (required)') }}</legend>

            <div class="field">
                <x-hearth-label for="locality" :value="__('forms.label_locality')" />
                <x-hearth-input id="locality" type="text" name="locality" :value="old('locality', $regulatedOrganization->locality)" required />
            </div>
            <div class="field">
                <x-hearth-label for="region" :value="__('forms.label_region')" />
                <x-hearth-select id="region" name="region" :selected="old('region', $regulatedOrganization->region)" required :options="$regions"/>
            </div>
        </fieldset>

        <button>{{ __('Save changes') }}</button>
    </form>
</x-app-wide-layout>
