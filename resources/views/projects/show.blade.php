<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $project->name }}
        </h1>
        <p>{!! __('project.initiated_by', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</p>
    </x-slot>

    @can('update', $project)
    <p><a href="{{ localized_route('project.edit', $project) }}">{{ __('project.edit_project') }}</a></p>
    @endcan
</x-app-layout>
