<x-heading :level="$level">{{ __('Goals for consultation') }}</x-heading>

<x-markdown class="stack">{{ $project->goals }}</x-markdown>

<x-heading :level="$level">{{ __('Project impact') }}</x-heading>

<x-heading :level="$level + 1">{{ __('Who will this project impact?') }}</x-heading>

<x-markdown class="stack">{{ $project->impact }}</x-markdown>

<x-heading :level="$level + 1">{{ __('What areas of your organization will this project impact?') }}</x-heading>

<ul role="list" class="tags">
    @foreach($project->impacts as $impact)
    <li class="tag">{{ $impact->name }}</li>
    @endforeach
</ul>

<x-heading :level="$level + 1">{{ __('What is this project not going to do?') }}</x-heading>

<x-markdown class="stack">{{ $project->out_of_scope }}</x-markdown>

<x-heading :level="$level">{{ __('Ways of consulting') }}</x-heading>

<ul>
    @foreach($project->consultingMethods as $method)
    <li>{{ $method->name }}</li>
    @endforeach
</ul>

@if($project->virtual_consultation)
<p><em>{{ __('Virtual consultation supported.') }}</em></p>
@endif

<x-heading :level="$level">{{ __('Timeline') }}</x-heading>

<x-markdown class="stack">{{ $project->timeline }}</x-markdown>

<x-heading :level="$level">{{ __('Payment') }}</x-heading>

<x-heading :level="$level + 1">{{ __('Timeline for payment') }}</x-heading>

<x-markdown class="stack">{{ $project->payment_terms }}</x-markdown>

<x-heading :level="$level + 1">{{ __('Payment types') }}</x-heading>

<ul role="list" class="tags">
    @foreach($project->paymentMethods as $method)
    <li class="tag">{{ $method->name }}</li>
    @endforeach
</ul>

@if($project->payment_negotiable)
<p>{{ __('Open to negotiating different types of payment') }}</p>
@endif
