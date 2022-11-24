<fieldset class="field @error('lived_experiences') field--error @enderror">
    <legend>
        <x-required>{{ __('Does your organization specifically :represent_or_serve_and_support people with disabilities and Deaf people, their supporters, or both?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}</x-required>
    </legend>
    <x-hearth-hint for="lived_experiences">{{ __('Please check all that apply.') }}</x-hearth-hint>
    <x-hearth-checkboxes name="lived_experiences" :options="$livedExperiences" :checked="old('lived_experiences', $organization->livedExperiences->pluck('id')->toArray() ?? [])" hinted="lived_experiences-hint"
        required x-model.number="livedExperiences" />
    <x-hearth-error for="lived_experiences" />
</fieldset>
