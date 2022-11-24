<form class="stack" action="{{ localized_route('organizations.update-constituencies', $organization) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack" x-data="{ livedExperiences: [{{ implode(', ', old('lived_experiences', $organization->livedExperiences->pluck('id')->toArray() ?? [])) }}] }">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('Groups your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}
            </h2>
            <hr class="divider--thick">
            <h3>
                {{ __('What groups does your organization specifically :represent_or_serve_and_support? Please tell us your primary constituencies.', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}
            </h3>

            <p>
                <strong>{{ __('Primary constituency means a group that’s specifically in your mandate to :represent_or_serve_and_support.', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</strong>
            </p>

            @if ($organization->isConnector())
                <p>{{ __('As you’ve indicated that your organization is playing the role of Community Connector, a Regulated Organization may request your services to assist them in connecting to these groups.') }}
                </p>
            @endif

            @if ($organization->isParticipant())
                <p>{{ __('As you’ve indicated that your organization is playing the role of Consultation Participant, a Regulated Organization may ask you to represent this group’s point of view in consultations.') }}
                </p>
            @endif

            @if ($organization->type !== 'civil-society')
                @include('organizations.partials.lived-experiences')
                @include('organizations.partials.disability-types')
            @endif

            <div class="fieldset" x-data="{ hasIndigenousIdentities: '{{ old('has_indigenous_identities', $organization->extra_attributes->get('has_indigenous_identities', '')) }}' }">
                <fieldset class="field @error('has_indigenous_identities') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support people who are First Nations, Inuit, or Métis?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <div class="field">
                        <input id="has_indigenous_identities-1" name="has_indigenous_identities" type="radio"
                            value="1" @checked(old('has_indigenous_identities', $organization->extra_attributes->get('has_indigenous_identities', ''))) x-model="hasIndigenousIdentities" />
                        <label for="has_indigenous_identities-1">{{ __('Yes') }}</label>
                    </div>
                    <div class="field">
                        <input id="has_indigenous_identities-0" name="has_indigenous_identities" type="radio"
                            value="0" @checked(!old('has_indigenous_identities', $organization->extra_attributes->get('has_indigenous_identities', ''))) x-model="hasIndigenousIdentities" />
                        <label for="has_indigenous_identities-0">{{ __('No') }}</label>
                    </div>
                    <x-hearth-error for="has_indigenous_identities" />
                </fieldset>
                <fieldset class="field box @error('indigenous_identities') field--error @enderror"
                    x-show="hasIndigenousIdentities == 1">
                    <legend>
                        <x-required>{{ __('Which Indigenous groups does your organization specifically :represent_or_serve_and_support?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="indigenous_identities" :options="$indigenousIdentities" :checked="old(
                        'indigenous_identities',
                        $organization->indigenousIdentities->pluck('id')->toArray(),
                    )" required />
                    <x-hearth-error for="indigenous_identities" />
                </fieldset>
            </div>

            <fieldset class="field @error('refugees_and_immigrants') field--error @enderror">
                <legend>
                    <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support refugees and/or immigrants?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                </legend>
                <x-hearth-radio-buttons name="refugees_and_immigrants" :options="Spatie\LaravelOptions\Options::forArray(['1' => __('Yes'), '0' => __('No')])->toArray()" :checked="old(
                    'refugees_and_immigrants',
                    $organization->extra_attributes->get('has_refugee_and_immigrant_constituency', ''),
                )" />
                <x-hearth-error for="refugees_and_immigrants" />
            </fieldset>

            <div class="fieldset" x-data="{ hasGenderAndSexualIdentities: '{{ old('has_gender_and_sexual_identities', $organization->extra_attributes->get('has_gender_and_sexual_identities', '')) }}' }">
                <fieldset class="field @error('has_gender_and_sexual_identities') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support people who are marginalized based on gender or sexual identity?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <div class="field">
                        <x-hearth-input id="has_gender_and_sexual_identities-1" name="has_gender_and_sexual_identities"
                            type="radio" value="1" x-model="hasGenderAndSexualIdentities" />
                        <label for="has_gender_and_sexual_identities-1">{{ __('Yes') }}</label>
                    </div>

                    <div class="field">
                        <x-hearth-input id="has_gender_and_sexual_identities-0" name="has_gender_and_sexual_identities"
                            type="radio" value="0" x-model="hasGenderAndSexualIdentities" />
                        <label for="has_gender_and_sexual_identities-0">{{ __('No') }}</label>
                    </div>
                    <x-hearth-error for="has_gender_and_sexual_identities" />
                </fieldset>
                <fieldset class="field box @error('gender_and_sexual_identities') field--error @enderror"
                    x-show="hasGenderAndSexualIdentities == 1">
                    <legend>
                        <x-required>{{ __('Which groups marginalized based on gender or sexual identity does your organization specifically :represent_or_serve_and_support?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <div class="field">
                        <x-hearth-checkbox id='gender_and_sexual_identities-women' name='gender_and_sexual_identities[]'
                            value='women' :checked="in_array('women', old('gender_and_sexual_identities', [])) ||
                                $organization->genderIdentities->contains($women) ?? false" />
                        <x-hearth-label for='gender_and_sexual_identities-women'>{{ $women->name }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox id='gender_and_sexual_identities-nb-gnc-fluid-people'
                            name='gender_and_sexual_identities[]' value='nb-gnc-fluid-people' :checked="in_array('nb-gnc-fluid-people', old('gender_and_sexual_identities', [])) ||
                                $organization->has_nb_gnc_fluid_constituents ?? false" />
                        <x-hearth-label for='gender_and_sexual_identities-nb-gnc-fluid-people'>
                            {{ __('Non-binary, gender non-conforming and/or gender fluid people') }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox id='gender_and_sexual_identities-trans-people'
                            name='gender_and_sexual_identities[]' value='trans-people' :checked="in_array('trans-people', old('gender_and_sexual_identities', [])) ||
                                $organization->identities->contains($transPeople) ?? false" />
                        <x-hearth-label for='gender_and_sexual_identities-trans-people'>{{ $transPeople->name }}
                        </x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox id='gender_and_sexual_identities-2slgbtqiaplus-people'
                            name='gender_and_sexual_identities[]' value='2slgbtqiaplus-people' :checked="in_array('2slgbtqiaplus-people', old('gender_and_sexual_identities', [])) ||
                                $organization->identities->contains($twoslgbtqiaplusPeople) ?? false" />
                        <x-hearth-label for='gender_and_sexual_identities-2slgbtqiaplus-people'>
                            {{ $twoslgbtqiaplusPeople->name }}</x-hearth-label>
                    </div>
                    <x-hearth-error for="gender_and_sexual_identities" />
                </fieldset>
            </div>

            <div class="fieldset" x-data="{ hasAgeBrackets: '{{ old('has_age_brackets', $organization->extra_attributes->get('has_age_brackets', '')) }}' }">
                <fieldset class="field @error('has_age_brackets') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization :represent_or_serve_and_support a specific age bracket or brackets?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <div class="field">
                        <input id="has_age_brackets-1" name="has_age_brackets" type="radio" value="1"
                            @checked(old('has_age_brackets', $organization->extra_attributes->get('has_age_brackets', ''))) x-model="hasAgeBrackets" />
                        <label for="has_age_brackets-1">{{ __('Yes') }}</label>
                    </div>

                    <div class="field">
                        <input id="has_age_brackets-0" name="has_age_brackets" type="radio" value="0"
                            @checked(!old('has_age_brackets', $organization->extra_attributes->get('has_age_brackets', ''))) x-model="hasAgeBrackets" />
                        <label for="has_age_brackets-0">{{ __('No') }}</label>
                    </div>
                    <x-hearth-error for="has_age_brackets" />
                </fieldset>
                <fieldset class="field box @error('age_brackets') field--error @enderror" x-show="hasAgeBrackets == 1">
                    <legend>
                        <x-required>{{ __('Which age groups does your organization specifically :represent_or_serve_and_support?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="age_brackets" :options="$ageBrackets" :checked="old('age_brackets', $organization->ageBrackets->pluck('id')->toArray())" required />
                    <x-hearth-error for="age_brackets" />
                </fieldset>
            </div>

            <div class="fieldset" x-data="{
                hasEthnoracialIdentities: '{{ old('has_ethnoracial_identities', $organization->extra_attributes->get('has_ethnoracial_identities', '')) }}',
                otherEthnoracialIdentity: {{ old('other_ethnoracial', !is_null($organization->other_ethnoracial_identity) && $organization->other_ethnoracial_identity !== '') ? 'true' : 'false' }}
            }">
                <fieldset class="field @error('has_ethnoracial_identities') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization :represent_or_serve_and_support a specific ethnoracial identity or identities?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <div class="field">
                        <input id="has_ethnoracial_identities-1" name="has_ethnoracial_identities" type="radio"
                            value="1" @checked(old('has_ethnoracial_identities', $organization->extra_attributes->get('has_ethnoracial_identities', ''))) x-model="hasEthnoracialIdentities" />
                        <label for="has_ethnoracial_identities-1">{{ __('Yes') }}</label>
                    </div>
                    <div class="field">
                        <input id="has_ethnoracial_identities-0" name="has_ethnoracial_identities" type="radio"
                            value="0" @checked(!old('has_ethnoracial_identities', $organization->extra_attributes->get('has_ethnoracial_identities', ''))) x-model="hasEthnoracialIdentities" />
                        <label for="has_ethnoracial_identities-0">{{ __('No') }}</label>
                    </div>
                    <x-hearth-error for="has_ethnoracial_identities" />
                </fieldset>
                <fieldset class="field box @error('ethnoracial_identities') field--error @enderror"
                    x-show="hasEthnoracialIdentities == 1">
                    <legend>
                        <x-required>{{ __('Which ethnoracial identity or identities are the people your organization specifically :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="ethnoracial_identities" :options="$ethnoracialIdentities" :checked="old(
                        'ethnoracial_identities',
                        $organization->ethnoracialIdentities->pluck('id')->toArray(),
                    )"
                        required />
                    <div class="field">
                        <x-hearth-checkbox name="other_ethnoracial" :checked="old(
                            'other_ethnoracial',
                            !is_null($organization->other_ethnoracial_identity) &&
                                $organization->other_ethnoracial_identity !== '',
                        )"
                            x-model="otherEthnoracialIdentity" />
                        <x-hearth-label for='other_ethnoracial'>{{ __('Something else') }}</x-hearth-label>
                    </div>
                    <div class="field__subfield stack">
                        <x-translatable-input name="other_ethnoracial_identity" :label="__('Ethnoracial identity')" :model="$organization"
                            :shortLabel="__('ethnoracial identity')" x-show="otherEthnoracialIdentity" />
                    </div>
                    <x-hearth-error for="ethnoracial_identities" />
                </fieldset>
            </div>

            <fieldset class="field @error('constituent_languages') field--error @enderror">
                <legend>
                    <x-optional>{{ __('What specific languages do the people your organization :represents_or_serves_and_supports use?', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-optional>
                </legend>
                <livewire:language-picker name="constituent_languages" :languages="$organization->constituentLanguages->pluck('code')->toArray() ?? []" :availableLanguages="$languages" />
                <x-hearth-error for="constituent_languages" />
            </fieldset>

            <fieldset class="field @error('area_types') field--error @enderror">
                <legend>
                    <x-required>{{ __('Where do the people that you :represent_or_serve_and_support come from?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                </legend>
                <x-hearth-hint for="area_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="area_types" :options="$areaTypes" :checked="old('area_types', $organization->areaTypes->pluck('id')->toArray())" hinted="area_types-hint"
                    required />
                <x-hearth-error for="area_types" />
            </fieldset>

            @if ($organization->type === 'civil-society')
                @include('organizations.partials.lived-experiences')
                @include('organizations.partials.disability-types')
            @endif

            <fieldset class="field @error('staff_lived_experience') field--error @enderror">
                <legend>
                    <x-required>{{ __('Do you have staff who have lived experience of the primary constituencies you specifically :represent_or_serve_and_support?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                </legend>
                <x-hearth-radio-buttons name="staff_lived_experience" :options="$staffHaveLivedExperience" :checked="old('staff_lived_experience', $organization->staff_lived_experience)" />
                <x-hearth-error for="staff_lived_experience" />
            </fieldset>
            <hr class="divider--thick">
            <p class="flex flex-wrap gap-7">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button class="secondary" name="save" value="1">{{ __('Save') }}</button>
                <button name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
