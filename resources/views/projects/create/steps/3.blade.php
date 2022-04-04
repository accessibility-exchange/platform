
<form class="stack" id="create-project" action="{{ localized_route('projects.store-languages', $entity) }}" method="post" novalidate>
    @csrf

    <fieldset class="stack">
        <legend class="h2">{{ __('Project languages') }}</legend>
        <x-hearth-hint for="languages">{{ __('What language are you able to provide the details of your project in?') }}</x-hearth-hint>
        <livewire:language-picker name="languages" :languages="['en', 'fr', 'ase', 'fcs']" :availableLanguages="$languages" />
    </fieldset>

    <p class="repel">
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
            <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
