<x-card class="project">
    <x-slot name="title"><a
            href="{{ $project->checkStatus('draft') ? localized_route('projects.edit', $project) : localized_route('projects.show', $project) }}">{{ $project->name }}</a>
    </x-slot>
    <p>
        <strong>{{ __('Project by :projectable', ['projectable' => $project->projectable->name]) }}</strong><br />
        <span class="font-semibold">{{ __('Sector:') }}</span>
        {{ implode(', ',$project->projectable->sectors()->pluck('name')->toArray()) }}
    </p>
    <p>
        <span class="badge">{{ $project->status }}</span>
    </p>
</x-card>
