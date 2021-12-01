<x-app-wide-layout>
    <x-slot name="title">{{ __('My projects') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            {{ __('My projects') }}
        </h1>
    </x-slot>

    <p><a href="{{ localized_route('projects.create', $entity) }}" class="button">{{ __('Create new project') }}</a></p>

    <h2>{{ __('Active projects') }}</h2>
    @forelse($entity->currentProjects as $project)
    <x-project-card :project="$project" :showEntity="false" />
    <p><a href="{{ localized_route('projects.manage', $project) }}">{{ __('Go to project dashboard') }}</a></p>
    @empty
    <p>{{ __('You have no active projects right now.') }}</p>
    @endforelse

    <h2>{{ __('Completed projects') }}</h2>
    @forelse($entity->pastProjects as $project)
    <x-project-card :project="$project" :showEntity="false" />
    <p><a class="button" href="{{ localized_route('projects.create-update', $project) }}">{{ __('Provide team update') }}</a> <a href="{{ localized_route('projects.manage', $project) }}">{{ __('Go to project dashboard') }}</a></p>
    @empty
    <p>{{ __('You have not completed any projects yet.') }}</p>
    @endforelse

</x-app-wide-layout>
