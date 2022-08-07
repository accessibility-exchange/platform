<div class="flex items-center justify-between">
    <h2>{{ __('Projects I am running') }}</h2>
    @if(
    !$projectable->inProgressProjects->isEmpty()
    || !$projectable->upcomingProjects->isEmpty()
    || !$projectable->completedProjects->isEmpty()
)
    <a href="{{ localized_route('projects.create') }}" class="cta">{{ __('Create new project') }}</a>
        @endif
</div>

@if(
    $projectable->inProgressProjects->isEmpty()
    && $projectable->upcomingProjects->isEmpty()
    && $projectable->completedProjects->isEmpty()
)
    <div class="box stack bg-grey-2">
        <p>{{ __('It seems as though you have not created any projects yet.') }}</p>
        <p><a href="{{ localized_route('projects.create') }}" class="cta">{{ __('Create new project') }}</a></p>
    </div>
@endif

@if(!$projectable->inProgressProjects->isEmpty())
<h3>{{  __('In progress') }}</h3>
@foreach ($projectable->inProgressProjects as $project)
    <x-project-card :project="$project" :level="4" />
@endforeach
@endif

@if(!$projectable->upcomingProjects->isEmpty())
    <h3>{{  __('In progress') }}</h3>
    @foreach ($projectable->upcomingProjects as $project)
        <x-project-card :project="$project" :level="4" />
    @endforeach
@endif

@if(!$projectable->completedProjects->isEmpty())
    <x-expander level="3" :summary="__('Completed')">
        @forelse ($projectable->completedProjects as $project)
            <x-project-card :project="$project" :level="4" />
        @empty
            <p>{{ __('No projects found.') }}</p>
        @endforelse
    </x-expander>
@endif
