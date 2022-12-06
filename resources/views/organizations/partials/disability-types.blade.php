<div class="stack fieldset" x-show="disabilityAndDeafConstituencies" x-cloak x-data="{
    baseDisabilityType: '{{ old('base_disability_type', $organization->base_disability_type ?? '') }}',
    otherDisability: {{ old('has_other_disability_constituency', !is_null($organization->other_disability_constituency) && $organization->other_disability_constituency !== '' ? 'true' : 'false') }}
}">
    <fieldset class="field @error('base_disability_type') field--error @enderror">
        <legend>
            <x-required>{{ __('Please select the disability and/or Deaf groups that your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-required>
        </legend>
        <x-hearth-radio-buttons name="base_disability_type" :options="$baseDisabilityTypes" :checked="old('base_disability_type', $organization->base_disability_type ?? '')"
            x-model="baseDisabilityType" />
        <x-hearth-error for="base_disability_type" />
    </fieldset>
    <fieldset
        class="field box @error('disability_and_deaf_constituencies') field--error @enderror @error('has_other_disability_constituency') field--error @enderror @error('other_disability_constituency') field--error @enderror"
        x-show="baseDisabilityType == 'specific_disabilities'">
        <legend>
            <x-required>{{ __('Please select the specific disability and/or Deaf groups that your organization :represents_or_serves_and_supports', ['represents_or_serves_and_supports' => $organization->type === 'representative' ? __('represents') : __('serves and supports')]) }}</x-required>
        </legend>
        <p class="field__hint">{{ __('Please check all that apply.') }}</p>
        <x-hearth-checkboxes name="disability_and_deaf_constituencies" :options="$disabilityTypes" :checked="old(
            'disability_and_deaf_constituencies',
            $organization->disabilityAndDeafConstituencies->pluck('id')->toArray(),
        )" required />
        <div class="field">
            <x-hearth-checkbox name="has_other_disability_constituency" :checked="old(
                'has_other_disability_constituency',
                !is_null($organization->other_disability_constituency) &&
                    $organization->other_disability_constituency !== '',
            )" x-model="otherDisability" />
            <x-hearth-label for='has_other_disability_constituency'>{{ __('Something else') }}</x-hearth-label>
        </div>

        <div class="field__subfield stack">
            <x-translatable-input name="other_disability_constituency" :label="__('Disability type')" :model="$organization"
                :shortLabel="__('disability type')" x-show="otherDisability" />
        </div>
        <x-hearth-error for="disability_and_deaf_constituencies" />
        <x-hearth-error for="has_other_disability_constituency" />
        <x-hearth-error for="other_disability_constituency" />
    </fieldset>
</div>
