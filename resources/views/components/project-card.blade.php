<article class="box card card--project">
    <h3 @if($level !== 3)aria-level="{{ $level }}" @endif><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></h3>
    @if($showRegulatedOrganization)
    <p>{!! __('Accessibility project by :entity', ['regulatedOrganization' => '<strong>' . $project->regulatedOrganization->name . '</strong>']) !!}</p>
    @endif
    @if($project->started())
    <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
    @else
    <p><strong>{{ __('Starting') }}:</strong> {{ $project->start_date->format('F Y') }}</p>
    @endif
</article>
