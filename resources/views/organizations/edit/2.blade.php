<form class="stack" action="{{ localized_route('organizations.update-constituencies', $organization) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack" x-data="{livedExperiences: [{{ implode(', ', $organization->livedExperiences->pluck('id')->toArray())}}]}">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('Groups your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => ($organization->type === 'representative') ? __('represents') : __('serves and supports')]) }}
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>

            <h3>
                {{ __('What groups does your organization specifically :represent_or_serve_and_support? Please tell us your primary constituencies.', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}
            </h3>

            <p><strong><em>{{ __('Primary constituency means a group that’s specifically in your mandate to :represent_or_serve_and_support.', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</em></strong></p>

            @if($organization->isConnector())
            <p><em>{{ __('As you’ve indicated that your organization is playing the role of community connector, a Regulated Organization may request your services to assist them in connecting to these groups.') }}</em></p>
            @endif

            @if($organization->isParticipant())
                <p><em>{{ __('As you’ve indicated that your organization is playing the role of consultation participant, a Regulated Organization may ask you to represent this group’s point of view in consultations.') }}</em></p>
            @endif

            @if($organization->type !== 'civil-society')
            @include('organizations.partials.lived-experiences')
            @include('organizations.partials.disability-types')
            @endif

            <fieldset class="field @error('indigenous_identities') field--error @enderror" x-data="{hasIndigenousIdentities: '{{ old('has_indigenous_identities', $organization->extra_attributes->get('has_indigenous_identities', '')) }}'}">
                <legend>{{ __('Does your organization specifically :represent_or_serve_and_support people indigenous to what is now known as Canada? (required)', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</legend>
                <div class="field">
                    <input type="radio" name="has_indigenous_identities" id="has_indigenous_identities-1" value="1" @checked(old('has_indigenous_identities', $organization->extra_attributes->get('has_indigenous_identities', ''))) x-model="hasIndigenousIdentities" /> <label for="has_indigenous_identities-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasIndigenousIdentities == 1">
                    <x-hearth-checkboxes name="indigenous_identities" :options="$indigenousIdentities" :checked="old('indigenous_identities', $organization->indigenousIdentities->pluck('id')->toArray())" required />
                </div>
                <div class="field">
                    <input type="radio" name="has_indigenous_identities" id="has_indigenous_identities-0" value="0" @checked(!old('has_indigenous_identities', $organization->extra_attributes->get('has_indigenous_identities', ''))) x-model="hasIndigenousIdentities" /> <label for="has_indigenous_identities-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('refugees_and_immigrants') field--error @enderror">
                <legend>{{ __('Does your organization specifically :represent_or_serve_and_support refugees and/or immigrants? (required)', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</legend>
                <x-hearth-radio-buttons name="refugees_and_immigrants" :options="Spatie\LaravelOptions\Options::forArray(['1' => __('Yes'), '0' => __('No')])->toArray()" :checked="old('refugees_and_immigrants', $organization->extra_attributes->get('has_refugee_and_immigrant_constituency', ''))" />
            </fieldset>

            <fieldset class="field @error('gender_identities') field--error @enderror @error('trans_people') field--error @enderror @error('twoslgbtqia') field--error @enderror" x-data="{hasGenderAndSexualIdentities: '{{ old('has_gender_and_sexual_identities', $organization->extra_attributes->get('has_gender_and_sexual_identities', '')) }}'}">
                <legend>{{ __('Does your organization specifically :represent_or_serve_and_support people who are marginalized based on gender or sexual identity? (required)', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</legend>
                <div class="field">
                    <x-hearth-input type="radio" name="has_gender_and_sexual_identities" id="has_gender_and_sexual_identities-1" value="1" x-model="hasGenderAndSexualIdentities" /> <label for="has_gender_and_sexual_identities-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasGenderAndSexualIdentities == 1">
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-women' value='women' :checked="old('gender_and_sexual_identities.women', $organization->genderIdentities->contains($women) ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-women'>{{ $women->name }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-nb-gnc-fluid-people' value='nb-gnc-fluid-people' :checked="old('gender_and_sexual_identities.nb-gnc-fluid-people', $organization->has_nb_gnc_fluid_constituents ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-nb-gnc-fluid-people'>{{ __('Non-binary, gender non-conforming and/or gender fluid people') }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-trans-people' value='trans-people' :checked="old('gender_and_sexual_identities.trans-people', $organization->constituencies->contains($transPeople) ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-trans-people'>{{ $transPeople->name_plural }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name='gender_and_sexual_identities[]' id='gender_and_sexual_identities-2slgbtqiaplus-people' value='2slgbtqiaplus-people' :checked="old('gender_and_sexual_identities.2slgbtqiaplus-people', $organization->constituencies->contains($twoslgbtqiaplusPeople) ?? false)" /> <x-hearth-label for='gender_and_sexual_identities-2slgbtqiaplus-people'>{{ $twoslgbtqiaplusPeople->name_plural }}</x-hearth-label>
                    </div>
                </div>
                <div class="field">
                    <x-hearth-input type="radio" name="has_gender_and_sexual_identities" id="has_gender_and_sexual_identities-0" value="0" x-model="hasGenderAndSexualIdentities" /> <label for="has_gender_and_sexual_identities-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('age_brackets') field--error @enderror" x-data="{hasAgeBrackets: '{{ old('has_age_brackets', $organization->extra_attributes->get('has_age_brackets', '')) }}'}">
                <legend>{{ __('Does your organization :represent_or_serve_and_support a specific age bracket or brackets? (required)', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</legend>
                <div class="field">
                    <input type="radio" name="has_age_brackets" id="has_age_brackets-1" value="1" @checked(old('has_age_brackets', $organization->extra_attributes->get('has_age_brackets', ''))) x-model="hasAgeBrackets" /> <label for="has_age_brackets-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasAgeBrackets == 1">
                    <x-hearth-checkboxes name="age_brackets" :options="$ageBrackets" :checked="old('age_brackets', $organization->ageBrackets->pluck('id')->toArray())" required />
                </div>
                <div class="field">
                    <input type="radio" name="has_age_brackets" id="has_age_brackets-0" value="0" @checked(!old('has_age_brackets', $organization->extra_attributes->get('has_age_brackets', ''))) x-model="hasAgeBrackets" /> <label for="has_age_brackets-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('ethnoracial_identities') field--error @enderror" x-data="{hasEthnoracialIdentities: '{{ old('has_ethnoracial_identities', $organization->extra_attributes->get('has_ethnoracial_identities', '')) }}', otherEthnoracialIdentity: {{ old('other_ethnoracial_identity', !is_null($organization->other_ethnoracial_identity) && $organization->other_ethnoracial_identity !== '' ? 'true' : 'false') }}}">
                <legend>{{ __('Does your organization :represent_or_serve_and_support a specific ethnoracial identity or identities? (required)', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</legend>
                <div class="field">
                    <input type="radio" name="has_ethnoracial_identities" id="has_ethnoracial_identities-1" value="1" @checked(old('has_ethnoracial_identities', $organization->extra_attributes->get('has_ethnoracial_identities', ''))) x-model="hasEthnoracialIdentities" /> <label for="has_ethnoracial_identities-1">{{ __('Yes') }}</label>
                </div>
                <div class="field__subfield stack" x-show="hasEthnoracialIdentities == 1">
                    <x-hearth-checkboxes name="ethnoracial_identities" :options="$ethnoracialIdentities" :checked="old('ethnoracial_identities', $organization->ethnoracialIdentities->pluck('id')->toArray())" required />
                    <div class="field">
                        <x-hearth-checkbox name="other_ethnoracial" :checked="old('other_ethnoracial', !is_null($organization->other_ethnoracial_identity) && $organization->other_ethnoracial_identity !== '')" x-model="otherEthnoracialIdentity" /> <x-hearth-label for='other_ethnoracial'>{{ __('Something else') }}</x-hearth-label>
                    </div>
                    <div class="field__subfield stack">
                        <x-translatable-input name="other_ethnoracial_identity" :label="__('Ethnoracial identity')" :model="$organization" x-show="otherEthnoracialIdentity" />
                    </div>
                </div>
                <div class="field">
                    <input type="radio" name="has_ethnoracial_identities" id="has_ethnoracial_identities-0" value="0" @checked(!old('has_ethnoracial_identities', $organization->extra_attributes->get('has_ethnoracial_identities', ''))) x-model="hasEthnoracialIdentities" /> <label for="has_ethnoracial_identities-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('constituent_languages') field--error @enderror">
                <legend>{{ __('What specific languages do the people your organization :represents_or_serves_and_supports use? (optional)', ['represents_or_serves_and_supports' => ($organization->type === 'representative') ? __('represents') : __('serves and supports')]) }}</legend>
                <livewire:language-picker name="constituent_languages" :languages="$organization->constituentLanguages->pluck('code')->toArray() ?? []" :availableLanguages="$languages" />
            </fieldset>

            <fieldset class="field @error('area_types') field--error @enderror">
                <legend>{{ __('Where do the people that you :represent_or_serve_and_support come from? (required)', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }} </legend>
                <x-hearth-hint for="area_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="area_types" :options="$areaTypes" :checked="old('area_types', $organization->areaTypes->pluck('id')->toArray())" hinted="area_types-hint" required />
            </fieldset>

            @if($organization->type === 'civil-society')
                @include('organizations.partials.lived-experiences')
                @include('organizations.partials.disability-types')
            @endif

            <fieldset class="field @error('staff_lived_experience') field--error @enderror">
                <legend>{{ __('Do you have staff who have lived experience of the primary constituencies you specifically :represent_or_serve_and_support? (required)', ['represent_or_serve_and_support' => ($organization->type === 'representative') ? __('represent') : __('serve and support')]) }}</legend>
                <x-hearth-radio-buttons name="staff_lived_experience" :options="$staffHaveLivedExperience" :checked="old('staff_lived_experience', $organization->staff_lived_experience)" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
