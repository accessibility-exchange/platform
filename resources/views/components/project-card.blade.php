<article class="card card--project flow">
    <h3 @if($level !== 3)aria-level="{{ $level }}" @endif><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></h3>
    @if($showEntity)
    <p>{!! __('project.initiated_by', ['entity' => '<strong>' . $project->entity->name . '</strong>']) !!}</p>
    @endif
</article>
