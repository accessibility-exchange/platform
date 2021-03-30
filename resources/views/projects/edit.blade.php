
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('project.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form id="update-project" action="{{ localized_route('projects.update', $project) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="field">
            <x-label for="name" :value="__('project.label_name')" />
            <x-input id="name" type="text" name="name" :value="old('name', $project->name)" required />
        </div>

        <x-date-input :label="__('project.label_start_date')" name="start_date" :value="old('start_date', $project->start_date->format('Y-m-d'))" />

        <x-date-input :label="__('project.label_end_date')" name="end_date" :value="old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '')" />

        <x-button>{{ __('forms.save_changes') }}</x-button>
    </form>
</x-app-layout>
