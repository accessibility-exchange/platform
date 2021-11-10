
<x-app-layout>
    <x-slot name="title">{{ __('Create a project') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create a project') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('project.create_intro', ['entity' => $entity->name]) }}</p>

    <form id="create-project" action="{{ localized_route('projects.store', $entity) }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="entity_id" type="hidden" name="entity_id" :value="$entity->id" required />

        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('Project name')" />
            <x-hearth-input type="text" name="name" required :value="old('name', '')" />
            <x-hearth-error for="name" />
        </div>

        <x-hearth-date-input :label="__('Project start date')" name="start_date" :value="old('start_date', '')" />

        <x-hearth-date-input :label="__('Project end date')" name="end_date" :value="old('end_date', '')" />

        <div class="field @error('goals') field--error @enderror">
            <x-hearth-label for="goals" :value="__('Goals for consultation')" />
            <x-hearth-textarea name="goals" required :value="old('goals', '')" />
            <x-hearth-error for="goals" />
        </div>

        <div class="field @error('impact') field--error @enderror">
            <x-hearth-label for="impact" :value="__('Who will the project impact')" />
            <x-hearth-textarea name="impact" required :value="old('impact', '')" />
            <x-hearth-error for="impact" />
        </div>

        <div class="field @error('out_of_scope') field--error @enderror">
            <x-hearth-label for="out_of_scope" :value="__('What is this project not going to do?')" />
            <x-hearth-textarea name="out_of_scope" required :value="old('out_of_scope', '')" />
            <x-hearth-error for="out_of_scope" />
        </div>

        <div class="field @error('timeline') field--error @enderror">
            <x-hearth-label for="timeline" :value="__('Project timeline')" />
            <x-hearth-textarea name="timeline" required :value="old('timeline', '')" />
            <x-hearth-error for="timeline" />
        </div>

        <x-hearth-button>{{ __('Create project') }}</x-hearth-button>
    </form>
</x-app-layout>
