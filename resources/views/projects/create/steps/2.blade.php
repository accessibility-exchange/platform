
<form class="stack" id="create-project" action="{{ localized_route('projects.store-focus', $entity) }}" method="post" novalidate>
    @csrf

    <fieldset class="field @error('focus') field--error @enderror stack">
        <legend class="h2">{{ __('Where do you want to start your project?') }}</legend>
        <x-hearth-radio-buttons name="focus" :options="[
            'define' => ['label' => __('Collaboratively define the areas of consultation'), 'hint' => __('Discover the areas of your organization that are important to members of the disability and Deaf community.')],
            'design' => ['label' => __('Design the consultation'), 'hint' => __('Collaboratively design the most accessible and inclusive way for the disability and Deaf community to participate in your consultation.')],
            'run' => ['label' => __('Run the consultation'), 'hint' => __('Set up and run consultations with people from the disability and Deaf community.')]
        ]" :checked="old('focus', session('focus')) ?? ''" />

        <x-hearth-error for="focus" />
    </fieldset>

    <p class="repel">
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
