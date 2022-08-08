<h3>{{ __('Project goals') }}</h3>

@markdown
{{ $project->getWrittenTranslation('goals', $language) }}
@endmarkdown

<h3>{{ __('Project impact') }}</h3>

<h4>{{ __('Communities this project hopes to engage and how they will be impacted') }}</h4>

@markdown
{{ $project->getWrittenTranslation('scope', $language) }}
@endmarkdown

<h4>{{ __('Geographical areas this project will impact') }}</h4>

<ul role="list" class="tags">
    @foreach($project->regions as $region)
        <li class="tag">{{ \App\Enums\ProvinceOrTerritory::from($region)->labels()[$region] }}</li>
    @endforeach
</ul>

@if(!$project->impacts->isEmpty())
<h4>{{ __('Areas of your organization this project will impact') }}</h4>

<ul role="list" class="tags">
    @foreach($project->impacts as $impact)
    <li class="tag">{{ $impact->name }}</li>
    @endforeach
</ul>
@endif

@if($project->out_of_scope)
<h4>{{ __('Not in this project') }}</h4>

@markdown
{{ $project->getWrittenTranslation('out_of_scope', $language) }}
@endmarkdown
@endif

<h3>{{ __('Project timeframe') }}</h3>

<div class="flex flex-col md:flex-row gap-5 w-full">
    <div class="md:w-1/2">
    <h4>{{ __('Project start date') }}</h4>
    <p>{{ $project->start_date->translatedFormat('F j, Y') }}</p>
    </div>

    <div class="md:w-1/2">
    <h4>{{ __('Project end date') }}</h4>
    <p>{{ $project->end_date->translatedFormat('F j, Y') }}</p>
    </div>
</div>

@if($project->outcomes)
<h3>{{ __('Project outcome') }}</h3>

<h4>{{ __('Tangible outcomes of this project') }}</h4>

@markdown
{{ $project->getWrittenTranslation('outcomes', $language) }}
@endmarkdown
@endif

<h4>{{ __('Project reports') }}</h4>

@if($project->public_outcomes)
<p>{{ __('Yes, project reports will be publicly available.') }}</p>
@else
<p>{{ __('No, project reports will not be publicly available.') }}</p>
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

@include('projects.partials.questions')
