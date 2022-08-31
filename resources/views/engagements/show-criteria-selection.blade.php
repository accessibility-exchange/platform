<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        </ol>
        <p class="h4">{{ $surtitle }}</p>
        <h1 class="mt-0">
            {{ $heading }}
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
            <div x-cloak x-show="expanded">
                <div class="stack" x-data="{ locationType: '{{ $engagement->matchingStrategy->location_type ?? 'regions' }}' }">
                    <fieldset class="field @error('location_type') field--error @enderror">
                        <legend>
                            {{ __('Are you looking for individuals in specific provinces or territories or specific cities or towns?') }}
                        </legend>
                        <x-hearth-radio-buttons name="location_type" :options="$locationTypeOptions" x-model="locationType" />
                        <x-hearth-error for="location_type" />
                    </fieldset>

                    <fieldset class="field @error('regions') field--error @enderror" x-data="enhancedCheckboxes()" x-cloak
                        x-show="locationType == 'regions'">
                        <legend>{{ __('Specific provinces or territories') }}</legend>
                        <x-hearth-checkboxes name="regions" :options="$regions" :checked="old(
                            'regions',
                            $engagement->matchingStrategy->regions ??
                                array_column(\App\Enums\ProvinceOrTerritory::cases(), 'value'),
                        )" required />
                        <div class="stack mt-8" x-cloak>
                            <button class="secondary" type="button"
                                x-on:click="selectAll()">{{ __('Select all') }}</button>
                            <button class="secondary" type="button"
                                x-on:click="selectNone()">{{ __('Select none') }}</button>
                        </div>
                        <x-hearth-error for="regions" />
                    </fieldset>

                    <fieldset class="field @error('locations') field--error @enderror" x-cloak
                        x-show="locationType == 'localities'">
                        <legend>{{ __('Specific cities or towns') }}</legend>
                        <livewire:locations :locations="old('locations', $engagement->matchingStrategy->locations ?? [])" />
                        <x-hearth-error for="locations" />
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
            <div x-cloak x-show="expanded">
                <div class="stack" x-data="{ crossDisability: {{ $engagement->matchingStrategy->hasDisabilityTypes() ? (int) $engagement->matchingStrategy->hasDisabilityType($crossDisability) : 1 }} }">
                    <fieldset class="field @error('cross_disability') field--error @enderror">
                        <legend>
                            {{ __('Is there a specific disability or Deaf group you are interested in engaging?') }}
                        </legend>
                        <x-hearth-radio-buttons name="cross_disability" :options="Spatie\LaravelOptions\Options::forArray([
                            '1' => __(
                                'No, I’m interested in a cross-disability group (includes disability, Deaf, and supporters)',
                            ),
                            '0' => __('Yes, I’m interested in a specific disability or Deaf group or groups'),
                        ])->toArray()"
                            x-model.number="crossDisability" />
                        <x-hearth-error for="cross_disability" />
                    </fieldset>
                    <fieldset class="field @error('disability_types') field--error @enderror" x-cloak
                        x-show="crossDisability == 0">
                        <legend>
                            {{ __('What specific disability and Deaf group or groups are you interested in engaging? (required)') }}
                        </legend>
                        <x-hearth-checkboxes name="disability_types" :options="$disabilityTypes" :checked="old(
                            'disability_types',
                            $engagement->matchingStrategy
                                ->criteria()
                                ->where('criteriable_type', 'App\Models\DisabilityType')
                                ->pluck('criteriable_id')
                                ->toArray(),
                        )" />
                    </fieldset>
                </div>
            </div>
        </div>

        <h3>{{ __('Other identities') }}</h3>
        {!! Str::markdown($engagement->matchingStrategy->other_identities_summary) !!}

        <div class="stack" x-data="{ expanded: true }">
            <button class="borderless" type="button" @click="expanded = !expanded"
                x-bind:aria-expanded="expanded.toString()">{{ __('Edit other identities criteria') }}
                <x-heroicon-o-chevron-down class="none transition-transform motion-reduce:transition"
                    x-bind:class="expanded && 'rotate-180'" />
            </button>
            <div x-cloak x-show="expanded">
                <div class="stack" x-data="{
                    intersectional: {{ $engagement->matchingStrategy->extra_attributes->get('other_identity_type', false) ? 0 : 1 }},
                    otherIdentityType: '{{ $engagement->matchingStrategy->extra_attributes->get('other_identity_type', '') }}'
                }">
                    <fieldset class="field @error('intersectional') field--error @enderror">
                        <legend>
                            {{ __('Is there a group with a specific experience of identity you are interested in engaging?') }}
                        </legend>
                        <x-hearth-radio-buttons name="intersectional" :options="$intersectionalOptions"
                            x-model.number="intersectional" />
                        <x-hearth-error for="intersectional" />
                    </fieldset>
                    <div class="field @error('other_identity_type') field--error @enderror" x-cloak
                        x-show="intersectional == 0">
                        <x-hearth-label for="other_identity_type">{{ __('Types of experiences or identities') }}
                        </x-hearth-label>
                        <x-hearth-select name="other_identity_type" :options="$otherIdentityOptions" :selected="old(
                            'other_identity_type',
                            $engagement->matchingStrategy->extra_attributes->get('other_identity_type', ''),
                        )"
                            x-model="otherIdentityType" />
                        <x-hearth-error for="other_identity_type" />
                    </div>
                    <fieldset x-cloak x-show="otherIdentityType == 'age-bracket'">
                        <legend>{{ __('What age group are you interested in engaging?') }}</legend>
                        <x-hearth-checkboxes name="age_brackets" :options="$ageBrackets" :checked="old(
                            'age_brackets',
                            $engagement->matchingStrategy
                                ->criteria()
                                ->where('criteriable_type', 'App\Models\AgeBracket')
                                ->pluck('criteriable_id')
                                ->toArray(),
                        )" required />
                    </fieldset>
                    <fieldset x-cloak x-show="otherIdentityType == 'gender-and-sexual-identity'">
                        <legend>
                            {{ __('What group that has been marginalized based on gender or sexual identity are you interested in engaging?') }}
                        </legend>
                        <div class="field">
                            <x-hearth-checkbox id='gender_and_sexual_identities-women'
                                name='gender_and_sexual_identities[]' value='women' :checked="old(
                                    'gender_and_sexual_identities.women',
                                    $engagement->matchingStrategy->hasCriterion(get_class($women), $women),
                                )" />
                            <x-hearth-label for='gender_and_sexual_identities-women'>{{ $women->name }}
                            </x-hearth-label>
                        </div>
                        <div class="field">
                            <x-hearth-checkbox id='gender_and_sexual_identities-nb-gnc-fluid-people'
                                name='gender_and_sexual_identities[]' value='nb-gnc-fluid-people' :checked="old(
                                    'gender_and_sexual_identities.nb-gnc-fluid-people',
                                    $engagement->matchingStrategy->hasCriteria('App\Models\GenderIdentity', [
                                        $nb,
                                        $gnc,
                                        $fluid,
                                    ]),
                                )" />
                            <x-hearth-label for='gender_and_sexual_identities-nb-gnc-fluid-people'>
                                {{ __('Non-binary, gender non-conforming and/or gender fluid people') }}
                            </x-hearth-label>
                        </div>
                        <div class="field">
                            <x-hearth-checkbox id='gender_and_sexual_identities-trans-people'
                                name='gender_and_sexual_identities[]' value='trans-people' :checked="old(
                                    'gender_and_sexual_identities.trans-people',
                                    $engagement->matchingStrategy->hasCriterion(get_class($transPeople), $transPeople),
                                )" />
                            <x-hearth-label for='gender_and_sexual_identities-trans-people'>
                                {{ $transPeople->name_plural }}
                            </x-hearth-label>
                        </div>
                        <div class="field">
                            <x-hearth-checkbox id='gender_and_sexual_identities-2slgbtqiaplus-people'
                                name='gender_and_sexual_identities[]' value='2slgbtqiaplus-people' :checked="old(
                                    'gender_and_sexual_identities.2slgbtqiaplus-people',
                                    $engagement->matchingStrategy->hasCriterion(
                                        get_class($twoslgbtqiaplusPeople),
                                        $twoslgbtqiaplusPeople,
                                    ),
                                )" />
                            <x-hearth-label for='gender_and_sexual_identities-2slgbtqiaplus-people'>
                                {{ $twoslgbtqiaplusPeople->name_plural }}</x-hearth-label>
                        </div>
                    </fieldset>
                    <fieldset x-cloak x-show="otherIdentityType == 'indigenous-identity'">
                        <legend>
                            {{ __('What Indigenous group are you interested in engaging?') }}
                        </legend>
                        <x-hearth-checkboxes name="indigenous_identities" :options="$indigenousIdentities" :checked="old(
                            'indigenous_identities',
                            $engagement->matchingStrategy
                                ->criteria()
                                ->where('criteriable_type', 'App\Models\IndigenousIdentity')
                                ->pluck('criteriable_id')
                                ->toArray(),
                        )" />
                    </fieldset>
                    <fieldset x-cloak x-show="otherIdentityType == 'ethnoracial-identity'">
                        <legend>{{ __('What ethno-racial group are you interested in engaging?') }}</legend>
                        <x-hearth-checkboxes name="ethnoracial_identities" :options="$ethnoracialIdentities" :checked="old(
                            'ethnoracial_identities',
                            $engagement->matchingStrategy
                                ->criteria()
                                ->where('criteriable_type', 'App\Models\EthnoracialIdentity')
                                ->pluck('criteriable_id')
                                ->toArray(),
                        )" />
                    </fieldset>
                    <fieldset x-cloak x-show="otherIdentityType == 'first-language'">
                        <legend>{{ __('What first languages are used by the people you’re interested in engaging?') }}
                        </legend>
                        <livewire:language-picker name="first_languages" :languages="old(
                            'languages',
                            $engagement->matchingStrategy
                                ->criteria()
                                ->where('criteriable_type', 'App\Models\Language')
                                ->with('criteriable')
                                ->get()
                                ->pluck('criteriable')
                                ->pluck('code')
                                ->toArray(),
                        )" :availableLanguages="$languages" />
                    </fieldset>
                    <fieldset class="field @error('area_types') field--error @enderror" x-cloak
                        x-show="otherIdentityType == 'area-type'">
                        <legend>{{ __('Where do the people you’re interested in engaging live?') }}</legend>
                        <x-hearth-hint for="area_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                        <x-hearth-checkboxes name="area_types" :options="$areaTypes" :checked="old(
                            'area_types',
                            $engagement->matchingStrategy
                                ->criteria()
                                ->where('criteriable_type', 'App\Models\AreaType')
                                ->pluck('criteriable_id')
                                ->toArray(),
                        )"
                            hinted="area_types-hint" />
                    </fieldset>
                </div>
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
                <x-hearth-input class="w-24" name="ideal_participants" type="number" :value="old('ideal_participants', $engagement->ideal_participants)"
                    min="1" hinted required />
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
