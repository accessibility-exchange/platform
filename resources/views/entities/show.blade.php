<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $entity->name }}
        </h1>
    </x-slot>

    <p>{{ $entity->locality }}, {{ __('regions.' . $entity->region) }}</p>

    <h3>{{ __('entity.active_projects') }}</h3>

    @forelse ($entity->projects as $project)
    <p><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></p>
    @empty
    <p>{{ __('project.none_found') }}</p>
    @endforelse

    @can('update', $entity)
    <p><a href="{{ localized_route('entities.edit', $entity) }}">{{ __('entity.edit_entity') }}</a></p>
    @endcan
</x-app-layout>
