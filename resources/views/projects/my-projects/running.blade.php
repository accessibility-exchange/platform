@if (
    $projectable &&
        $projectable->draftProjects->isEmpty() &&
        $projectable->inProgressProjects->isEmpty() &&
        $projectable->upcomingProjects->isEmpty() &&
        $projectable->completedProjects->isEmpty())
    <div class="box stack">
        <p>{{ __('It seems as though you have not created any projects yet.') }}</p>

        @can('update', $projectable)
            <p><a class="cta"
                    href="{{ $user->projectable->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create new project') }}</a>
            </p>
        @endcan
    </div>
@endif

@if ($projectable && !$projectable->draftProjects->isEmpty())
    <h2>{{ __('Draft') }}</h2>
    <x-interpretation name="{{ __('Draft', [], 'en') }}" />
    @foreach ($projectable->draftProjects as $project)
        @include('projects.partials.project-and-engagements')
    @endforeach
@endif

@if ($projectable && !$projectable->inProgressProjects->isEmpty())
    <h2>{{ __('In progress') }}</h2>
    <x-interpretation name="{{ __('In progress', [], 'en') }}" />
    @foreach ($projectable->inProgressProjects as $project)
        @include('projects.partials.project-and-engagements')
    @endforeach
@endif

@if ($projectable && !$projectable->upcomingProjects->isEmpty())
    <h2>{{ __('Upcoming') }}</h2>
    <x-interpretation name="{{ __('Upcoming', [], 'en') }}" />
    @foreach ($projectable->upcomingProjects as $project)
        @include('projects.partials.project-and-engagements')
    @endforeach
@endif

@if ($projectable && !$projectable->completedProjects->isEmpty())
    <x-expander level="3" :summary="__('Completed')">
        <x-interpretation name="{{ __('Completed', [], 'en') }}" />
        @forelse ($projectable->completedProjects as $project)
            @include('projects.partials.project-and-engagements')
        @empty
            <p>{{ __('No projects found.') }}</p>
        @endforelse
    </x-expander>
@endif
