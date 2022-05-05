
<x-app-layout>
    <x-slot name="title">{{ __('Create an engagement') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create an engagement') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('This engagement will be created as part of :project.', ['project' => $project->name]) }}</p>

    <form id="create-engagement" action="{{ localized_route('engagements.store', $project) }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="project_id" type="hidden" name="project_id" :value="$project->id" required />

        <x-translatable-input name="name" :label="__('Engagement name')" />

        <x-translatable-textarea name="goals" :label="__('Goals for engagement')" />

        <fieldset>
            <legend>{{ __('Recruiting participants') }}</legend>

            <x-hearth-hint for="recruitment">{{ __('How do you want to recruit participants for this engagement?') }}</x-hearth-hint>

            <x-hearth-radio-buttons name="recruitment" :options="[
                'automatic' => __('With the Accessibility Exchange’s automatic matching system'),
                'open' => __('With an open call for participants'),
            ]" :checked="old('recruitment', session('recruitment')) ?? false" hinted />
        </fieldset>

        <button>{{ __('Create engagement') }}</button>
    </form>
</x-app-layout>
