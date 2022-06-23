<h2>
    {{ __('Step 1 of 3') }}<br />
    {{ __('About your project') }}
</h2>

<form class="stack" id="create-project" action="{{ localized_route('projects.store') }}" method="post" novalidate>
    @csrf

    <fieldset class="field @error('name') field--error @enderror stack">
        <x-hearth-input type="hidden" name="projectable_id" :value="$projectable->id" />
        <x-hearth-input type="hidden" name="projectable_type" :value="get_class($projectable)" />

        <x-hearth-input type="hidden" name="ancestor_id" :value="session()->get('ancestor')" />

        <h3>{{ __('Project name') }}</h3>

        <x-translatable-input name="name" :label="__('Project name (required)')" :value="old('name', '')" />

        <h3>{{ __('Project goals') }}</h3>

        <x-translatable-textarea name="goals" :label="__('What are your goals for this project? (required)')" :value="old('goals', '')" />

        <h3>{{ __('Project scope') }}</h3>

        <x-translatable-textarea name="scope" :label="__('What communities does this project hope to engage, and how will they be impacted? (required)')" :value="old('scope', '')" />

        <fieldset class="field @error('impacts') field--error @enderror stack">
            <legend>{{ __('What areas of your organization will this project impact?') }}</legend>
            <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', [])" />
            <x-hearth-error for="impacts" />
        </fieldset>

        <x-translatable-textarea name="out_of_scope" :label="__('What is out of scope for your project?')" />

        <h3>{{ __('Project timeframe') }}</h3>

        <x-hearth-date-input :label="__('Project start date')" name="start_date" :value="old('start_date', '')" />

        <x-hearth-date-input :label="__('Project end date')" name="end_date" :value="old('end_date', '')" />

        <h3>{{ __('Project outcomes') }}</h3>

        <x-translatable-textarea name="outcomes" :label="__('What are the tangible outcomes of this project?')" />

        <fieldset class="field @error('public_outcomes') field--error @enderror stack">
            <legend>{{ __('Will the outcomes be made publicly available?') }}</legend>
            <x-hearth-radio-buttons name="public_outcomes" :options="[1 => __('Yes'), 0 => __('No')]" :checked="old('public_outcomes', '')"  />
        </fieldset>
    </fieldset>

    <p class="repel">
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
