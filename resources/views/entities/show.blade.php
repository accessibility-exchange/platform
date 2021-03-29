<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $entity->name }}
        </h1>
    </x-slot>

    <p>{{ $entity->locality }}, {{ __('regions.' . $entity->region) }}</p>

    @can('update', $entity)
    <p><a href="{{ localized_route('entities.edit', $entity) }}">{{ __('entity.edit_entity') }}</a></p>
    @endcan

    <h2>{{ __('entity.active_projects') }}</h2>

    <div class="projects flow">
        @forelse ($entity->projects as $project)
        <x-project-card :project="$project" :showEntity="false" />
        @empty
        <p>{{ __('project.none_found') }}</p>
        @endforelse
    </div>

    @if(count($entity->projects) > 0)
    <p><a href="{{ localized_route('projects.entity-index', $entity) }}">{{ __('entity.browse_all_projects', ['entity' => $entity->name]) }} <span class="aria-hidden">&rarr;</span></a></p>
    @endif

    @can('update', $entity)
    <p><a href="{{ localized_route('projects.create', $entity) }}">{{ __('entity.create_project') }}</a></p>
    @endcan
</x-app-layout>
