<h3>{{ __('Team composition') }}</h3>

@if ($project->team_size)
<h4>{{ __('Number of team members') }}</h4>

<p>{{ $project->team_size }}</p>
@endif

@if($project->team_has_disability_or_deaf_lived_experience || $project->team_has_other_lived_experience)
<h4>{{ __('Team lived experience') }}</h4>

<p>{{ $project->teamExperience() }}</p>
@endif

@if($project->team_languages)
<h4>{{ __('Team languages') }}</h4>

<ul>
    @foreach($project->team_languages as $language)
    <li>{{ get_language_exonym($language) }}</li>
    @endforeach
</ul>
@endif

@if($project->has_consultant)
<h4>{{ __('Accessibility consultant') }}</h4>

@if($project->accessibilityConsultant)
<p><a href="{{ localized_route('community-members.show', $project->accessibilityConsultant) }}">{{ $project->accessibilityConsultant->name }}</a></p>
@else
<p>{{ $project->consultant_name }}</p>
@endif

@if($project->consultant_responsibilities)
<x-markdown class="stack">{{ $project->consultant_responsibilities }}</x-markdown>
@endif
@endif

@if($project->team_trainings && count($project->team_trainings))
<h3>{{ __('Trainings') }}</h3>

<p>{{ __('Members of our team have received the following trainings:') }}</p>

<ul role="list" class="stack">
    @foreach($project->team_trainings as $training)
    <li class="stack">
        <p><strong>{{ $training['name'] }}</strong></p>
        <p>
            {!! __('Conducted by :trainer', ['trainer' => "<a href='{$training['trainer_url']}' rel='external'>{$training['trainer_name']}</a>"]) !!}<br />
            {{ __('Date: :date', ['date' => $training['date']]) }}
        </p>
    </li>
    @endforeach
</ul>
@endif
