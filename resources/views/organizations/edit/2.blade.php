<form class="stack" action="{{ localized_route('organizations.update-constituencies', $organization) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('organizations.partials.progress')

        <div class="stack" x-data="{livedExperiences: [{{ implode(', ', $organization->livedExperienceConstituencies->pluck('id')->toArray())}}]}">
            <h2>
                {{ __('Step :current of :total', ['current' => request()->get('step') ?? 1, 'total' => 4]) }}<br />
                @if($organization->type === 'representative')
                    {{ __('Groups your organization represents') }}
                @else
                    {{ __('Groups your organization serves or supports') }}
                @endif
            </h2>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
            <h3>
                @if($organization->type === 'representative')
                    {{ __('What groups does your organization represent? Please tell us your primary constituencies.') }}
                @else
                    {{ __('What groups does your organization serve and support? Please tell us your primary constituencies.') }}
                @endif
            </h3>

            <fieldset class="field @error('lived_experiences') field--error @enderror">
                <legend>
                @if($organization->type === 'representative')
                    {{ __('Do you represent people with disabilities, Deaf persons, and/or their supporters? (required)') }}
                @else
                    {{ __('Do you support or serve people with disabilities, Deaf persons, and/or their supporters? (required)') }}
                @endif
                </legend>
                <x-hearth-hint for="lived_experiences">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="lived_experiences" :options="$livedExperiences" :checked="old('lived_experiences', $organization->livedExperienceConstituencies->pluck('id')->toArray())" hinted="lived_experiences-hint" required x-model="livedExperiences" />
            </fieldset>

            <fieldset class="field @error('disability_types') field--error @enderror" x-show="livedExperiences.includes(1) || livedExperiences.includes('1')" x-data="{baseDisabilityType: '{{ old('base_disability_type', $organization->base_disability_type) }}', disabilityTypes: [{{ implode(', ', $organization->disabilityConstituencies->pluck('id')->toArray())}}]}">
                <legend>
                    @if($organization->type === 'representative')
                        {{ __('Please select people with disabilities that you represent (required)') }}
                    @else
                        {{ __('Please select people with disabilities that you serve and support (required)') }}
                    @endif
                </legend>
                <x-hearth-hint for="disability_types">{{ __('Please select your primary constituency.') }}</x-hearth-hint>
                <x-hearth-radio-buttons name="base_disability_type" :options="['cross_disability' => __('Cross-disability'), 'specific_disabilities' => __('Specific disability or disabilities')]" :checked="old('base_disability_type', $organization->base_disability_type ?? '')" x-model="baseDisabilityType" />
                <div class="stack" x-show="baseDisabilityType == 'specific_disabilities'" >
                <x-hearth-checkboxes name="disability_types" :options="$disabilityTypes" :checked="old('disability_types', $organization->disabilityConstituencies->pluck('id')->toArray())" hinted="disability_types-hint" required x-model="disabilityTypes" />
                </div>
                <x-translatable-input name="other_disability_type" :label="__('Other disability')" x-show="disabilityTypes.includes(16) || disabilityTypes.includes('16')" />
            </fieldset>

            <fieldset class="field @error('indigenous_identities') field--error @enderror" x-data="{baseIndigenousIdentity: '{{ old('base_indigenous_identity', $organization->base_indigenous_identity) }}'}">
                <legend>{{ __('Does your organization represent people Indigenous to what is now known as Canada? (required)') }}</legend>
                <x-hearth-hint for="indigenous_identities">{{ __('Please select your primary constituency.') }}</x-hearth-hint>
                <div class="field">
                    <input type="radio" name="base_indigenous_identity" id="base_indigenous_identity-1" value="1" @checked(old('base_indigenous_identity', $organization->base_indigenous_identity)) x-model="baseIndigenousIdentity" /> <label for="base_indigenous_identity-1">{{ __('Yes') }}</label>
                </div>
                <div class="stack" x-show="baseIndigenousIdentity == 1">
                    <x-hearth-checkboxes name="indigenous_identities" :options="$indigenousIdentities" :checked="old('indigenous_identities', $organization->indigenousConstituencies->pluck('id')->toArray())" hinted="indigenous_identities-hint" required />
                </div>
                <div class="field">
                    <input type="radio" name="base_indigenous_identity" id="base_indigenous_identity-0" value="0" @checked(!old('base_indigenous_identity', $organization->base_indigenous_identity)) x-model="baseIndigenousIdentity" /> <label for="base_indigenous_identity-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('refugees_and_immigrants') field--error @enderror">
                <legend>{{ __('Does your organization represent refugees and/or immigrants? (required)') }}</legend>
                <x-hearth-radio-buttons name="refugees_and_immigrants" :options="['1' => __('Yes'), '0' => __('No')]" :checked="old('refugees_and_immigrants', $organization->refugees_and_immigrants)" />
            </fieldset>

            <fieldset class="field @error('gender_and_sexual_identities') field--error @enderror" x-data="{baseGenderAndSexualIdentity: '{{ old('base_gender_and_sexual_identity', $organization->base_gender_and_sexual_identity) }}'}">
                <legend>{{ __('Does your organization represent people who are marginalized based on gender or sexual identity? (required)') }}</legend>
                <x-hearth-hint for="gender_and_sexual_identities">{{ __('Please select your primary constituency.') }}</x-hearth-hint>
                <div class="field">
                    <x-hearth-input type="radio" name="base_gender_and_sexual_identity" id="base_gender_and_sexual_identity-1" value="1" x-model="baseGenderAndSexualIdentity" /> <label for="base_gender_and_sexual_identity-1">{{ __('Yes') }}</label>
                </div>
                <div class="stack" x-show="baseGenderAndSexualIdentity == 1">
                    <div class="field">
                        <x-hearth-checkbox name="gender_identities[]" :id="'gender-identities-' . $women->id" :value="$women->id" :checked="$organization->genderIdentityConstituencies->contains($women)" /> <x-hearth-label :for="'gender-identities-' . $women->id">{{ $women->name }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name="gender_identities[]" :id="'gender-identities-' . $nonBinary->id" :value="$nonBinary->id" :checked="$organization->genderIdentityConstituencies->contains($nonBinary)" /> <x-hearth-label :for="'gender-identities-' . $nonBinary->id">{{ $nonBinary->name }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name="gender_identities[]" :id="'gender-identities-' . $genderNonConforming->id" :value="$genderNonConforming->id" :checked="$organization->genderIdentityConstituencies->contains($genderNonConforming)" /> <x-hearth-label :for="'gender-identities-' . $genderNonConforming->id">{{ $genderNonConforming->name }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name="gender_identities[]" :id="'gender-identities-' . $genderFluid->id" :value="$genderFluid->id" :checked="$organization->genderIdentityConstituencies->contains($genderFluid)" /> <x-hearth-label :for="'gender-identities-' . $genderFluid->id">{{ $genderFluid->name }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name="trans_people" value="1" :checked="old('trans_people', $organization->trans_people ?? false)" /> <x-hearth-label for='trans_people'>{{ __('Trans people') }}</x-hearth-label>
                    </div>
                    <div class="field">
                        <x-hearth-checkbox name="twoslgbtqia" value="1" :checked="old('twoslgbtqia', $organization->twoslgbtqia ?? false)" /> <x-hearth-label for='twoslgbtqia'>{{ __('People who identify with one or more of the 2SLGBTQIA+ identities') }}</x-hearth-label>
                    </div>
                </div>
                <div class="field">
                    <x-hearth-input type="radio" name="base_gender_and_sexual_identity" id="base_gender_and_sexual_identity-0" value="0" x-model="baseGenderAndSexualIdentity" /> <label for="base_gender_and_sexual_identity-0">{{ __('No') }}</label>
                </div>
            </fieldset>

            <fieldset class="field @error('employment_barriers') field--error @enderror">
                <legend>{{ __('Does your organization represent people who face employment barriers? (required)') }}</legend>
                <x-hearth-radio-buttons name="employment_barriers" :options="['1' => __('Yes'), '0' => __('No')]" :checked="old('employment_barriers', $organization->employment_barriers)" />
            </fieldset>

            <p class="repel">
                <button class="secondary" name="save_and_previous" value="1">{{ __('Save and back') }}</button>
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
