<form class="stack" action="{{ localized_route('organizations.update-constituencies', $organization) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack" x-data="{ disabilityAndDeafConstituencies: @js(old('disability_and_deaf', $organization->extra_attributes->get('disability_and_deaf_constituencies', false))) }">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                {{ __('Communities your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}
            </h2>
            <hr class="divider--thick">
            <p class="h3">
                {{ __('Please tell us which community or communities your organization :represents_or_serves_and_supports.', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}
            </p>

            {{ safe_markdown('If your organization is offering services as a **Community Connector**, regulated organizations may ask you to assist them in connecting to your primary constituencies. If your organization is offering services as a **Consultation Participant**, regulated organizations may ask you to represent this group’s point of view in consultations.') }}

            <p>{{ __('Please note that selecting some of these options may open up new follow-up questions below them. ') }}
            </p>

            @if ($organization->type !== 'civil-society')
                @include('organizations.partials.lived-experiences')
                @include('organizations.partials.disability-types')
            @endif

            <div class="fieldset" x-data="{ hasIndigenousConstituencies: @js(old('has_indigenous_constituencies', $organization->hasConstituencies('indigenousConstituencies'))) }">
                <fieldset class="field @error('has_indigenous_constituencies') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support people who are First Nations, Inuit, or Métis?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <div class="field">
                        <input id="has_indigenous_constituencies-1" name="has_indigenous_constituencies" type="radio"
                            value="1" @checked(old('has_indigenous_constituencies', (int) $organization->hasConstituencies('indigenousConstituencies') ?? '')) x-model="hasIndigenousConstituencies" />
                        <label for="has_indigenous_constituencies-1">{{ __('Yes') }}</label>
                    </div>
                    <div class="field">
                        <input id="has_indigenous_constituencies-0" name="has_indigenous_constituencies" type="radio"
                            value="0" @checked(!old('has_indigenous_constituencies', (int) $organization->hasConstituencies('indigenousConstituencies') ?? '')) x-model="hasIndigenousConstituencies" />
                        <label for="has_indigenous_constituencies-0">{{ __('No') }}</label>
                    </div>
                    <x-hearth-error for="has_indigenous_constituencies" />
                </fieldset>
                <fieldset class="field box @error('indigenous_constituencies') field--error @enderror" x-cloak
                    x-show="hasIndigenousConstituencies == 1">
                    <legend>
                        <x-required>{{ __('Which Indigenous groups does your organization specifically :represent_or_serve_and_support?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="indigenous_constituencies" :options="$indigenousIdentities" :checked="old(
                        'indigenous_constituencies',
                        $organization->indigenousConstituencies->pluck('id')->toArray(),
                    )"
                        required />
                    <x-hearth-error for="indigenous_constituencies" />
                </fieldset>
            </div>

            <fieldset class="field @error('refugees_and_immigrants') field--error @enderror">
                <legend>
                    <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support refugees and/or immigrants?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                </legend>
                <x-hearth-radio-buttons name="refugees_and_immigrants" :options="$yesNoOptions" :checked="old('refugees_and_immigrants', $organization->hasConstituencies('statusConstituencies')) ??
                    ''" />
                <x-hearth-error for="refugees_and_immigrants" />
            </fieldset>

            <div class="fieldset" x-data="{ hasGenderAndSexualityConstituencies: @js(old('has_gender_and_sexuality_constituencies', $organization->hasConstituencies('genderAndSexualityConstituencies'))) }">
                <fieldset class="field @error('has_gender_and_sexuality_constituencies') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support people who are marginalized based on gender or sexual identity?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <x-hearth-radio-buttons name="has_gender_and_sexuality_constituencies" :options="$yesNoOptions"
                        :checked="old(
                            'has_gender_and_sexuality_constituencies',
                            $organization->hasConstituencies('genderAndSexualityConstituencies'),
                        ) ?? ''" x-model="hasGenderAndSexualityConstituencies" />
                    <x-hearth-error for="has_gender_and_sexuality_constituencies" />
                </fieldset>
                <fieldset
                    class="field box @error('gender_and_sexuality_constituencies') field--error @enderror @error('nb_gnc_fluid_identity') field--error @enderror"
                    x-cloak x-show="hasGenderAndSexualityConstituencies == 1">
                    <legend>
                        <x-required>{{ __('Which groups marginalized based on gender or sexual identity does your organization specifically :represent_or_serve_and_support?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <div class="field">
                        <x-hearth-checkbox name="nb_gnc_fluid_identity" :checked="old(
                            'nb_gnc_fluid_identity',
                            $organization->hasConstituencies('genderDiverseConstituencies') ?? false,
                        )" />
                        <x-hearth-label
                            for='nb_gnc_fluid_identity'>{{ __('Non-binary, gender non-conforming and/or gender fluid people') }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkboxes name="gender_and_sexuality_constituencies" :options="$genderAndSexualIdentities"
                            :checked="old(
                                'gender_and_sexuality_constituencies',
                                $organization->genderAndSexualityConstituencies->pluck('id')->toArray(),
                            )" required />
                    </div>
                    <x-hearth-error for="gender_and_sexuality_constituencies" />
                </fieldset>
            </div>

            <div class="fieldset" x-data="{ hasAgeBracketConstituencies: @js(old('has_age_bracket_constituencies', $organization->hasConstituencies('ageBracketConstituencies'))) }">
                <fieldset class="field @error('has_age_bracket_constituencies') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization :represent_or_serve_and_support a specific age bracket or brackets?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <x-hearth-radio-buttons name="has_age_bracket_constituencies" :options="$yesNoOptions" :checked="old(
                        'has_age_bracket_constituencies',
                        $organization->hasConstituencies('ageBracketConstituencies'),
                    ) ?? ''"
                        x-model="hasAgeBracketConstituencies" />
                    <x-hearth-error for="has_age_bracket_constituencies" />
                </fieldset>
                <fieldset class="field box @error('age_bracket_constituencies') field--error @enderror" x-cloak
                    x-show="hasAgeBracketConstituencies == 1">
                    <legend>
                        <x-required>{{ __('Which age groups does your organization specifically :represent_or_serve_and_support?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="age_bracket_constituencies" :options="$ageBrackets" :checked="old(
                        'age_bracket_constituencies',
                        $organization->ageBracketConstituencies->pluck('id')->toArray(),
                    )"
                        required />
                    <x-hearth-error for="age_bracket_constituencies" />
                </fieldset>
            </div>

            <div class="fieldset" x-data="{
                hasEthnoracialIdentityConstituencies: @js(old('has_ethnoracial_identity_constituencies', $organization->hasConstituencies('ethnoracialIdentityConstituencies') || !blank($organization->other_ethnoracial_identity_constituency) ?: null)),
                otherEthnoracialIdentity: @js(old('has_other_ethnoracial_identity_constituency', !blank($organization->other_ethnoracial_identity_constituency)))
            }">
                <fieldset class="field @error('has_ethnoracial_identity_constituencies') field--error @enderror">
                    <legend>
                        <x-required>{{ __('Does your organization :represent_or_serve_and_support a specific ethnoracial identity or identities?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                    </legend>
                    <x-hearth-radio-buttons name="has_ethnoracial_identity_constituencies" :options="$yesNoOptions"
                        checked="@js('has_ethnoracial_identity_constituencies', $organization->hasConstituencies('ethnoracialIdentityConstituencies') || !blank($organization->other_ethnoracial_identity_constituency) ?: '')" x-model="hasEthnoracialIdentityConstituencies" />
                    <x-hearth-error for="has_ethnoracial_identity_constituencies" />
                </fieldset>
                <fieldset class="field box @error('ethnoracial_identity_constituencies') field--error @enderror" x-cloak
                    x-show="hasEthnoracialIdentityConstituencies == 1">
                    <legend>
                        <x-required>{{ __('Which ethnoracial identity or identities are the people your organization specifically :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-required>
                    </legend>
                    <p class="field__hint">{{ __('Please check all that apply.') }}</p>
                    <x-hearth-checkboxes name="ethnoracial_identity_constituencies" :options="$ethnoracialIdentities"
                        :checked="old(
                            'ethnoracial_identity_constituencies',
                            $organization->ethnoracialIdentityConstituencies->pluck('id')->toArray(),
                        )" required />
                    <div class="field">
                        <x-hearth-checkbox name="has_other_ethnoracial_identity_constituency" :checked="old(
                            'has_other_ethnoracial_identity_constituency',
                            !blank($organization->other_ethnoracial_identity_constituency),
                        )"
                            x-model="otherEthnoracialIdentity" />
                        <x-hearth-label
                            for='has_other_ethnoracial_identity_constituency'>{{ __('Something else') }}</x-hearth-label>
                    </div>
                    <div class="field__subfield stack">
                        <x-translatable-input name="other_ethnoracial_identity_constituency" :label="__('Ethnoracial identity')"
                            :model="$organization" :shortLabel="__('ethnoracial identity')" x-cloak x-show="otherEthnoracialIdentity" />
                    </div>
                    <x-hearth-error for="ethnoracial_identity_constituencies" />
                </fieldset>
            </div>

            <fieldset class="field @error('constituent_languages') field--error @enderror">
                <legend>
                    <x-optional>{{ __('What specific languages do the people your organization :represents_or_serves_and_supports use?', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-optional>
                </legend>
                <livewire:language-picker name="constituent_languages" :languages="$organization->languageConstituencies->pluck('code')->toArray() ?? []" :availableLanguages="$languages" />
                <x-hearth-error for="constituent_languages" />
            </fieldset>

            <fieldset class="field @error('area_type_constituencies') field--error @enderror">
                <legend>
                    <x-required>{{ __('Where do the people that you :represent_or_serve_and_support come from?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
                </legend>
                <x-hearth-hint for="area_type_constituencies">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="area_type_constituencies" :options="$areaTypes" :checked="old(
                    'area_type_constituencies',
                    $organization->areaTypeConstituencies->pluck('id')->toArray(),
                )"
                    hinted="area_type_constituencies-hint" required />
                <x-hearth-error for="area_type_constituencies" />
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
