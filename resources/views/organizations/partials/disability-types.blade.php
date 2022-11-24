<div class="fieldset" x-data="{ baseDisabilityType: '{{ old('base_disability_type', $organization->base_disability_type) }}', otherDisability: {{ old('other_disability', !is_null($organization->other_disability_type) && $organization->other_disability_type !== '' ? 'true' : 'false') }} }">
    <fieldset class="field @error('base_disability_type') field--error @enderror"
        x-show="livedExperiences.includes({{ $deafAndDisabilityGroups->id }})">
        <legend>
            <x-required>{{ __('Please select the disability and/or Deaf groups that your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-required>
        </legend>
        <x-hearth-radio-buttons name="base_disability_type" :options="$baseDisabilityTypes" :checked="old('base_disability_type', $organization->disabilityTypes->contains($crossDisability) ? '1' : null)"
            x-model="baseDisabilityType" />
        <x-hearth-error for="base_disability_type" />
    </fieldset>
    <fieldset class="field box @error('disability_types') field--error @enderror"
        x-show="baseDisabilityType == 'specific_disabilities'">
        <legend>
            <x-required>{{ __('Please select the specific disability and/or Deaf groups that your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-required>
        </legend>
        <p class="field__hint">{{ __('Please check all that apply.') }}</p>
        <x-hearth-checkboxes name="disability_types" :options="$disabilityTypes" :checked="old('disability_types', $organization->disabilityTypes->pluck('id')->toArray())" required />
        <div class="field">
            <x-hearth-checkbox name="other_disability" :checked="old(
                'other_disability',
                !is_null($organization->other_disability_type) && $organization->other_disability_type !== '',
            )" x-model="otherDisability" />
            <x-hearth-label for='other_disability'>{{ __('Something else') }}</x-hearth-label>
        </div>

        <div class="field__subfield stack">
            <x-translatable-input name="other_disability_type" :label="__('Disability type')" :model="$organization" :shortLabel="__('disability type')"
                x-show="otherDisability" />
        </div>
        <x-hearth-error for="disability_types" />
    </fieldset>
</div>
