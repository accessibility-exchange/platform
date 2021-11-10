<x-header :level="$level">{{ __('Goals for consultation') }}</x-header>

<x-markdown class="flow">{{ $project->goals }}</x-markdown>

<x-header :level="$level">{{ __('Project impact') }}</x-header>

<x-header :level="$level + 1">{{ __('Who will this project impact?') }}</x-header>

<x-markdown class="flow">{{ $project->impact }}</x-markdown>

<x-header :level="$level + 1">{{ __('What areas of your organization will this project impact?') }}</x-header>

<ul role="list" class="tags">
    @foreach($project->impacts as $impact)
    <li>{{ $impact->name }}</li>
    @endforeach
</ul>

<x-header :level="$level + 1">{{ __('What is this project not going to do?') }}</x-header>

<x-markdown class="flow">{{ $project->out_of_scope }}</x-markdown>

<x-header :level="$level">{{ __('Ways of consulting') }}</x-header>

<ul>
    @foreach($project->consultingMethods as $method)
    <li>{{ $method->name }}</li>
    @endforeach
</ul>

@if($project->virtual_consultation)
<p><em>{{ __('Virtual consultation supported.') }}</em></p>
@endif

<x-header :level="$level">{{ __('Timeline') }}</x-header>

<x-markdown class="flow">{{ $project->timeline }}</x-markdown>

<x-header :level="$level">{{ __('Payment') }}</x-header>

<x-header :level="$level + 1">{{ __('Timeline for payment') }}</x-header>

<x-markdown class="flow">{{ $project->payment_terms }}</x-markdown>

<x-header :level="$level + 1">{{ __('Payment types') }}</x-header>

<ul role="list" class="tags">
    @foreach($project->paymentMethods as $method)
    <li>{{ $method->name }}</li>
    @endforeach
</ul>

@if($project->payment_negotiable)
<p>{{ __('Open to negotiating different types of payment') }}</p>
@endif
