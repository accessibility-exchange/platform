<h2>{{ __('Projects I am contracted for') }}</h2>

@if ($user->context === 'individual')
    <h3>{{ __('In progress') }}</h3>
    @forelse ($user->individual->inProgressContractedProjects as $project)
        @include('projects.partials.project-and-contracted-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    <h3>{{ __('Upcoming') }}</h3>
    @forelse ($user->individual->upcomingContractedProjects as $project)
        @include('projects.partials.project-and-contracted-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    @if (!$user->individual->completedContractedProjects->isEmpty())
        <x-expander level="3" :summary="__('Completed')">
            @forelse ($user->individual->completedContractedProjects as $project)
                @include('projects.partials.project-and-contracted-engagements')
            @empty
                <p>{{ __('No projects found.') }}</p>
            @endforelse
        </x-expander>
    @endif
@endif

@if ($user->context === 'organization')
    <h3>{{ __('In progress') }}</h3>
    @forelse ($user->organization->inProgressContractedProjects as $project)
        @include('projects.partials.project-and-contracted-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    <h3>{{ __('Upcoming') }}</h3>
    @forelse ($user->organization->upcomingContractedProjects as $project)
        @include('projects.partials.project-and-contracted-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    @if (!$user->organization->completedContractedProjects->isEmpty())
        <x-expander level="3" :summary="__('Completed')">
            @forelse ($user->organization->completedContractedProjects as $project)
                @include('projects.partials.project-and-contracted-engagements')
            @empty
                <p>{{ __('No projects found.') }}</p>
            @endforelse
        </x-expander>
    @endif
@endif
