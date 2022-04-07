
<form class="stack" id="create-project" action="{{ localized_route('projects.store-focus') }}" method="post" novalidate>
    @csrf

    <fieldset class="field @error('focus') field--error @enderror stack">
        <legend class="h2">{{ __('Where do you want to start your project?') }}</legend>
        <x-hearth-radio-buttons name="focus" :options="[
            'learn' => ['label' => __('Learn'), 'hint' => __('Access resources about disability and learn how to do inclusive and accessible consultation. Connect with disability and Deaf organizations to get support for planning your consultation process with the community.')],
            'engage' => ['label' => __('Engage'), 'hint' => __('Publish accessibility projects and consultation engagements on the Accessibility Exchange website to access the site’s matching services. Connect with the disability and Deaf organizations to refine your consultation plan and recruit from specific communities.')],
            'deepen-understanding' => ['label' => __('Deepen understanding'), 'hint' => __('Connect with the disability and Deaf organizations to develop a system analysis of what you have learned from the community members.')]
        ]" :checked="old('focus', session('focus')) ?? ''" />

        <x-hearth-error for="focus" />
    </fieldset>

    <p class="repel">
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
