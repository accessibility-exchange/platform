<h2>{{ __('Projects I am contracted for') }}</h2>

@if($user->context === 'individual')
    <h3>{{  __('In progress') }}</h3>
    @forelse ($user->individual->inProgressContractedProjects as $project)
        <x-project-card :project="$project" :level="4" />
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    <h3>{{  __('Upcoming') }}</h3>
    @forelse ($user->individual->upcomingContractedProjects as $project)
        <x-project-card :project="$project" :level="4" />
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    @if(!$user->individual->completedContractedProjects->isEmpty())
        <x-expander level="3" :summary="__('Completed')">
            @forelse ($user->individual->completedContractedProjects as $project)
                <x-project-card :project="$project" :level="4" />
            @empty
                <p>{{ __('No projects found.') }}</p>
            @endforelse
        </x-expander>
    @endif
@endif
