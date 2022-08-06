<div class="flex items-center justify-between">
    <h2>{{ __('Projects I am running') }}</h2>
    <a href="{{ localized_route('projects.create') }}" class="cta">{{ __('Create new project') }}</a>
</div>

<h3>{{  __('In progress') }}</h3>
@forelse ($projectable->inProgressProjects as $project)
    <x-project-card :project="$project" :level="4" />
@empty
    <p>{{ __('No projects found.') }}</p>
@endforelse

<h3>{{  __('Upcoming') }}</h3>
@forelse ($projectable->upcomingProjects as $project)
    <x-project-card :project="$project" :level="4" />
@empty
    <p>{{ __('No projects found.') }}</p>
@endforelse

@if(!$projectable->completedProjects->isEmpty())
    <x-expander level="3" :summary="__('Completed')">
        @forelse ($projectable->completedProjects as $project)
            <x-project-card :project="$project" :level="4" />
        @empty
            <p>{{ __('No projects found.') }}</p>
        @endforelse
    </x-expander>
@endif
