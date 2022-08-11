<h3>{{ __('Team composition') }}</h3>

@if ($project->team_size)
<h4>{{ __('Number of team members') }}</h4>

<p>{{ $project->team_size }}</p>
@endif

@if($project->team_has_disability_or_deaf_lived_experience)
<h4>{{ __('Lived and living experiences') }}</h4>

<p>{{ $project->teamExperience() }}</p>
@endif

@if($project->team_languages)
<h4>{{ __('Languages used') }}</h4>

<ul>
    @foreach($project->team_languages as $language)
    <li>{{ get_language_exonym($language) }}</li>
    @endforeach
</ul>
@endif

@if($project->has_consultant)
<h4>{{ __('Accessibility Consultant') }}</h4>

@if($project->consultant)
<p><a href="{{ localized_route('individuals.show', $project->consultant) }}">{{ $project->consultant->name }}</a></p>
@else
<p>{{ $project->consultant_name }}</p>
@endif

@if($project->consultant_responsibilities)
@markdown
{{ $project->getWrittenTranslation('consultant_responsibilities', $language) }}
@endmarkdown
@endif
@endif

@if($project->team_trainings && count($project->team_trainings))
<h3>{{ __('Training') }}</h3>

<p>{{ __('Members of our team have received the following training:') }}</p>

<ul role="list" class="stack">
    @foreach($project->team_trainings as $training)
    <li class="stack">
        <p class="h4">{{ $training['name'] }}</p>
        <p>
            <strong>
            {{ __('Date') }}<br />
            {{ Illuminate\Support\Carbon::parse($training['date'])->translatedFormat('F Y') }}
            </strong>
        </p>
        <p>
            <strong>
            {{ __('Trainer') }}<br />
            <a href='{{ $training['trainer_url'] }}' rel='external'>{{ $training['trainer_name'] }}</a>
            </strong>
        </p>
    </li>
    @endforeach
</ul>
@endif

@include('projects.partials.questions')
