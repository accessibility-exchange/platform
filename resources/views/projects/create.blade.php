
<x-app-layout>
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
        <x-input id="entity_id" type="hidden" name="entity_id" :value="$entity->id" required />

        <div class="field">
            <x-label for="name" :value="__('project.label_name')" />
            <x-input id="name" type="text" name="name" required />
        </div>

        <x-date-input :label="__('project.label_start_date')" name="start_date" :value="old('start_date', '')" />

        <x-date-input :label="__('project.label_end_date')" name="end_date" :value="old('end_date', '')" />

        <x-button>{{ __('project.action_create') }}</x-button>
    </form>
</x-app-layout>
