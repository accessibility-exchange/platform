<x-header :level="$level">{{ __('Relationship to entity') }}</x-header>

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

<x-header :level="$level">{{ __('Representation') }}</x-header>

<p>{{ __('Cross disability & intersectional') }}</p>

<x-header :level="$level">{{ __('Where') }}</x-header>

<p>
    @if($project->locality){{ $project->locality }}, @endif
    @if(count($project->regions) === 13)
    {{ _('Any province or territory') }}
    @endif
</p>

<x-header :level="$level">{{ __('How many') }}</x-header>

<p>
    @if($project->min < $project->max)
    {{ $project->min }}–{{ $project->max }}
    @else
    {{ $project->max }}
    @endif
</p>

<x-header :level="$level">{{ __('Anything else?') }}</x-header>

<x-markdown class="flow">{{ $project->anything_else }}</x-markdown>
