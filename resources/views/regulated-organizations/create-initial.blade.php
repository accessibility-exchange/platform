<x-app-layout>
    <x-slot name="title">
        {{ app(App\Models\RegulatedOrganization::class)->getType($type) }}
    </x-slot>
    <x-slot name="header">
        <h1>{{ __('Create new :type', ['type' => app(App\Models\RegulatedOrganization::class)->getType($type)]) }}</h1>
    </x-slot>

    @foreach(['en', 'fr'] as $locale)
        @error('name.' . $locale)
            <div class="stack">
                @php
                $regulatedOrganization = App\Models\RegulatedOrganization::where('name->' . $locale, old('name.' . $locale))->first()
                @endphp
                <x-hearth-alert type="error">
                    {{ __('There is already a :type with the name “:name” on this website. You can request to join this :type, or create one with a different name.', ['type' => app(App\Models\RegulatedOrganization::class)->getType($type), 'name' => old('name.' . $locale)]) }}
                </x-hearth-alert>
                <x-regulated-organization-card level="3" :regulatedOrganization="$regulatedOrganization" />
                <form action="{{ localized_route('regulated-organizations.join', $regulatedOrganization) }}" method="POST">
                    @csrf
                    <button class="secondary">{{ __('Request to join') }}</button>
                </form>
            </div>
            @break
        @enderror
    @endforeach

    <form class="stack" action="{{ localized_route('regulated-organizations.store') }}" method="post" novalidate>
        <fieldset class="stack">
            <legend>{{ __('Your organization’s name') }}</legend>
            <div class="field @error('name.en') field--error @enderror">
                <x-hearth-label for="name-en">{{ __('Name of organization — English') }}</x-hearth-label>
                <x-hearth-input name="name[en]" id="name-en" :value="old('name.en', '')" />
                <x-hearth-error for="name.en" />
            </div>
            <div class="field @error('name.fr') field--error @enderror">
                <x-hearth-label for="name-fr">{{ __('Name of organization — French') }}</x-hearth-label>
                <x-hearth-input name="name[fr]" id="name-fr" :value="old('name.fr', '')" />
                <x-hearth-error for="name.fr" />
            </div>

            <x-hearth-input type="hidden" name="type" :value="$type" />
        </fieldset>

        <button>{{ __('Create') }}</button>

        @csrf
    </form>
</x-app-layout>
