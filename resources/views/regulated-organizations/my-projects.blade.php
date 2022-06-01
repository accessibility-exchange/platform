<x-app-wide-layout>
    <x-slot name="title">{{ __('My projects') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            {{ __('My projects') }}
        </h1>
    </x-slot>

    <p><a href="{{ localized_route('projects.create') }}" class="button">{{ __('Create new project') }}</a></p>

    <h2>{{ __('Active projects') }}</h2>
    @forelse($regulatedOrganization->inProgressProjects as $project)
    <x-project-card :project="$project" :showRegulatedOrganization="false" />
    <p><a href="{{ localized_route('projects.manage', $project) }}">{{ __('Go to project dashboard') }}</a></p>
    @empty
    <p>{{ __('You have no active projects right now.') }}</p>
    @endforelse

    <h2>{{ __('Completed projects') }}</h2>
    @forelse($regulatedOrganization->completedProjects as $project)
    <x-project-card :project="$project" :showRegulatedOrganization="false" />
    @empty
    <p>{{ __('You have not completed any projects yet.') }}</p>
    @endforelse

</x-app-wide-layout>
