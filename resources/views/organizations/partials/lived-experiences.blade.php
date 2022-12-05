<fieldset
    class="field @error('disability_and_deaf') field--error @enderror @error('lived_experience_constituencies') field--error @enderror">
    <legend>
        <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support people with disabilities and Deaf people, their supporters, or both?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
    </legend>
    <x-hearth-hint for="lived_experience_constituencies">{{ __('Please check all that apply.') }}</x-hearth-hint>
    <div class="field">
        <x-hearth-checkbox name="disability_and_deaf" :checked="old(
            'disability_and_deaf',
            $organization->extra_attributes->get('disability_and_deaf_constituencies', false),
        )" x-model="disabilityAndDeafConstituencies"
            hinted="lived_experience_constituencies-hint" />
        <x-hearth-label
            for="disability_and_deaf">{{ __('People with disabilities and/or Deaf people') }}</x-hearth-label>
    </div>
    <x-hearth-checkboxes name="lived_experience_constituencies" :options="$livedExperiences" :checked="old(
        'lived_experience_constituencies',
        $organization->livedExperienceConstituencies->pluck('id')->toArray() ?? [],
    )"
        hinted="lived_experience_constituencies-hint" required />
    <x-hearth-error for="disability_and_deaf" />
    <x-hearth-error for="lived_experience_constituencies" />
</fieldset>
