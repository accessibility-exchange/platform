<h3>{{  __('Upcoming') }}</h3>
@forelse ($organization->upcomingProjects as $project)
    <x-project-card :project="$project" :level="4" />
@empty
    <p>{{ __('No projects found.') }}</p>
@endforelse
<h3>{{  __('In progress') }}</h3>
@forelse ($organization->inProgressProjects as $project)
    <x-project-card :project="$project" :level="4" />
@empty
    <p>{{ __('No projects found.') }}</p>
@endforelse
@if(!$organization->completedProjects->isEmpty())
<x-expander level="3" :summary="__('Completed')">
    @forelse ($organization->completedProjects as $project)
        <x-project-card :project="$project" :level="4" />
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse
</x-expander>
@endif
