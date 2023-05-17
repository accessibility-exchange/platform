<x-card class="project">
    <x-slot name="title">
        @can('update', $model->projectable)
            <a
                href="{{ $model->checkStatus('draft') ? localized_route('projects.edit', $model) : localized_route('projects.manage', $model) }}">{{ $model->name }}</a>
        @else
            <a href="{{ localized_route('projects.show', $model) }}">{{ $model->name }}</a>
        @endcan
    </x-slot>
    <p>
        <strong>{{ __('Project by :projectable', ['projectable' => $model->projectable->name]) }}</strong><br />
        @if ($model->projectable->sectors()->count())
            <span class="font-semibold">{{ __('Sector:') }}</span>
            {{ implode(', ',$model->projectable->sectors()->pluck('name')->toArray()) }}
        @endif
    </p>
    <p class="flex flex-wrap gap-3">
        <span class="badge">{{ $model->status }}</span>
        @can('update', $model->projectable)
            @if ($model->allEngagements->filter(fn($engagement) => $engagement->extra_attributes->get('seeking_community_connector') == true)->count())
                <span class="badge badge--yellow">{{ __('Seeking Community Connector') }}</span>
            @endif
            @if ($model->allEngagements->filter(fn($engagement) => $engagement->recruitment === 'open-call')->count())
                <span class="badge badge--lavender">{{ __('Seeking Participants') }}</span>
            @endif
        @else
            @if ($model->engagements->filter(fn($engagement) => $engagement->extra_attributes->get('seeking_community_connector') == true)->count())
                <span class="badge badge--yellow">{{ __('Seeking Community Connector') }}</span>
            @endif
            @if ($model->engagements->filter(fn($engagement) => $engagement->recruitment === 'open-call')->count())
                <span class="badge badge--lavender">{{ __('Seeking Participants') }}</span>
            @endif
            @endif
        </p>
    </x-card>
