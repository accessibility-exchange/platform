<x-app-layout>
    <x-slot name="title">
        {{ __('Create new :type', ['type' => __('organization.types.' . $type . '.name')]) }}
    </x-slot>
    <x-slot name="header">
        <h1>{{ __('Create new :type', ['type' => __('organization.types.' . $type . '.name')]) }}</h1>
    </x-slot>

    <form class="stack" action="{{ localized_route('organizations.store') }}" method="post" novalidate>
        <fieldset class="stack">
            <legend>{{ __('Your organization’s name') }}</legend>
            <p class="field__hint">{{ __('Your organization’s name in either English or French is required.') }}</p>
            <div class="field @error('name.en') field--error @enderror">
                <x-hearth-label for="name-en">{{ __('Name of organization — English') }}</x-hearth-label>
                <x-hearth-input id="name-en" name="name[en]" :value="old('name.en', '')" />
                <x-hearth-error for="name.en" />
            </div>
            <div class="field @error('name.fr') field--error @enderror">
                <x-hearth-label for="name-fr">{{ __('Name of organization — French') }}</x-hearth-label>
                <x-hearth-input id="name-fr" name="name[fr]" :value="old('name.fr', '')" />
                <x-hearth-error for="name.fr" />
            </div>

            <x-hearth-input name="type" type="hidden" :value="$type" />
        </fieldset>

        <button>{{ __('Create Organization') }}</button>

        @csrf
    </form>

    @foreach (['en', 'fr'] as $locale)
        @error('name.' . $locale)
            @if ($message ===
                __('A :type with this name already exists.', ['type' => __('organization.types.' . $type . '.name')]))
                <div class="stack">
                    @php
                        $organization = App\Models\Organization::where('name->' . $locale, old('name.' . $locale))->first();
                    @endphp
                    <x-live-region>
                        <x-hearth-alert type="error">
                            {{ __('There is already a :type with the name “:name” registered on this platform. If this is the organization you work for, please contact your colleagues to get an invitation to the organization. If this isn’t the organization you work for, please use a different name.', ['type' => __('organization.types.' . $type . '.name'), 'name' => old('name.' . $locale)]) }}
                        </x-hearth-alert>
                    </x-live-region>
                    <x-organization-card level="3" :model="$organization" />
                </div>
            @endif
        @break
    @enderror
@endforeach
</x-app-layout>
