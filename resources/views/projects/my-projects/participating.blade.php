<h2>{{ __('Projects I am participating in') }}</h2>

@if ($user->context === 'individual')
    <h3>{{ __('In progress') }}</h3>
    @forelse ($user->individual->inProgressParticipatingProjects as $project)
        <x-card.project :project="$project" :level="4" />
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    <h3>{{ __('Upcoming') }}</h3>
    @forelse ($user->individual->upcomingParticipatingProjects as $project)
        <x-card.project :project="$project" :level="4" />
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    @if (!$user->individual->completedParticipatingProjects->isEmpty())
        <x-expander level="3" :summary="__('Completed')">
            @forelse ($user->individual->completedParticipatingProjects as $project)
                <x-card.project :project="$project" :level="4" />
            @empty
                <p>{{ __('No projects found.') }}</p>
            @endforelse
        </x-expander>
    @endif
@endif

@if ($user->context === 'organization')
    <h3>{{ __('In progress') }}</h3>
    @forelse ($user->organization->inProgressParticipatingProjects as $project)
        <x-card.project :project="$project" :level="4" />
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    <h3>{{ __('Upcoming') }}</h3>
    @forelse ($user->organization->upcomingParticipatingProjects as $project)
        <x-card.project :project="$project" :level="4" />
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    @if (!$user->organization->completedParticipatingProjects->isEmpty())
        <x-expander level="3" :summary="__('Completed')">
            @forelse ($user->organization->completedParticipatingProjects as $project)
                <x-card.project :project="$project" :level="4" />
            @empty
                <p>{{ __('No projects found.') }}</p>
            @endforelse
        </x-expander>
    @endif
@endif
