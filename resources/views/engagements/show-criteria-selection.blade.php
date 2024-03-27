<x-app-layout page-width="medium">
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
            </li>
        </ol>
        <p class="h4">{{ $surtitle }}</p>
        <h1 class="mt-0">
            {{ $heading }}
        </h1>
        <x-interpretation name="{{ $headingInterpretation }}" />
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    @if ($engagement->who === 'individuals')
        <h2>{{ __('Participant details') }}</h2>
        <x-interpretation name="{{ __('Participant details') }}" />

        <p>{{ __('Please tell us more about the individuals you’d like participating in your engagement.') }}</p>
    @else
        <h2>{{ __('Organization details') }}</h2>
        <x-interpretation name="{{ __('Organization details') }}" />

        <p>{{ __('Please tell us more about the organization you’d like participating in your engagement.') }}</p>
    @endif

    <form class="stack" action="{{ localized_route('engagements.update-criteria', $engagement) }}" method="post"
        novalidate>
        @csrf
        @method('put')

        <h3>{{ __('Location') }}</h3>
        <x-interpretation name="{{ __('Location') }}" />

        <div x-data="{ editing: @if ($errors->isNotEmpty()) true @else false @endif }">
            <div class="stack" x-show="!editing">
                <x-array-list-view :data="$engagement->matchingStrategy->location_summary" />

                <button class="secondary" type="button" @click="editing = !editing">
                    @svg('heroicon-o-pencil', 'mr-1') {{ __('Edit') }} <span class="sr-only">{{ __('Location') }}</span>
                </button>
            </div>

            <div class="box box--alt space-y-6 px-6 py-8" x-cloak x-show="editing">
                <div class="stack" x-data="{ locationType: @js(old('location_type', $engagement->matchingStrategy->location_type ?? App\Enums\LocationType::Regions->value)) }">
                    <fieldset class="field @error('location_type') field--error @enderror">
                        <legend>
                            {{ __('Are you looking for individuals in specific provinces or territories or specific cities or towns?') }}
                        </legend>
                        <x-interpretation
                            name="{{ __('Are you looking for individuals in specific provinces or territories or specific cities or towns?') }}" />
                        <x-hearth-radio-buttons name="location_type" :options="$locationTypeOptions" x-model="locationType" />
                        <x-hearth-error for="location_type" />
                    </fieldset>

                    <fieldset class="field @error('regions') field--error @enderror" x-data="enhancedCheckboxes()" x-cloak
                        x-show="locationType == '{{ App\Enums\LocationType::Regions->value }}'">
                        <legend>{{ __('Specific provinces or territories') }}</legend>
                        <x-interpretation name="{{ __('Specific provinces or territories') }}" />
                        <x-hearth-checkboxes name="regions" :options="$regions" :checked="old('regions', $engagement->matchingStrategy->regions ?? [])" required />
                        <div class="stack mt-8" x-cloak>
                            <button class="secondary" type="button"
                                x-on:click="selectAll()">{{ __('Select all') }}</button>
                            <button class="secondary" type="button"
                                x-on:click="selectNone()">{{ __('Select none') }}</button>
                        </div>
                        <x-hearth-error for="regions" />
                    </fieldset>

                    <fieldset class="field @error('locations') field--error @enderror" x-cloak
                        x-show="locationType == '{{ App\Enums\LocationType::Localities->value }}'">
                        <legend>{{ __('Specific cities or towns') }}</legend>
                        <x-interpretation name="{{ __('Specific cities or towns') }}" />
                        <livewire:locations :locations="old('locations', $engagement->matchingStrategy->locations ?? [])" />
                        <x-hearth-error for="locations" />
                    </fieldset>
                </div>
            </div>
        </div>

        <h3>{{ __('Disability or Deaf group') }}</h3>
        <x-interpretation name="{{ __('Disability or Deaf group') }}" />

        <div x-data="{ editing: @if ($errors->isNotEmpty()) true @else false @endif }">
            <div class="stack" x-show="!editing">
                <x-array-list-view :data="$engagement->matchingStrategy->disability_and_deaf_group_summary" />
                <button class="secondary" type="button" @click="editing = !editing">
                    @svg('heroicon-o-pencil', 'mr-1') {{ __('Edit') }} <span
                        class="sr-only">{{ __('Disability or Deaf group') }}</span>
                </button>
            </div>

            <div class="box box--alt space-y-6 px-6 py-8" x-cloak x-show="editing">
                <div class="stack" x-data="{ crossDisability: @js(old('cross_disability_and_deaf', $engagement->matchingStrategy->cross_disability_and_deaf ?? 1)) }">
                    <fieldset class="field @error('cross_disability_and_deaf') field--error @enderror">
                        <legend>
                            {{ __('Is there a specific disability or Deaf group you are interested in engaging?') }}
                        </legend>
                        <x-interpretation
                            name="{{ __('Is there a specific disability or Deaf group you are interested in engaging?') }}" />
                        <x-hearth-radio-buttons name="cross_disability_and_deaf" :options="Spatie\LaravelOptions\Options::forArray([
                            '1' => __(
                                'No, I’m interested in a cross-disability group (includes disability, Deaf, and supporters)',
                            ),
                            '0' => __('Yes, I’m interested in a specific disability or Deaf group or groups'),
                        ])->toArray()"
                            x-model.number="crossDisability" />
                        <x-hearth-error for="cross_disability_and_deaf" />
                    </fieldset>
                    <fieldset class="field @error('disability_types') field--error @enderror" x-cloak
                        x-show="crossDisability == 0">
                        <legend>
                            {{ __('What specific disability and Deaf group or groups are you interested in engaging?') . ' ' . __('(required)') }}
                        </legend>
                        <x-interpretation
                            name="{{ __('What specific disability and Deaf group or groups are you interested in engaging?') }}" />
                        <x-hearth-checkboxes name="disability_types" :options="$disabilityTypes" :checked="old(
                            'disability_types',
                            $engagement->matchingStrategy
                                ->identities()
                                ->whereJsonContains('clusters', App\Enums\IdentityCluster::DisabilityAndDeaf)
                                ->pluck('identity_id')
                                ->toArray(),
                        )" />
                    </fieldset>
                </div>
            </div>
        </div>

        <h3>{{ __('Other identities') }}</h3>
        <x-interpretation name="{{ __('Other identities') }}" />

        <div x-data="{ editing: @if ($errors->isNotEmpty()) true @else false @endif }">
            <div class="stack" x-show="!editing">
                <x-array-list-view :data="$engagement->matchingStrategy->other_identities_summary" />
                <button class="secondary" type="button" @click="editing = !editing">
                    @svg('heroicon-o-pencil', 'mr-1') {{ __('Edit') }} <span class="sr-only">{{ __('Other identities') }}</span>
                </button>
            </div>

            <div class="box box--alt space-y-6 px-6 py-8" x-cloak x-show="editing">
                <div class="stack" x-data="{
                    intersectional: @js(old('intersectional', $engagement->matchingStrategy->extra_attributes->get('intersectional', 1))),
                    otherIdentityType: @js(old('other_identity_type', $engagement->matchingStrategy->extra_attributes->get('other_identity_type', '')))
                }">
                    <fieldset class="field @error('intersectional') field--error @enderror">
                        <legend>
                            {{ __('Is there a group with a specific experience of identity you are interested in engaging?') }}
                        </legend>
                        <x-interpretation
                            name="{{ __('Is there a group with a specific experience of identity you are interested in engaging?') }}" />
                        <x-hearth-radio-buttons name="intersectional" :options="$intersectionalOptions"
                            x-model.number="intersectional" />
                        <x-hearth-error for="intersectional" />
                    </fieldset>
                    <div class="field @error('other_identity_type') field--error @enderror" x-cloak
                        x-show="intersectional == 0">
                        <x-hearth-label for="other_identity_type">{{ __('Types of experiences or identities') }}
                        </x-hearth-label>
                        <x-interpretation name="{{ __('Types of experiences or identities') }}" />
                        <x-hearth-hint
                            for="other_identity_type">{{ __('To protect the privacy of participants, you are only able to pick one of the following criteria.') }}</x-hearth-hint>
                        <x-hearth-select name="other_identity_type" :options="$otherIdentityOptions" :selected="old(
                            'other_identity_type',
                            $engagement->matchingStrategy->extra_attributes->get('other_identity_type', ''),
                        )"
                            x-model="otherIdentityType" hinted="other_identity_type-hint" />
                        <x-hearth-error for="other_identity_type" />
                    </div>
                    <div x-show="intersectional == 0" x-cloak>
                        <fieldset class="field @error('age_brackets') field--error @enderror" x-cloak
                            x-show="otherIdentityType == '{{ App\Enums\IdentityType::AgeBracket->value }}'">
                            <legend>{{ __('What age group are you interested in engaging?') }}</legend>
                            <x-interpretation name="{{ __('What age group are you interested in engaging?') }}" />
                            <x-hearth-checkboxes name="age_brackets" :options="$ageBrackets" :checked="old(
                                'age_brackets',
                                $engagement->matchingStrategy->ageBrackets->pluck('id')->toArray(),
                            )" required />
                            <x-hearth-error for="age_brackets" />
                        </fieldset>
                        <fieldset class="field @error('gender_and_sexual_identities') field--error @enderror" x-cloak
                            x-show="otherIdentityType == '{{ App\Enums\IdentityType::GenderAndSexualIdentity->value }}'">
                            <legend>
                                {{ __('What group that has been marginalized based on gender or sexual identity are you interested in engaging?') }}
                            </legend>
                            <x-interpretation
                                name="{{ __('What group that has been marginalized based on gender or sexual identity are you interested in engaging?') }}" />
                            <div class="field">
                                <x-hearth-checkbox name="nb_gnc_fluid_identity" :checked="old(
                                    'nb_gnc_fluid_identity',
                                    $engagement->matchingStrategy->hasIdentities($genderDiverseIdentities) ?? false,
                                )" />
                                <x-hearth-label
                                    for='nb_gnc_fluid_identity'>{{ __('Non-binary, gender non-conforming and/or gender fluid people') }}</x-hearth-label>
                            </div>
                            <x-hearth-checkboxes name="gender_and_sexual_identities" :options="$genderAndSexualityIdentities"
                                :checked="old(
                                    'gender_and_sexual_identities',
                                    $engagement->matchingStrategy->genderAndSexualityIdentities->pluck('id')->toArray(),
                                )" />
                            <x-hearth-error for="gender_and_sexual_identities" />
                        </fieldset>
                        <fieldset class="field @error('indigenous_identities') field--error @enderror" x-cloak
                            x-show="otherIdentityType == '{{ App\Enums\IdentityType::IndigenousIdentity->value }}'">
                            <legend>
                                {{ __('What Indigenous group are you interested in engaging?') }}
                            </legend>
                            <x-interpretation
                                name="{{ __('What Indigenous group are you interested in engaging?') }}" />
                            <x-hearth-checkboxes name="indigenous_identities" :options="$indigenousIdentities" :checked="old(
                                'indigenous_identities',
                                $engagement->matchingStrategy->indigenousIdentities->pluck('id')->toArray(),
                            )" />
                            <x-hearth-error for="indigenous_identities" />
                        </fieldset>
                        <fieldset class="field @error('ethnoracial_identities') field--error @enderror" x-cloak
                            x-show="otherIdentityType == '{{ App\Enums\IdentityType::EthnoracialIdentity->value }}'">
                            <legend>{{ __('What ethno-racial group are you interested in engaging?') }}</legend>
                            <x-interpretation
                                name="{{ __('What ethno-racial group are you interested in engaging?') }}" />
                            <x-hearth-checkboxes name="ethnoracial_identities" :options="$ethnoracialIdentities" :checked="old(
                                'ethnoracial_identities',
                                $engagement->matchingStrategy->ethnoracialIdentities->pluck('id')->toArray(),
                            )" />
                            <x-hearth-error for="ethnoracial_identities" />
                        </fieldset>
                        <fieldset class="field @error('first_languages') field--error @enderror" x-cloak
                            x-show="otherIdentityType == '{{ App\Enums\IdentityType::FirstLanguage->value }}'">
                            <legend>
                                {{ __('What first languages are used by the people you’re interested in engaging?') }}
                            </legend>
                            <x-interpretation
                                name="{{ __('What first languages are used by the people you’re interested in engaging?') }}" />
                            <livewire:language-picker name="first_languages" :languages="old(
                                'languages',
                                $engagement->matchingStrategy->languages->pluck('code')->toArray(),
                            )" :availableLanguages="$languages" />
                            <x-hearth-error for="first_languages" />
                        </fieldset>
                        <fieldset class="field @error('area_types') field--error @enderror" x-cloak
                            x-show="otherIdentityType == '{{ App\Enums\IdentityType::AreaType->value }}'">
                            <legend>{{ __('Where do the people you’re interested in engaging live?') }}</legend>
                            <x-interpretation
                                name="{{ __('Where do the people you’re interested in engaging live?') }}" />
                            <x-hearth-hint for="area_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                            <x-hearth-checkboxes name="area_types" :options="$areaTypes" :checked="old(
                                'area_types',
                                $engagement->matchingStrategy->areaTypes->pluck('id')->toArray(),
                            )"
                                hinted="area_types-hint" />
                            <x-hearth-error for="area_types" />
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

        @if ($engagement->who === 'individuals')
            <hr class="divider--thick" />
            <fieldset class="field stack">
                <legend>
                    <h2>{{ __('Number of participants') }}</h2>
                </legend>
                <x-interpretation name="{{ __('Number of participants') }}" />

                <x-hearth-hint for="participants">
                    {{ __('How many participants would you like to engage? Please enter a number, for example 20.') }}
                </x-hearth-hint>

                <div class="field @error('ideal_participants') field--error @enderror">
                    <x-hearth-label for="ideal_participants">
                        {{ __('Ideal number of participants') . ' ' . __('(required)') }}</x-hearth-label>
                    <x-hearth-hint for="ideal_participants">
                        {{ __('This is the ideal number of participants you would like to have for this engagement. The least you can select is 10 participants.') }}
                    </x-hearth-hint>
                    <x-hearth-input class="w-24" name="ideal_participants" type="number" :value="old('ideal_participants', $engagement->ideal_participants)"
                        min="10" hinted required />
                    <x-hearth-error for="ideal_participants" />
                </div>

                <div class="field @error('minimum_participants') field--error @enderror">
                    <x-hearth-label for="minimum_participants">
                        {{ __('Minimum number of participants') . ' ' . __('(required)') }}
                    </x-hearth-label>
                    <x-hearth-hint for="minimum_participants">
                        {{ __('The least number of participants you can have to go forward with your engagement. The least you can select is 10 participants.') }}
                    </x-hearth-hint>
                    <x-hearth-input class="w-24" name="minimum_participants" type="number" :value="old('minimum_participants', $engagement->minimum_participants)"
                        min="10" hinted required />
                    <x-hearth-error for="minimum_participants" />
                </div>
            </fieldset>
        @endif

        <hr class="divider--thick" />

        <button>{{ __('Save selection criteria') }}</button>
    </form>
</x-app-layout>
