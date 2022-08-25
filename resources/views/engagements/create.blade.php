
<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        </ol>
        <h1>
            {{ __('Create engagement') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('An engagement involves a group of people participating in one set way (for example, a focus group or a survey). An engagement like a focus group can have multiple meetings.') }}</p>

    <p>{{ __('For example: The engagement group could be a focus group for Deaf customers. There could be three times the focus group meets to discuss different topics.') }}</p>

    <form class="stack" action="{{ localized_route('engagements.store', $project) }}" method="post" novalidate>
        @csrf

        <x-hearth-input id="project_id" type="hidden" name="project_id" :value="$project->id" required />

        <x-translatable-input name="name" :label="__('What is the name of your engagement?')" />

        <fieldset class="field @error('format') field--error @enderror">
            <legend>{{ __('What format would you like to use?') }}</legend>
            <x-hearth-radio-buttons name="format" :options="$formats" :checked="old('format', '')" />
            <x-hearth-error for="format" />
        </fieldset>

        <fieldset class="field stack">
            <legend>{{ __('How many participants would you like to engage?') }}</legend>

            <x-hearth-hint for="participants">{{ __('Please enter a number, for example 20') }}</x-hearth-hint>

            <div class="field @error('ideal_participants') field--error @enderror">
                <x-hearth-label for="ideal_participants">{{ __('Ideal number of participants') }}</x-hearth-label>
                <x-hearth-hint for="ideal_participants">{{ __('This is the ideal number of participants you would like to have for this engagement.') }}</x-hearth-hint>
                <x-hearth-input name="ideal_participants" type="number" :value="old('ideal_participants')" min="1" hinted required />
                <x-hearth-error for="ideal_participants" />
            </div>

            <div class="field @error('minimum_participants') field--error @enderror">
                <x-hearth-label for="minimum_participants">{{ __('Minimum number of participants') }}</x-hearth-label>
                <x-hearth-hint for="minimum_participants">{{ __('The least number of participants you can have to go forward with your engagement.') }}</x-hearth-hint>
                <x-hearth-input name="minimum_participants" type="number" :value="old('minimum_participants')" min="1" hinted required />
                <x-hearth-error for="minimum_participants" />
            </div>
        </fieldset>

        <div class="repel">
            <a class="cta secondary" href="{{ localized_route('engagements.show-language-selection', $project) }}">{{ __('Back') }}</a>
            <button>{{ __('Next') }}</button>
        </div>
    </form>
</x-app-layout>
