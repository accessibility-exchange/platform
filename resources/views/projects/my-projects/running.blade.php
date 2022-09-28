<div class="flex items-center justify-between">
    <h2>{{ __('Projects I am running') }}</h2>
    @if (!$projectable->draftProjects->isEmpty() ||
        !$projectable->inProgressProjects->isEmpty() ||
        !$projectable->upcomingProjects->isEmpty() ||
        !$projectable->completedProjects->isEmpty())
        <a class="cta"
            href="{{ $user->projectable()->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create new project') }}</a>
    @endif
</div>

@if ($projectable->draftProjects->isEmpty() &&
    $projectable->inProgressProjects->isEmpty() &&
    $projectable->upcomingProjects->isEmpty() &&
    $projectable->completedProjects->isEmpty())
    <div class="box stack bg-grey-2">
        <p>{{ __('It seems as though you have not created any projects yet.') }}</p>
        <p><a class="cta"
                href="{{ $user->projectable()->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create new project') }}</a>
        </p>
    </div>
@endif

@if (!$projectable->draftProjects->isEmpty())
    <h3>{{ __('Draft') }}</h3>
    @foreach ($projectable->draftProjects as $project)
        <x-card.project :project="$project" :level="4" />
    @endforeach
@endif

@if (!$projectable->inProgressProjects->isEmpty())
    <h3>{{ __('In progress') }}</h3>
    @foreach ($projectable->inProgressProjects as $project)
        <x-card.project :project="$project" :level="4" />
    @endforeach
@endif

@if (!$projectable->upcomingProjects->isEmpty())
    <h3>{{ __('In progress') }}</h3>
    @foreach ($projectable->upcomingProjects as $project)
        <x-card.project :project="$project" :level="4" />
    @endforeach
@endif

@if (!$projectable->completedProjects->isEmpty())
    <x-expander level="3" :summary="__('Completed')">
        @forelse ($projectable->completedProjects as $project)
            <x-card.project :project="$project" :level="4" />
        @empty
            <p>{{ __('No projects found.') }}</p>
        @endforelse
    </x-expander>
@endif
