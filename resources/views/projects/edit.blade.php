<x-app-layout>
    <x-slot name="title">{{ __('Edit “:name”', ['name' => $project->name]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit “:name”', ['name' => $project->name]) }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form id="update-project" action="{{ localized_route('projects.update', $project) }}" method="POST" novalidate>
        @csrf
        @method('put')

        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('Project name')" />
            <x-hearth-input type="text" name="name" :value="old('name', $project->name)" required />
            <x-hearth-error for="name" />
        </div>

        <x-hearth-date-input :label="__('Project start date')" name="start_date" :value="old('start_date', $project->start_date->format('Y-m-d'))" />

        <x-hearth-date-input :label="__('Project end date')" name="end_date" :value="old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '')" />

        <div class="field @error('goals') field--error @enderror">
            <x-hearth-label for="goals" :value="__('Goals for consultation')" />
            <x-hearth-textarea name="goals" required :value="old('goals', $project->goals)" />
            <x-hearth-error for="goals" />
        </div>

        <div class="field @error('impact') field--error @enderror">
            <x-hearth-label for="impact" :value="__('Who will the project impact')" />
            <x-hearth-textarea name="impact" required :value="old('impact', $project->impact)" />
            <x-hearth-error for="impact" />
        </div>

        <div class="field @error('out_of_scope') field--error @enderror">
            <x-hearth-label for="out_of_scope" :value="__('What is this project not going to do?')" />
            <x-hearth-textarea name="out_of_scope" required :value="old('out_of_scope', $project->out_of_scope)" />
            <x-hearth-error for="out_of_scope" />
        </div>

        <div class="field @error('timeline') field--error @enderror">
            <x-hearth-label for="timeline" :value="__('Project timeline')" />
            <x-hearth-textarea name="timeline" required :value="old('timeline', $project->timeline)" />
            <x-hearth-error for="timeline" />
        </div>

        <x-hearth-button>{{ __('Save changes') }}</x-hearth-button>
    </form>

    <h2>
        {{ __('Delete project') }}
    </h2>

    <p>{{ __('Your project will be deleted and cannot be recovered. If you still want to delete your project, please enter your current password to proceed.') }}</p>

    <form action="{{ localized_route('projects.destroy', $project) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyProject') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyProject" />
        </div>

        <x-hearth-button>
            {{ __('Delete project') }}
        </x-hearth-button>
    </form>
</x-app-layout>
