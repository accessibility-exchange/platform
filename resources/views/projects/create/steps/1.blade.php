<form class="stack" id="create-project" action="{{ localized_route('projects.store-context', $regulatedOrganization) }}" method="post" novalidate x-data="{context: '{{ old('context', session('context')) ?? '' }}'}">
    @csrf

    <fieldset class="field @error('context') field--error @enderror stack">
        <legend class="h2">{{ __('Is your project a new project, or a follow-up to previous project?') }}</legend>
        <x-hearth-radio-buttons name="context" :options="['new' => __('A new project'), 'follow-up' => __('A follow-up to a previous project')]" :checked="old('context', session('context')) ?? ''" x-model="context" />
        <div class="field @error('ancestor') field--error @enderror stack" x-show="context == 'follow-up'">
            <x-hearth-label for="ancestor" :value="__('Please select the previous project')" />
            <x-hearth-select name="ancestor" :options="array_merge(['' => ''], $regulatedOrganization->projects->pluck('name', 'id')->toArray())" :selected="old('ancestor_id', session('ancestor_id'))" />
            <x-hearth-error for="ancestor" />
        </div>
        <x-hearth-error for="context" />
    </fieldset>

    <p class="repel">
        <x-hearth-button>{{ __('Save and next') }}</x-hearth-button>
    </p>
</form>
