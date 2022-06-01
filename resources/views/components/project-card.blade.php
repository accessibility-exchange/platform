<x-card class="project">
    <x-slot name="title"><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></x-slot>
    <p>
        <strong>{{ __('Project by :projectable', ['projectable' => $project->projectable->name]) }}</strong><br />
        <span class="weight:semibold">{{ __('Sector:') }}</span> {{ implode(', ', $project->projectable->sectors()->pluck('name')->toArray()) }}
    </p>
    @if($project->started())
        <p><span class="badge">{{ __('In progress') }}</span></p>
    @else
        <p><span class="badge">{{ __('Upcoming') }}</span></p>
    @endif
</x-card>
