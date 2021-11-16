
<x-app-layout>
    <x-slot name="title">{{ __('Create a project') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create a project') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('This project will be created on behalf of :entity.', ['entity' => $entity->name]) }}</p>

    {{-- TODO: Remove this --}}
    @php $locales = ['en', 'fr']; @endphp

    <form id="create-project" action="{{ localized_route('projects.store', $entity) }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="entity_id" type="hidden" name="entity_id" :value="$entity->id" required />

        <x-translatable-input name="name" :label="__('Project name')" :locales="$locales" />

        <x-hearth-date-input :label="__('Project start date')" name="start_date" :value="old('start_date', '')" />

        <x-hearth-date-input :label="__('Project end date')" name="end_date" :value="old('end_date', '')" />

        <x-translatable-textarea name="goals" :label="__('Goals for consultation')" :locales="$locales" />

        <x-translatable-textarea name="impact" :label="__('Who will the project impact')" :locales="$locales" />

        <x-translatable-textarea name="out_of_scope" :label="__('What is this project not going to do?')" :locales="$locales" />

        <x-translatable-textarea name="timeline" :label="__('Project timeline')" :locales="$locales" />

        <x-hearth-button>{{ __('Create project') }}</x-hearth-button>
    </form>
</x-app-layout>
