<x-card class="project">
    <x-slot name="title"><a
            href="{{ $project->checkStatus('draft') ? localized_route('projects.edit', $project) : localized_route('projects.show', $project) }}">{{ $project->name }}</a>
    </x-slot>
    <p>
        <strong>{{ __('Project by :projectable', ['projectable' => $project->projectable->name]) }}</strong><br />
        <span class="weight:semibold">{{ __('Sector:') }}</span>
        {{ implode(', ',$project->projectable->sectors()->pluck('name')->toArray()) }}
    </p>
    <p>
        @if ($project->checkStatus('draft'))
            <span class="badge">{{ __('Draft') }}</span>
        @elseif($project->started())
            <span class="badge">{{ __('In progress') }}</span>
        @elseif($project->finished())
            <span class="badge">{{ __('Completed') }}</span>
        @else
            <span class="badge">{{ __('Upcoming') }}</span>
        @endif
    </p>
</x-card>
