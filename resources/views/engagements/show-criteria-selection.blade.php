<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        </ol>
        <p class="h4">{{ __('Create engagement') }}</p>
        <h1 class="mt-0">
            {{ __('Confirm your participant selection criteria') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2>{{ __('Participant details') }}</h2>

    <p>{{ __('Please tell us more about the individuals you’d like participating in your engagement.') }}</p>

    <form class="stack" action="{{ localized_route('engagements.store-criteria', $engagement) }}" method="post"
        novalidate>
        @csrf
        @method('put')

        <h3>{{ __('Location') }}</h3>
        <p>{{ $engagement->matchingStrategy->location_summary }}</p>

        <fieldset x-data="enhancedCheckboxes()">
            <legend>{{ __('Specific provinces or territories') }}</legend>
            <x-hearth-checkboxes name="regions" :options="$regions" :checked="old('regions', $engagement->matchingStrategy->regions ?? [])" required />
            <div class="stack" x-cloak>
                <button class="secondary" type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                <button class="secondary" type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
            </div>
        </fieldset>

        <fieldset>
            <legend>{{ __('Specific cities or towns') }}</legend>
            <livewire:locations :locations="[]" />
        </fieldset>

        <h3>{{ __('Disability or Deaf group') }}</h3>
        <p>{{ $engagement->matchingStrategy->disability_and_deaf_group_summary }}</p>

        <h3>{{ __('Other identities') }}</h3>
        <p>{{ $engagement->matchingStrategy->other_identities_summary }}</p>

        <hr class="mt-16 mb-12 border-x-0 border-t-3 border-b-0 border-solid border-t-blue-7" />

        <fieldset class="field stack">
            <legend>
                <h2>{{ __('Number of participants') }}</h2>
            </legend>

            <x-hearth-hint for="participants">
                {{ __('How many participants would you like to engage? Please enter a number, for example 20.') }}
            </x-hearth-hint>

            <div class="field @error('ideal_participants') field--error @enderror">
                <x-hearth-label for="ideal_participants">{{ __('Ideal number of participants') }}</x-hearth-label>
                <x-hearth-hint for="ideal_participants">
                    {{ __('This is the ideal number of participants you would like to have for this engagement.') }}
                </x-hearth-hint>
                <x-hearth-input class="w-24" name="ideal_participants" type="number" :value="old('ideal_participants')" min="1"
                    hinted required />
                <x-hearth-error for="ideal_participants" />
            </div>

            <div class="field @error('minimum_participants') field--error @enderror">
                <x-hearth-label for="minimum_participants">{{ __('Minimum number of participants') }}</x-hearth-label>
                <x-hearth-hint for="minimum_participants">
                    {{ __('The least number of participants you can have to go forward with your engagement.') }}
                </x-hearth-hint>
                <x-hearth-input class="w-24" name="minimum_participants" type="number" :value="old('minimum_participants')"
                    min="1" hinted required />
                <x-hearth-error for="minimum_participants" />
            </div>
        </fieldset>

        <button>{{ __('Next') }}</button>
    </form>
</x-app-layout>
