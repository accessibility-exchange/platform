<h3>{{ __('About the project team') }}</h3>

@if ($project->team_size)
    <h4>{{ __('Number of team members') }}</h4>

    <p>{{ $project->team_size }}</p>
@endif

@if ($project->team_has_disability_or_deaf_lived_experience)
    <h4>{{ __('Lived and living experiences') }}</h4>

    <p>{{ $project->teamExperience() }}</p>
@endif

@if ($project->has_consultant)
    <h4>{{ __('Accessibility Consultant') }}</h4>

    @if ($project->consultant)
        <p>
            <a
                href="{{ localized_route('individuals.show', $project->consultant) }}">{{ $project->consultant->name }}</a>
        </p>
    @else
        <p>{{ $project->consultant_name }}</p>
    @endif

    @if ($project->consultant_responsibilities)
        {{ $project->getWrittenTranslation('consultant_responsibilities', $language) }}
    @endif
@endif

@if ($project->team_trainings && count($project->team_trainings))
    <h3>{{ __('Training') }}</h3>

    <p>{{ __('Members of our team have received the following training:') }}</p>

    <ul class="stack" role="list">
        @foreach ($project->team_trainings as $training)
            <li class="stack">
                <p class="h4">{{ $training['name'] }}</p>
                <p>
                    <strong>
                        {{ __('Date') }}</strong><br />
                    {{ Illuminate\Support\Carbon::parse($training['date'])->translatedFormat('F Y') }}

                </p>
                <p>
                    <strong>
                        {{ __('Trainer') }}</strong><br />
                    <a href='{{ $training['trainer_url'] }}' rel='external'>{{ $training['trainer_name'] }}</a>

                </p>
            </li>
        @endforeach
    </ul>
@endif

@include('projects.partials.questions')
