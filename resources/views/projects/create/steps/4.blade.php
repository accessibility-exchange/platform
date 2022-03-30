<h2>{{ __('About your project') }}</h2>

<form class="stack" id="create-project" action="{{ localized_route('projects.store', $entity) }}" method="post" novalidate>
    @csrf

    <fieldset class="field @error('name') field--error @enderror stack">
        <x-hearth-input type="hidden" name="entity_id" :value="$entity->id" />

        <x-hearth-input type="hidden" name="ancestor_id" :value="session()->get('ancestor')" />

        <x-translatable-input name="name" :locales="session()->get('languages')" :label="__('Project name')" :value="old('name', '')" />
    </fieldset>

    <p class="repel">
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
