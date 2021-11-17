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

        <x-translatable-input name="name" :label="__('Project name')" :model="$project" />

        <x-hearth-date-input :label="__('Project start date')" name="start_date" :value="old('start_date', $project->start_date->format('Y-m-d'))" />

        <x-hearth-date-input :label="__('Project end date')" name="end_date" :value="old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '')" />

        <x-translatable-textarea name="goals" :label="__('Goals for consultation')" :model="$project" />

        <x-translatable-textarea name="impact" :label="__('Who will the project impact')" :model="$project" />

        <x-translatable-textarea name="out_of_scope" :label="__('What is this project not going to do?')" :model="$project" />

        <x-translatable-textarea name="timeline" :label="__('Project timeline')" :model="$project" />

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
