<x-card class="project">
    <x-slot name="title">
        <a
            href="{{ $model->checkStatus('draft') ? localized_route('projects.edit', $model) : localized_route('projects.show', $model) }}">{{ $model->name }}</a>
    </x-slot>
    <p>
        <strong>{{ __('Project by :projectable', ['projectable' => $model->projectable->name]) }}</strong><br />
        <span class="font-semibold">{{ __('Sector:') }}</span>
        {{ implode(', ',$model->projectable->sectors()->pluck('name')->toArray()) }}
    </p>
    <p>
        <span class="badge">{{ $model->status }}</span>
    </p>
</x-card>
