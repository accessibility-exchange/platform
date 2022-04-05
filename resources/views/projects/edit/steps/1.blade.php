<h2>
    {{ __('Step 1 of 3') }}<br />
    {{ __('About your project') }}
</h2>

@include('projects.partials.progress')

<form class="stack" id="edit-project" action="{{ localized_route('projects.update', $project) }}" method="POST" novalidate>
    @method('put')
    @csrf

    <h3>{{ __('Project name') }}</h3>

    <x-translatable-input name="name" :label="__('Project name')" :model="$project" />

    <h3>{{ __('Project goals') }}</h3>

    <x-translatable-textarea name="goals" :label="__('What are your goals for this project? (required)')" :model="$project" />

    <h3>{{ __('Project scope') }}</h3>

    <x-translatable-textarea name="scope" :label="__('What communities does this project hope to engage, and how will they be impacted?')" :model="$project" />

    <fieldset class="field @error('impacts') field--error @enderror stack">
        <legend>{{ __('What areas of your organization will this project impact?') }}</legend>
        <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $project->impacts->pluck('id')->toArray())" />
        <x-hearth-error for="impacts" />
    </fieldset>

    <x-translatable-textarea name="out_of_scope" :label="__('What is out of scope for your project?')" :model="$project" />

    <h3>{{ __('Project timeframe') }}</h3>

    <x-hearth-date-input :label="__('Project start date')" name="start_date" :value="old('start_date', $project->start_date)" />

    <x-hearth-date-input :label="__('Project end date')" name="end_date" :value="old('end_date', $project->end_date)" />

    <h3>{{ __('Project outcomes') }}</h3>

    <x-translatable-textarea name="outcomes" :label="__('What are the tangible outcomes of this project?')" :model="$project" />

    <fieldset class="field @error('public_outcomes') field--error @enderror stack">
        <legend>{{ __('Will the outcomes be made publicly available?') }}</legend>
        <x-hearth-radio-buttons name="public_outcomes" :options="[1 => __('Yes'), 0 => __('No')]" :checked="old('public_outcomes', $project->public_outcomes)"  />
    </fieldset>

    <p class="repel">
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
