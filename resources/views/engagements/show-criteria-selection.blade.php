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
        {!! Str::markdown($engagement->matchingStrategy->location_summary) !!}

        <div class="stack" x-data="{ expanded: false }">
            <button class="borderless" type="button" @click="expanded = !expanded"
                x-bind:aria-expanded="expanded.toString()">{{ __('Edit location criteria') }}
                <x-heroicon-o-chevron-down class="none transition-transform motion-reduce:transition"
                    x-bind:class="expanded && 'rotate-180'" />
            </button>
            <div x-show="expanded">
                <div class="stack" x-data="{ locationType: '{{ $engagement->matchingStrategy->location_type }}' }">
                    <fieldset class="field">
                        <legend>
                            {{ __('Are you looking for individuals in specific provinces or territories or specific cities or towns?') }}
                        </legend>
                        <x-hearth-radio-buttons name="location_type" :options="Spatie\LaravelOptions\Options::forArray([
                            'regions' => __('Specific provinces or territories'),
                            'localities' => __('Specific cities or towns'),
                        ])->toArray()" x-model="locationType" />
                        <x-hearth-error for="context" />
                    </fieldset>

                    <fieldset x-data="enhancedCheckboxes()" x-show="locationType == 'regions'">
                        <legend>{{ __('Specific provinces or territories') }}</legend>
                        <x-hearth-checkboxes name="regions" :options="$regions" :checked="old('regions', $engagement->matchingStrategy->regions ?? [])" required />
                        <div class="stack mt-8" x-cloak>
                            <button class="secondary" type="button"
                                x-on:click="selectAll()">{{ __('Select all') }}</button>
                            <button class="secondary" type="button"
                                x-on:click="selectNone()">{{ __('Select none') }}</button>
                        </div>
                    </fieldset>

                    <fieldset x-show="locationType == 'localities'">
                        <legend>{{ __('Specific cities or towns') }}</legend>
                        <livewire:locations :locations="old('locations', $engagement->matchingStrategy->locations ?? [])" />
                    </fieldset>
                </div>
            </div>
        </div>

        <h3>{{ __('Disability or Deaf group') }}</h3>
        {!! Str::markdown($engagement->matchingStrategy->disability_and_deaf_group_summary) !!}

        <div class="stack" x-data="{ expanded: false }">
            <button class="borderless" type="button" @click="expanded = !expanded"
                x-bind:aria-expanded="expanded.toString()">{{ __('Edit disability or Deaf group criteria') }}
                <x-heroicon-o-chevron-down class="none transition-transform motion-reduce:transition"
                    x-bind:class="expanded && 'rotate-180'" />
            </button>
            <div x-show="expanded">
                <div class="stack" x-data="{ crossDisability: {{ $engagement->matchingStrategy->hasDisabilityType($crossDisability) }} }">
                    <fieldset class="field">
                        <legend>
                            {{ __('Is there a specific disability or Deaf group you are interested in engaging?') }}
                        </legend>
                        <x-hearth-radio-buttons name="cross_disability" :options="Spatie\LaravelOptions\Options::forArray([
                            '1' => __(
                                'No, I’m interested in a cross-disability group (includes disability, Deaf, and supporters)',
                            ),
                            '0' => __('Yes, I’m interested in a specific disability or Deaf group or groups'),
                        ])->toArray()" x-model="crossDisability" />
                        <x-hearth-error for="context" />
                    </fieldset>
                </div>
            </div>
        </div>

        <h3>{{ __('Other identities') }}</h3>
        {!! Str::markdown($engagement->matchingStrategy->other_identities_summary) !!}

        <div class="stack" x-data="{ expanded: false }">
            <button class="borderless" type="button" @click="expanded = !expanded"
                x-bind:aria-expanded="expanded.toString()">{{ __('Edit other identities criteria') }}
                <x-heroicon-o-chevron-down class="none transition-transform motion-reduce:transition"
                    x-bind:class="expanded && 'rotate-180'" />
            </button>
            <div x-show="expanded">
            </div>
        </div>

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
                <x-hearth-input class="w-24" name="ideal_participants" type="number" :value="old('ideal_participants', $engagement->ideal_participants)" min="1"
                    hinted required />
                <x-hearth-error for="ideal_participants" />
            </div>

            <div class="field @error('minimum_participants') field--error @enderror">
                <x-hearth-label for="minimum_participants">{{ __('Minimum number of participants') }}</x-hearth-label>
                <x-hearth-hint for="minimum_participants">
                    {{ __('The least number of participants you can have to go forward with your engagement.') }}
                </x-hearth-hint>
                <x-hearth-input class="w-24" name="minimum_participants" type="number" :value="old('minimum_participants', $engagement->minimum_participants)"
                    min="1" hinted required />
                <x-hearth-error for="minimum_participants" />
            </div>
        </fieldset>

        <button>{{ __('Next') }}</button>
    </form>
</x-app-layout>
