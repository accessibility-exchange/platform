<h2>
    {{ __('Step 2 of 3') }}<br />
    {{ __('Project team') }}
</h2>

@include('projects.partials.progress')

<form class="stack" id="edit-project" action="{{ localized_route('projects.update-team', $project) }}" method="POST" novalidate>
    @method('put')
    @csrf

    <h3>{{ __('About your team') }}</h3>


    <div class="field @error('team_size') field--error @enderror stack">
        <x-hearth-label for="team_size" :value="__('How many people are on your team?')" />
        <x-hearth-hint for="team_size">{{ __('You can give an exact number or range.') }}</x-hearth-hint>
        <x-hearth-input name="team_size" :value="old('team_size', $project->team_size)" hinted />
    </div>

    <fieldset class="field @error('team_has_disability_or_deaf_lived_experience') field--error @enderror stack">
        <legend class="h4">{{ __('Does any member of your team have lived/living experiences of disability or being Deaf?') }}</legend>
        <x-hearth-radio-buttons name="team_has_disability_or_deaf_lived_experience" :options="[1 => __('Yes'), 0 => __('No'), '' => __('Prefer not to say')]" :checked="old('team_has_disability_or_deaf_lived_experience', $project->team_has_disability_or_deaf_lived_experience ?? '')"  />
    </fieldset>

    <fieldset class="field @error('team_has_other_lived_experience') field--error @enderror stack">
        <legend class="h4">{{ __('Does anyone in your team identify as a member of another equity-seeking group?') }}</legend>
        <x-hearth-hint for="team_has_other_lived_experience">{{ __('For example, Black, Indigenous, person of colour, 2SLGBTQIA+, newcomer or immigrant.') }}</x-hearth-hint>
        <x-hearth-radio-buttons name="team_has_other_lived_experience" :options="[1 => __('Yes'), 0 => __('No'), '' => __('Prefer not to say')]" :checked="old('team_has_other_lived_experience', $project->team_has_other_lived_experience ?? '')" hinted />
    </fieldset>

    <fieldset class="field stack">
        <legend class="h4">{{ __('What languages do people on the project team speak fluently?') }}</legend>
        <livewire:language-picker name="team_languages" :languages="['en']" :availableLanguages="$languages" />
    </fieldset>

    <fieldset class="field stack">
        <legend class="h4">{{ __('Team contacts') }}</legend>
        <livewire:contacts :contacts="old('contacts', $project->contacts ?? [['name' => '', 'email' => '', 'phone' => '']])" />
    </fieldset>

    <div class="stack" x-data="{ hasConsultant: '{{ old('has_consultant', $project->has_consultant) }}' }">
        <fieldset class="field @error('has_consultant') field--error @enderror stack">
            <legend class="h4">{{ __('Are you working with an accessibility consultant on this project? (required)') }}</legend>
            <x-hearth-radio-buttons name="has_consultant" :options="[1 => __('Yes'), 0 => __('No')]" :checked="old('has_consultant', $project->has_consultant)" x-model="hasConsultant" />
        </fieldset>
        <fieldset x-show="hasConsultant == '1'" class="stack" x-data="{consultantOrigin: '{{ old('consultant_origin', $project->consultant_origin()) }}'}">
            <legend class="h4">{{ _('Where did you find the accessibility consultant? (required)') }}</legend>
            <x-hearth-radio-buttons name="consultant_origin" :options="['platform' => __('On the Accessibility Exchange'), 'external' => __('Somewhere else')]" :checked="old('consultant_origin', $project->consultant_origin())" x-model="consultantOrigin" />
            <div class="field @error('consultant_id') field--error @enderror stack" x-show="consultantOrigin == 'platform'">
                <x-hearth-label for="consultant_id" :value="__('Consultant (required)')" />
                <x-hearth-select x-data="autocomplete()" name="consultant_id" :options="$consultants" :selected="old('consultant_id', $project->consultant_id)" />
            </div>
            <div class="field @error('consultant_name') field--error @enderror stack" x-show="consultantOrigin == 'external'">
                <x-hearth-label for="consultant_name" :value="__('Consultant name (required)')" />
                <x-hearth-input name="consultant_name" :value="old('consultant_name', $project->consultant_name)" />
            </div>
            <div class="field @error('consultant_responsibilities') field--error @enderror stack">
                <x-translatable-textarea name="consultant_responsibilities" :label="__('Description of responsibilities')" :model="$project" />
            </div>
        </fieldset>
    </div>

    <fieldset class="field stack">
        <legend class="h4">{{ __('Team trainings') }}</legend>
        <livewire:team-trainings :trainings="old('team_trainings', $project->team_trainings ?? [['name' => '', 'date' => '', 'trainer_name' => '', 'trainer_url' => '']])">
    </fieldset>

    <p class="repel">
        <x-hearth-input type="submit" name="save_and_previous" :value="__('Save and previous')" />
        <x-hearth-input type="submit" name="save" :value="__('Save')" />
        <x-hearth-input type="submit" name="save_and_next" :value="__('Save and next')" />
    </p>
</form>
