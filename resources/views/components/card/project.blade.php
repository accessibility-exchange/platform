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
    <p class="flex flex-wrap gap-3">
        <span class="badge">{{ $model->status }}</span>
        @if ($model->allEngagements->filter(fn($engagement) => $engagement->extra_attributes->get('seeking_community_connector') == true)->count())
            <span class="badge badge--yellow">{{ __('Seeking Community Connector') }}</span>
        @endif
        @if ($model->allEngagements->filter(fn($engagement) => $engagement->recruitment === 'open-call')->count())
            <span class="badge badge--lavender">{{ __('Seeking Participants') }}</span>
        @endif
    </p>
</x-card>
