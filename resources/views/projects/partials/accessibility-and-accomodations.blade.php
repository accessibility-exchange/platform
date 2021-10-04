<x-header :level="$level">{{ __('Access supports available') }}</x-header>

<ul>
    @foreach ($project->accessSupports as $accessSupport)
    <li>{{ $accessSupport->name }}</li>
    @endforeach
</ul>

<x-header :level="$level">{{ __('Communication tools used') }}</x-header>

<ul>
    @foreach ($project->communicationTools as $tool)
    <li>{{ $tool->name }}</li>
    @endforeach
</ul>

<x-header :level="$level">{{ __('Time-related accomodations') }}</x-header>

<ul>
    <li>{{ __('Flexibility with deadlines') }}</li>
    <li>{{ __('Flexible and/or frequent breaks provided') }}</li>
</ul>

