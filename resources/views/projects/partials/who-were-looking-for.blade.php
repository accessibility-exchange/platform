<x-heading :level="$level">{{ __('Relationship to entity') }}</x-heading>

<ul role="list" class="tags">
    @if($project->existing_clients)
    <li>{{ __('Existing clients') }}</li>
    @endif
    @if($project->prospective_clients)
    <li>{{ __('Prospective clients') }}</li>
    @endif
    @if($project->employees)
    <li>{{ __('Employees') }}</li>
    @endif
</ul>

<x-heading :level="$level">{{ __('Representation') }}</x-heading>

<p>{{ __('Cross disability & intersectional') }}</p>

@if($project->communities)
<x-heading :level="$level + 1">{{ __('Priority outreach') }}</x-heading>

<ul>
    @foreach ($project->communities as $community)
    <li>{{ $community->name }}</li>
    @endforeach
</ul>

@if($project->priority_outreach)
<x-markdown class="flow">{{ $project->priority_outreach }}</x-markdown>
@endif
@endif

<x-heading :level="$level">{{ __('Where') }}</x-heading>

<p>
    @if($project->locality){{ $project->locality }}, @endif
    @if($project->regions && count($project->regions) === 13)
    {{ __('Any province or territory') }}
    @endif
</p>

<x-heading :level="$level">{{ __('How many') }}</x-heading>

<p>
    @if($project->min < $project->max)
    {{ $project->min }}–{{ $project->max }}
    @else
    {{ $project->max }}
    @endif
</p>

<x-heading :level="$level">{{ __('Anything else?') }}</x-heading>

<x-markdown class="flow">{{ $project->anything_else }}</x-markdown>
