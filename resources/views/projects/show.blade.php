<x-app-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $project->name }}
        </h1>
        <p>{!! __('project.project_by', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</p>
        <p>{!! $project->timespan() !!}</p>
    </x-slot>

    @can('update', $project)
    <p><a href="{{ localized_route('projects.edit', $project) }}">{{ __('project.edit_project') }}</a></p>
    @endcan
</x-app-layout>
