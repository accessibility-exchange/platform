<form class="stack" action="{{ localized_route('projects.update', $project) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="with-sidebar with-sidebar:last">

        @include('projects.partials.progress')

        <div class="stack">
            <h2>
                {{ __('Project overview') }}
            </h2>

            <p class="repel">
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>

            <h3>{{ __('Project name') }}</h3>

            <x-translatable-input name="name" :label="__('Project name (required)')" :model="$project" />

            <h3>{{ __('Project goals') }}</h3>

            <x-translatable-textarea name="goals" :label="__('What are your goals for this project? (required)')" :model="$project" />

            <h3>{{ __('Project scope') }}</h3>

            <x-translatable-textarea name="scope" :label="__('Please identify the communities this project hopes to engage and how they will be impacted. (required)')" :model="$project" />

            <fieldset class="field @error('regions') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Please indicate the geographical areas this project will impact? (required)') }}</legend>
                <p class="stack" x-cloak>
                    <button class="secondary" type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button class="secondary" type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </p>
                <x-hearth-checkboxes name="regions" :options="array_filter($regions)" :checked="old('regions_impacted', $project->regions ?? [])" required />
            </fieldset>

            <fieldset class="field @error('impacts') field--error @enderror stack">
                <legend>{{ __('Please indicate which areas of your organization this project will impact. (required)') }}</legend>
                <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $project->impacts->pluck('id')->toArray())" />
                <x-hearth-error for="impacts" />
            </fieldset>

            <x-translatable-textarea name="out_of_scope" :label="__('What is out of scope for your project?')" :model="$project" />

            <h3>{{ __('Project timeframe') }}</h3>

            <livewire:date-picker :label="__('Project start date (required)')" name="start_date" :value="old('start_date', $project->start_date)" />

            <livewire:date-picker :label="__('Project end date (required)')" name="end_date" :value="old('end_date', $project->end_date)" />

            <h3>{{ __('Project outcome') }}</h3>

            <fieldset class="field @error('outcome_analysis') field--error @enderror stack" x-data="{otherOutcomeAnalysis: {{ old('other', !is_null($project->outcome_analysis_other) && $project->outcome_analysis_other !== '' ? 'true' : 'false') }}}">
                <legend>{{ __('Who is analyzing the results?') }}</legend>
                <x-hearth-checkboxes name="outcome_analysis" :options="\Spatie\LaravelOptions\Options::forArray(['internal' => __('Internal team'), 'external' => __('External team')])->toArray()" :checked="old('outcome_analysis', $project->outcome_analysis ?? [])" x-model="outcomeAnalysis" />
                <div class="field">
                    <x-hearth-checkbox name="other" :checked="old('other', !is_null($project->outcome_analysis_other) && $project->outcome_analysis_other !== '')" x-model="otherOutcomeAnalysis" /> <x-hearth-label for='other'>{{ __('Other') }}</x-hearth-label>
                </div>
                <div class="field__subfield stack">
                    <x-translatable-input name="outcome_analysis_other" :label="__('Other')" :model="$project" x-show="otherOutcomeAnalysis" />
                </div>
            </fieldset>

            <x-translatable-textarea name="outcomes" :label="__('Please indicate the tangible outcomes of this project. (required)')" :hint="__('For example, an accessibility report')" :model="$project" />

            <fieldset class="field @error('public_outcomes') field--error @enderror stack">
                <legend>{{ __('Please indicate if the reports will be publicly available. (required)') }}</legend>
                <x-hearth-radio-buttons name="public_outcomes" :options="Spatie\LaravelOptions\Options::forArray([1 => __('Yes'), 0 => __('No')])->toArray()" :checked="old('public_outcomes', $project->public_outcomes)"  />
            </fieldset>

            <p class="repel">
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
