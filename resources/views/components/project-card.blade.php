<article class="box card card--project">
    <h3 @if($level !== 3)aria-level="{{ $level }}" @endif><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></h3>
    @if($showEntity)
    <p>{!! __('Accessibility project by :entity', ['entity' => '<strong>' . $project->entity->name . '</strong>']) !!}</p>
    @endif
    <p><strong>{{ __('Status') }}:</strong> @if($project->step())
        {{ $project->step() }}
    @endif
    </p>
    @if($project->started())
    <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
    @else
    <p><strong>{{ __('Starting') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
    @endif
    @if($project->completed())
    <p><strong>{{ __('Completed') }}:</strong> {{ $project->end_date->format('F Y') }}</p>
    @endif
</article>
