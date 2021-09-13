<article class="card card--project">
    <h3 @if($level !== 3)aria-level="{{ $level }}" @endif><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></h3>
    @if($showEntity)
    <p>{!! __('project.project_by', ['entity' => '<strong>' . $project->entity->name . '</strong>']) !!}</p>
    @endif
    <p><strong>{{ __('project.status_label') }}:</strong> @if($project->status)
        {{ $project->status }}
    @elseif($project->started() && !$project->completed())
        {{ __('In progress') }}
    @elseif($project->completed())
        {{ __('Completed') }}
    @endif
    </p>
    @if($project->started())
    <p><strong>{{ __('project.started_label') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
    @else
    <p><strong>{{ __('project.starting_label') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
    @endif
    @if($project->completed())
    <p><strong>{{ __('project.completed_label') }}:</strong> {{ $project->end_date->format('F Y') }}</p>
    @endif
</article>
