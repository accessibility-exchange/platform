<x-heading :level="$level">{{ __('Access supports available') }}</x-heading>

<ul>
    @foreach ($project->accessSupports as $accessSupport)
    <li>{{ $accessSupport->name }}</li>
    @endforeach
</ul>

<x-heading :level="$level">{{ __('Communication tools used') }}</x-heading>

<ul>
    @foreach ($project->communicationTools as $tool)
    <li>{{ $tool->name }}</li>
    @endforeach
</ul>

<x-heading :level="$level">{{ __('Time-related accomodations') }}</x-heading>

<ul>
    <li>{{ __('Flexibility with deadlines') }}</li>
    <li>{{ __('Flexible and/or frequent breaks provided') }}</li>
</ul>

