<h3>{{ __('Project goals') }}</h3>

@markdown
{{ $project->goals }}
@endmarkdown

<h3>{{ __('Project impact') }}</h3>

<h4>{{ __('Who will this project impact?') }}</h4>

@markdown
{{ $project->scope }}
@endmarkdown

@if(!$project->impacts->isEmpty())
<h4>{{ __('What areas of your organization will this project impact?') }}</h4>

<ul role="list" class="tags">
    @foreach($project->impacts as $impact)
    <li class="tag">{{ $impact->name }}</li>
    @endforeach
</ul>
@endif

@if($project->out_of_scope)
<h4>{{ __('What is out of scope?') }}</h4>

@markdown
{{ $project->out_of_scope }}
@endmarkdown
@endif

<h3>{{ __('Project timeframe') }}</h3>

<p>{!! $project->timeframe() !!}</p>

@if($project->outcomes)
<h3>{{ __('Project outcomes') }}</h3>

<h4>{{ __('What are the tangible outcomes of this project?') }}</h4>

@markdown
{{ $project->outcomes }}
@endmarkdown
@endif

<h3>{{ __('Engagements') }}</h3>

<h4>{{ __('Upcoming engagements') }}</h4>
@if(!$project->engagements->isEmpty())
<div class="grid">
    @foreach($project->engagements as $engagement)
    <x-engagement-card :engagement="$engagement" :level="5" />
    @endforeach
</div>
@else
<p>{{ __('No upcoming engagements.') }}</p>
@endif
<p><a href="{{ localized_route('projects.show-engagements', $project) }}">Go to all engagements</a></p>
