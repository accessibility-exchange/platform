<h3>{{ __('Project goals') }}</h3>

{!! Str::markdown($project->getWrittenTranslation('goals', $language)) !!}

<h3>{{ __('Engagements') }}</h3>

@if (!$engagements->isEmpty())
    <div class="grid">
        @foreach ($engagements as $engagement)
            <x-card.engagement :model="$engagement" :level="5" />
        @endforeach
    </div>
@else
    <p>{{ __('No upcoming engagements.') }}</p>
@endif
<p><a href="{{ localized_route('projects.show-engagements', $project) }}">{{ __('Go to all engagements') }}</a></p>

<h3>{{ __('Project impact') }}</h3>

<h4>{{ __('How the disability and Deaf communities will be impacted by the outcomes of this project') }}</h4>

{!! Str::markdown($project->getWrittenTranslation('scope', $language)) !!}

<h4>{{ __('Geographical areas this project will impact') }}</h4>

<ul class="tags" role="list">
    @foreach ($project->regions as $region)
        <li class="tag">{{ \App\Enums\ProvinceOrTerritory::from($region)->labels()[$region] }}</li>
    @endforeach
</ul>

@if (!$project->impacts->isEmpty())
    <h4>{{ __('Areas of your organization this project will impact') }}</h4>

    <ul class="tags" role="list">
        @foreach ($project->impacts as $impact)
            <li class="tag">{{ $impact->name }}</li>
        @endforeach
    </ul>
@endif

@if ($project->out_of_scope)
    <h4>{{ __('Not in this project') }}</h4>

    {!! Str::markdown($project->getWrittenTranslation('out_of_scope', $language)) !!}
@endif

<h3>{{ __('Project timeframe') }}</h3>

<div class="flex w-full flex-col gap-5 md:flex-row">
    <div class="md:w-1/2">
        <h4>{{ __('Project start date') }}</h4>
        <p>{{ $project->start_date->translatedFormat('F j, Y') }}</p>
    </div>

    <div class="md:w-1/2">
        <h4>{{ __('Project end date') }}</h4>
        <p>{{ $project->end_date->translatedFormat('F j, Y') }}</p>
    </div>
</div>

<h3>{{ __('Project outcome') }}</h3>

@if ($project->outcome_analysis || $project->outcome_analysis_other)
    <h4>{{ __('Who’s responsible for going through results and producing an outcome') }}</h4>

    <ul>
        @foreach ($project->outcome_analysis ?? [] as $outcome_analysis)
            <li>{{ \App\Enums\OutcomeAnalyzer::labels()[$outcome_analysis] }}</li>
        @endforeach
        @if ($project->outcome_analysis_other)
            <li>{{ $project->outcome_analysis_other }}</li>
        @endif
    </ul>
@endif

@if ($project->outcomes)
    <h4>{{ __('Tangible outcomes of this project') }}</h4>

    {!! Str::markdown($project->getWrittenTranslation('outcomes', $language)) !!}
@endif

<h4>{{ __('Project reports') }}</h4>

@if ($project->public_outcomes)
    <p>{{ __('Yes, project reports will be publicly available.') }}</p>
@else
    <p>{{ __('No, project reports will not be publicly available.') }}</p>
@endif

@include('projects.partials.questions')
