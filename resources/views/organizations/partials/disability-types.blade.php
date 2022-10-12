<fieldset class="field @error('disability_types') field--error @enderror" x-show="livedExperiences.includes(1)"
    x-data="{ baseDisabilityType: '{{ old('base_disability_type', $organization->base_disability_type) }}', otherDisability: {{ old('other_disability', !is_null($organization->other_disability_type) && $organization->other_disability_type !== '' ? 'true' : 'false') }} }">
    <legend>
        {{ __('Please select people with disabilities that you specifically :represent_or_serve_and_support (required)', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}
    </legend>
    <x-hearth-radio-buttons name="base_disability_type" :options="$baseDisabilityTypes" :checked="old('base_disability_type', $organization->disabilityTypes->contains($crossDisability) ? '1' : null)"
        x-model="baseDisabilityType" />
    <div class="field__subfield stack" x-show="baseDisabilityType == 'specific_disabilities'">
        <x-hearth-checkboxes name="disability_types" :options="$disabilityTypes" :checked="old('disability_types', $organization->disabilityTypes->pluck('id')->toArray())" required />
        <div class="field">
            <x-hearth-checkbox name="other_disability" :checked="old(
                'other_disability',
                !is_null($organization->other_disability_type) && $organization->other_disability_type !== '',
            )" x-model="otherDisability" />
            <x-hearth-label for='other_disability'>{{ __('Something else') }}</x-hearth-label>
        </div>

        <div class="field__subfield stack">
            <x-translatable-input name="other_disability_type" :label="__('Disability type')" :model="$organization"
                x-show="otherDisability" />
        </div>
    </div>
</fieldset>
