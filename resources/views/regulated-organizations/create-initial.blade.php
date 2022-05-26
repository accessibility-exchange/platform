<x-app-layout>
    <x-slot name="title">
        @switch($type)
            @case('government')
            {{ __('Create new government organization') }}
            @break
            @case('business')
            {{ __('Create new business') }}
            @break
            @case('public-sector')
            {{ __('Create new public sector organization') }}
            @break
            @default
            {{ __('Create new regulated organization') }}
        @endswitch
    </x-slot>
    <x-slot name="header">
        @switch($type)
            @case('government')
                <h1>{{ __('Create new government organization') }}</h1>
                @break
            @case('business')
                <h1>{{ __('Create new business') }}</h1>
                @break
            @case('public-sector')
                <h1>{{ __('Create new public sector organization') }}</h1>
            @break
            @default
                <h1>{{ __('Create new regulated organization') }}</h1>
        @endswitch
    </x-slot>

    @foreach(['en', 'fr'] as $locale)
        @error('name.' . $locale)
        <div class="stack">
            @php
            $regulatedOrganization = App\Models\RegulatedOrganization::where('name->' . $locale, old('name.' . $locale))->first()
            @endphp
            <x-hearth-alert type="error">
                {{ __('There is already a :type with the name “:name” on this website. You can request to join this :type, or create one with a different name.', ['type' => $type, 'name' => old('name.' . $locale)]) }}
            </x-hearth-alert>
            <x-regulated-organization-card level="3" :regulatedOrganization="$regulatedOrganization" />
            <form action="{{ localized_route('regulated-organizations.join', $regulatedOrganization) }}" method="POST">
                @csrf
                <button class="secondary">{{ __('Request to join') }}</button>
            </form>
        </div>
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
