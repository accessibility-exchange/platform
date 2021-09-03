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
        @method('put')

        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('project.label_name')" />
            <x-hearth-input type="text" name="name" :value="old('name', $project->name)" required />
            <x-hearth-error for="name" />
        </div>

        <x-hearth-date-input :label="__('project.label_start_date')" name="start_date" :value="old('start_date', $project->start_date->format('Y-m-d'))" />

        <x-hearth-date-input :label="__('project.label_end_date')" name="end_date" :value="old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '')" />

        <x-hearth-button>{{ __('forms.save_changes') }}</x-hearth-button>
    </form>

    <h2>
        {{ __('project.delete_title') }}
    </h2>

    <p>{{ __('project.delete_intro') }}</p>

    <form action="{{ localized_route('projects.destroy', $project) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyProject') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyProject" />
        </div>

        <x-hearth-button>
            {{ __('project.action_delete') }}
        </x-hearth-button>
    </form>
</x-app-layout>
