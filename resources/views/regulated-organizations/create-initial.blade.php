<x-app-layout>
    <x-slot name="title">
        {{ __('Create new :type', ['type' => __('regulated-organization.types.' . $type)]) }}
    </x-slot>
    <x-slot name="header">
        @if ($type === 'government' || $type === 'public-sector')
            <h1> {{ __('Tell us your organization’s name') }} </h1>
        @elseif ($type === 'business')
            <h1> {{ __('Tell us your business name') }} </h1>
        @else
            <h1>{{ __('Create new :type', ['type' => __('regulated-organization.types.' . $type)]) }}</h1>
        @endif
    </x-slot>

    <form class="stack" action="{{ localized_route('regulated-organizations.store') }}" method="post" novalidate>
        <fieldset class="stack">
            <legend>{{ __('Your organization’s name') }}</legend>
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
                __('A :type with this name already exists.', ['type' => __('regulated-organization.types.' . $type)]))
                <div class="stack">
                    @php
                        $regulatedOrganization = App\Models\RegulatedOrganization::where('name->' . $locale, old('name.' . $locale))->first();
                    @endphp
                    <x-live-region>
                        <x-hearth-alert type="error">
                            {{ __('There is already a :type with the name “:name” registered on this platform. If this is the organization you work for, please contact your colleagues to get an invitation to the organization. If this isn’t the organization you work for, please use a different name.', ['type' => __('regulated-organization.types.' . $type), 'name' => old('name.' . $locale)]) }}
                        </x-hearth-alert>
                    </x-live-region>
                    <x-card.regulated-organization level="3" :model="$regulatedOrganization" />
                </div>
            @break

        @endif
    @enderror
@endforeach
</x-app-layout>
