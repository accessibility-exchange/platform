<fieldset class="field @error('lived_experiences') field--error @enderror">
    <legend>
        {{ __('Do you specifically :represent_or_serve_and_support people with disabilities, Deaf persons, and/or their supporters?', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) . ' ' . __('(required)') }}
    </legend>
    <x-hearth-hint for="lived_experiences">{{ __('Please check all that apply.') }}</x-hearth-hint>
    <x-hearth-checkboxes name="lived_experiences" :options="$livedExperiences" :checked="old('lived_experiences', $organization->livedExperiences->pluck('id')->toArray() ?? [])" hinted="lived_experiences-hint"
        required x-model.number="livedExperiences" />
</fieldset>
