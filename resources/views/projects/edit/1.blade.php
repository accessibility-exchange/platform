<form class="stack" action="{{ localized_route('projects.update', $project) }}" method="POST"
    enctype="multipart/form-data" novalidate>
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

            <x-translatable-input name="name" :label="__('Project name (please fill this out)')" :hint="__('This is the name that will be displayed on your project page.')" :model="$project" />

            <h3>{{ __('Project goals') }}</h3>

            <x-translatable-textarea name="goals" :label="__('Please indicate the goals for this project. (required)')" :model="$project" />

            <h3>{{ __('Project scope') }}</h3>

            <x-translatable-textarea name="scope" :label="__(
                'Please describe how the Disability and Deaf communities will be impacted by the outcomes of your project. (required)',
            )" :model="$project" />

            <fieldset class="field @error('regions') field--error @enderror" x-data="enhancedCheckboxes()">
                <legend>{{ __('Please indicate the geographical areas this project will impact. (required)') }}</legend>
                <x-hearth-checkboxes name="regions" :options="array_filter($regions)" :checked="old('regions_impacted', $project->regions ?? [])" required />
                <div class="stack" x-cloak>
                    <button class="secondary" type="button" x-on:click="selectAll()">{{ __('Select all') }}</button>
                    <button class="secondary" type="button" x-on:click="selectNone()">{{ __('Select none') }}</button>
                </div>
            </fieldset>

            <fieldset class="field @error('impacts') field--error @enderror stack">
                <legend>
                    {{ __('Please indicate which areas of your organization this project will impact. (required)') }}
                </legend>
                <x-hearth-checkboxes name="impacts" :options="$impacts" :checked="old('impacts', $project->impacts->pluck('id')->toArray())" />
                <x-hearth-error for="impacts" />
            </fieldset>

            <x-translatable-textarea name="out_of_scope" :label="__('Please indicate what is out of scope for this project.  (optional)')" :model="$project" />

            <h3>{{ __('Project timeframe') }}</h3>

            <livewire:date-picker name="start_date" :label="__('Project start date (required)')" minimumYear="2021" :value="old('start_date', $project->start_date?->format('Y-m-d') ?? null)" />

            <livewire:date-picker name="end_date" :label="__('Project end date (required)')" minimumYear="2021" :value="old('end_date', $project->end_date?->format('Y-m-d') ?? null)" />

            <h3>{{ __('Project outcome') }}</h3>

            <fieldset class="field @error('outcome_analysis') field--error @enderror stack" x-data="{ otherOutcomeAnalysis: {{ old('other', !is_null($project->outcome_analysis_other) && $project->outcome_analysis_other !== '' ? 'true' : 'false') }} }">
                <legend>{{ __('Who will be going through the results from this project and writing a report?') }}
                </legend>
                <x-hearth-checkboxes name="outcome_analysis" :options="\Spatie\LaravelOptions\Options::forArray([
                    'internal' => __('Internal team'),
                    'external' => __('External team'),
                ])->toArray()" :checked="old('outcome_analysis', $project->outcome_analysis ?? [])" />
                <div class="field">
                    <x-hearth-checkbox name="other" :checked="old(
                        'other',
                        !is_null($project->outcome_analysis_other) && $project->outcome_analysis_other !== '',
                    )" x-model="otherOutcomeAnalysis" />
                    <x-hearth-label for='other'>{{ __('Other') }}</x-hearth-label>
                </div>
                <div class="field__subfield stack">
                    <x-translatable-input name="outcome_analysis_other" :label="__('Other')" :model="$project"
                        x-show="otherOutcomeAnalysis" />
                </div>
            </fieldset>

            <x-translatable-textarea name="outcomes" :label="__('Please indicate the tangible outcomes of this project. (required)')" :hint="__('For example, an accessibility report')" :model="$project" />

            <fieldset class="field @error('public_outcomes') field--error @enderror stack">
                <legend>{{ __('Please indicate if the reports will be publicly available. (required)') }}</legend>
                <x-hearth-hint for="public_outcomes">
                    {{ __('This can mean either on this website, or on your organization’s website.') }}
                </x-hearth-hint>
                <x-hearth-radio-buttons name="public_outcomes" :options="Spatie\LaravelOptions\Options::forArray([1 => __('Yes'), 0 => __('No')])->toArray()" :checked="old('public_outcomes', $project->public_outcomes)" />
            </fieldset>

            <p class="repel">
                <button name="save" value="1">{{ __('Save') }}</button>
                <button class="secondary" name="save_and_next" value="1">{{ __('Save and next') }}</button>
            </p>
        </div>
    </div>
</form>
