<form class="stack" action="{{ localized_route('individuals.update-constituencies', $individual) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('individuals.partials.progress')

        <div class="stack" x-data="{livedExperiences: [{{ implode(', ', old('lived_experiences', $individual->livedExperienceConnections->pluck('id')->toArray() ?? []))}}]}">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => $individual->isConnector() ? 5 : 4]) }}<br />
                {{ __('Groups you can connect to') }}
            </h2>

            <p class="repel">
                <button class="secondary" name="previous" value="1">{{ __('Save and previous') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>

            <h3>
                {{ __('Which groups can you connect to Regulated Organizations?') }}
            </h3>

            <p><em>{{ __('As a Community Connector, a Regulated Organization may request your services to assist them in connecting to these groups.') }}</em></p>

            <fieldset class="field @error('lived_experiences') field--error @enderror">
                <legend>{{ __('Can you connect to people with disabilities, Deaf persons, and/or their supporters? (required)') }}</legend>
                <x-hearth-hint for="lived_experiences">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="lived_experiences" :options="$livedExperiences" :checked="old('lived_experiences', $individual->livedExperienceConnections->pluck('id')->toArray() ?? [])" hinted="lived_experiences-hint" required x-model.number="livedExperiences" />
            </fieldset>

            <fieldset class="field @error('disability_types') field--error @enderror" x-show="livedExperiences.includes(1)" x-data="{baseDisabilityType: '{{ old('base_disability_type', $individual->base_disability_type) }}', otherDisability: {{ old('other_disability', !is_null($individual->other_disability_type_connection) && $individual->other_disability_type_connection !== '' ? 'true' : 'false') }}}">
                <legend>{{ __('Please select people with disabilities that you can connect to (required)') }}</legend>
                <x-hearth-radio-buttons name="base_disability_type" :options="$baseDisabilityTypes" :checked="old('base_disability_type', $individual->base_disability_type)" x-model="baseDisabilityType" />
                <div class="field__subfield stack" x-show="baseDisabilityType == 'specific_disabilities'">
                    <x-hearth-checkboxes name="disability_types" :options="$disabilityTypes" :checked="old('disability_types', $individual->disabilityTypeConnections->pluck('id')->toArray())" required />
                    <div class="field">
                        <x-hearth-checkbox name="other_disability" :checked="old('other_disability', !is_null($individual->other_disability_type_connection) && $individual->other_disability_type_connection !== '')" x-model="otherDisability" /> <x-hearth-label for='other_disability'>{{ __('Something else') }}</x-hearth-label>
                    </div>

                    <div class="field__subfield stack">
                        <x-translatable-input name="other_disability_type_connection" :label="__('Disability type')" :model="$individual" x-show="otherDisability" />
                    </div>
                </div>
            </fieldset>

            <fieldset class="field @error('area_types') field--error @enderror">
                <legend>{{ __('Where do the people that you can connect to come from? (required)') }} </legend>
                <x-hearth-hint for="area_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="area_types" :options="$areaTypes" :checked="old('area_types', $individual->areaTypeConnections->pluck('id')->toArray())" hinted="area_types-hint" required />
            </fieldset>

            <fieldset class="field @error('indigenous_identities') field--error @enderror" x-data="{hasIndigenousIdentities: '{{ old('has_indigenous_identities', $individual->extra_attributes->get('has_indigenous_identities', '')) }}'}">
                <legend>{{ __('Can you connect to people Indigenous to what is now known as Canada? (required)') }}</legend>
                <div class="field">
                    <input type="radio" name="has_indigenous_identities" id="has_indigenous_identities-1" value="1" @checked(old('has_indigenous_identities', $individual->extra_attributes->get('has_indigenous_identities', ''))) x-model="hasIndigenousIdentities" /> <label for="has_indigenous_identities-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasIndigenousIdentities == 1">
                    <x-hearth-checkboxes name="indigenous_identities" :options="$indigenousIdentities" :checked="old('indigenous_identities', $individual->indigenousIdentityConnections->pluck('id')->toArray() ?? [])" required />
                </div>
                <div class="field">
                    <input type="radio" name="has_indigenous_identities" id="has_indigenous_identities-0" value="0" @checked(!old('has_indigenous_identities', $individual->extra_attributes->get('has_indigenous_identities', ''))) x-model="hasIndigenousIdentities" /> <label for="has_indigenous_identities-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('refugees_and_immigrants') field--error @enderror">
                <legend>{{ __('Can you connect to refugees and/or immigrants? (required)') }}</legend>
                <x-hearth-radio-buttons name="refugees_and_immigrants" :options="$refugeesAndImmigrantsOptions" :checked="old('refugees_and_immigrants', $individual->extra_attributes->get('has_refugee_and_immigrant_constituency', ''))" />
            </fieldset>

            <fieldset class="field @error('gender_identities') field--error @enderror @error('trans_people') field--error @enderror @error('twoslgbtqia') field--error @enderror" x-data="{hasGenderAndSexualIdentities: '{{ old('has_gender_and_sexual_identities', $individual->extra_attributes->get('has_gender_and_sexual_identities', '')) }}'}">
                <legend>{{ __('Can you connect to people who are marginalized based on gender or sexual identity? (required)') }}</legend>
                <div class="field">
                    <x-hearth-input type="radio" name="has_gender_and_sexual_identities" id="has_gender_and_sexual_identities-1" value="1" x-model="hasGenderAndSexualIdentities" /> <label for="has_gender_and_sexual_identities-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasGenderAndSexualIdentities == 1">
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-women' value='women' :checked="old('gender_and_sexual_identities.women', $individual->genderIdentityConnections->contains($women) ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-women'>{{ $women->name }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-nb-gnc-fluid-people' value='nb-gnc-fluid-people' :checked="old('gender_and_sexual_identities.nb-gnc-fluid-people', $individual->has_nb_gnc_fluid_constituents ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-nb-gnc-fluid-people'>{{ __('Non-binary, gender non-conforming and/or gender fluid people') }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-trans-people' value='trans-people' :checked="old('gender_and_sexual_identities.trans-people', $individual->constituencyConnections->contains($transPeople) ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-trans-people'>{{ $transPeople->name_plural }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-2slgbtqiaplus-people' value='2slgbtqiaplus-people' :checked="old('gender_and_sexual_identities.2slgbtqiaplus-people', $individual->constituencyConnections->contains($twoslgbtqiaplusPeople) ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-2slgbtqiaplus-people'>{{ $twoslgbtqiaplusPeople->name_plural }}</x-hearth-label>
                    </div>
                </div>
                <div class="field">
                    <x-hearth-input type="radio" name="has_gender_and_sexual_identities" id="has_gender_and_sexual_identities-0" value="0" x-model="hasGenderAndSexualIdentities" /> <label for="has_gender_and_sexual_identities-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('age_brackets') field--error @enderror" x-data="{hasAgeBrackets: '{{ old('has_age_brackets', $individual->extra_attributes->get('has_age_brackets', '')) }}'}">
                <legend>{{ __('Can you connect to a specific age bracket or brackets? (required)') }}</legend>
                <div class="field">
                    <input type="radio" name="has_age_brackets" id="has_age_brackets-1" value="1" @checked(old('has_age_brackets', $individual->extra_attributes->get('has_age_brackets', ''))) x-model="hasAgeBrackets" /> <label for="has_age_brackets-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasAgeBrackets == 1">
                    <x-hearth-checkboxes name="age_brackets" :options="$ageBrackets" :checked="old('age_brackets', $individual->ageBracketConnections->pluck('id')->toArray())" required />
                </div>
                <div class="field">
                    <input type="radio" name="has_age_brackets" id="has_age_brackets-0" value="0" @checked(!old('has_age_brackets', $individual->extra_attributes->get('has_age_brackets', ''))) x-model="hasAgeBrackets" /> <label for="has_age_brackets-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('ethnoracial_identities') field--error @enderror" x-data="{hasEthnoracialIdentities: '{{ old('has_ethnoracial_identities', $individual->extra_attributes->get('has_ethnoracial_identities', '')) }}', otherEthnoracialIdentity: {{ old('other_ethnoracial_identity', !is_null($individual->other_ethnoracial_identity_connection) && $individual->other_ethnoracial_identity_connection !== '' ? 'true' : 'false') }}}">
                <legend>{{ __('Can you connect to a specific ethnoracial identity or identities? (optional)') }}</legend>
                <div class="field">
                    <input type="radio" name="has_ethnoracial_identities" id="has_ethnoracial_identities-1" value="1" @checked(old('has_ethnoracial_identities', $individual->extra_attributes->get('has_ethnoracial_identities', ''))) x-model="hasEthnoracialIdentities" /> <label for="has_ethnoracial_identities-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasEthnoracialIdentities == 1">
                    <x-hearth-checkboxes name="ethnoracial_identities" :options="$ethnoracialIdentities" :checked="old('ethnoracial_identities', $individual->ethnoracialIdentityConnections->pluck('id')->toArray())" required />
                    <div class="field">
                        <x-hearth-checkbox name="other_ethnoracial" :checked="old('other_ethnoracial', !is_null($individual->other_ethnoracial_identity_connection) && $individual->other_ethnoracial_identity_connection !== '')" x-model="otherEthnoracialIdentity" /> <x-hearth-label for='other_ethnoracial'>{{ __('Something else') }}</x-hearth-label>
                    </div>
                    <div class="field__subfield stack">
                        <x-translatable-input name="other_ethnoracial_identity_connection" :label="__('Ethnoracial identity')" :model="$individual" x-show="otherEthnoracialIdentity" />
                    </div>
                </div>
                <div class="field">
                    <input type="radio" name="has_ethnoracial_identities" id="has_ethnoracial_identities-0" value="0" @checked(!old('has_ethnoracial_identities', $individual->extra_attributes->get('has_ethnoracial_identities', ''))) x-model="hasEthnoracialIdentities" /> <label for="has_ethnoracial_identities-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('constituent_languages') field--error @enderror">
                <legend>{{ __('What languages are used by the people you can connect to? (required)') }}</legend>
                <livewire:language-picker name="constituent_languages" :languages="$individual->languageConnections->pluck('code')->toArray() ?? []" :availableLanguages="$languages" />
            </fieldset>

            <fieldset class="field @error('connection_lived_experience') field--error @enderror">
                <legend>{{ __('Do you have lived experience of the people you can connect to? (required)') }}</legend>
                <x-hearth-radio-buttons name="connection_lived_experience" :options="$communityConnectorHasLivedExperience" :checked="old('connection_lived_experience', $individual->connection_lived_experience)" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="previous" value="1">{{ __('Save and previous') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>


</form>
