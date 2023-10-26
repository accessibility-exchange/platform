<h2>{{ __('Projects I am participating in') }}</h2>
<x-interpretation name="{{ __('Projects I am participating in', [], 'en') }}" />

@if ($user->context === 'individual')
    <h3>{{ __('In progress') }}</h3>
    <x-interpretation name="{{ __('In progress', [], 'en') }}" />
    @forelse ($user->individual->inProgressParticipatingProjects as $project)
        @include('projects.partials.project-and-participating-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    <h3>{{ __('Upcoming') }}</h3>
    <x-interpretation name="{{ __('Upcoming', [], 'en') }}" />
    @forelse ($user->individual->upcomingParticipatingProjects as $project)
        @include('projects.partials.project-and-participating-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    @if (!$user->individual->completedParticipatingProjects->isEmpty())
        <x-expander level="3" :summary="__('Completed')">
            <x-interpretation name="{{ __('Completed', [], 'en') }}" />
            @forelse ($user->individual->completedParticipatingProjects as $project)
                @include('projects.partials.project-and-participating-engagements')
            @empty
                <p>{{ __('No projects found.') }}</p>
            @endforelse
        </x-expander>
    @endif
@endif

@if ($user->context === 'organization')
    <h3>{{ __('In progress') }}</h3>
    <x-interpretation name="{{ __('In progress', [], 'en') }}" />
    @forelse ($user->organization->inProgressParticipatingProjects as $project)
        @include('projects.partials.project-and-participating-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    <h3>{{ __('Upcoming') }}</h3>
    <x-interpretation name="{{ __('Upcoming', [], 'en') }}" />
    @forelse ($user->organization->upcomingParticipatingProjects as $project)
        @include('projects.partials.project-and-participating-engagements')
    @empty
        <p>{{ __('No projects found.') }}</p>
    @endforelse

    @if (!$user->organization->completedParticipatingProjects->isEmpty())
        <x-expander level="3" :summary="__('Completed')">
            <x-interpretation name="{{ __('Completed', [], 'en') }}" />
            @forelse ($user->organization->completedParticipatingProjects as $project)
                @include('projects.partials.project-and-participating-engagements')
            @empty
                <p>{{ __('No projects found.') }}</p>
            @endforelse
        </x-expander>
    @endif
@endif
