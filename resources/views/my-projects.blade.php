<x-app-wide-layout>
    <x-slot name="title">{{ __('My projects') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name">
            {{ __('My projects') }}
        </h1>
        <a href="{{ localized_route('projects.index') }}" class="cta secondary">{{ __('Browse all projects') }}</a>
    </x-slot>

    <p><a href="{{ localized_route('projects.create') }}" class="button">{{ __('Create new project') }}</a></p>

    {{-- TODO: Projects I am contracted for --}}
    {{-- TODO: Projects I am participating in --}}
    {{-- TODO: Projects I am running --}}
    {{-- TODO: Suggested projects --}}
    {{-- TODO: Similar projects --}}

    <h2>{{ __('Active projects') }}</h2>
    @forelse($projectable->inProgressProjects as $project)
        <x-project-card :project="$project" :showRegulatedOrganization="false" />
        <p><a href="{{ localized_route('projects.manage', $project) }}">{{ __('Go to project dashboard') }}</a></p>
    @empty
        <p>{{ __('You have no active projects right now.') }}</p>
    @endforelse

    <h2>{{ __('Completed projects') }}</h2>
    @forelse($projectable->completedProjects as $project)
        <x-project-card :project="$project" :showRegulatedOrganization="false" />
    @empty
        <p>{{ __('You have not completed any projects yet.') }}</p>
    @endforelse

</x-app-wide-layout>
