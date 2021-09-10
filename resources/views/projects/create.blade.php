
<x-app-layout>
    <x-slot name="title">{{ __('project.create_title') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('project.create_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('project.create_intro', ['entity' => $entity->name]) }}</p>

    <form id="create-project" action="{{ localized_route('projects.store', $entity) }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="entity_id" type="hidden" name="entity_id" :value="$entity->id" required />

        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('project.label_name')" />
            <x-hearth-input type="text" name="name" required :value="old('name', '')" />
            <x-hearth-error for="name" />
        </div>

        <x-hearth-date-input :label="__('project.label_start_date')" name="start_date" :value="old('start_date', '')" />

        <x-hearth-date-input :label="__('project.label_end_date')" name="end_date" :value="old('end_date', '')" />

        <x-hearth-button>{{ __('project.action_create') }}</x-hearth-button>
    </form>
</x-app-layout>
