<x-card class="engagement" title-class="h4">
    <x-slot name="title">
        <a href="{{ localized_route('engagements.show', $model) }}">{{ $model->name }}
            <x-heroicon-m-chevron-right class="absolute bottom-1/2 right-5 h-7 w-7" role="presentation" aria-hidden="true"
                fill="currentColor" />
        </a>
    </x-slot>

    @if ($model->format)
        <p><strong>{{ $model->display_format }}</strong></p>
    @endif

    <div class="meta">
        @if (in_array($model->format, ['workshop', 'focus-group', 'other-sync']))
            <p><span class="font-semibold">{{ __('Meeting dates') }}</span> {{ $model->meeting_dates }}</p>
        @endif

        @if (in_array($model->format, ['workshop', 'interviews', 'focus-group', 'other-sync']))
            <p><span class="font-semibold">{{ __('Ways to participate') }}</span>
                {{ implode(', ', $model->display_meeting_types) }}
            </p>
        @endif

        @if ($model->complete_by_date)
            <p><span class="font-semibold">{{ __('Due by') }}</span> {{ $model->complete_by_date->isoFormat('LLL') }}
            </p>
        @endif
    </div>

    <p class="flex gap-2">
        @if ($model->recruitment === 'open-call')
            <span class="badge border-0 bg-lavender-2">{{ __('Seeking participants') }}</span>
        @endif
        @if ($model->seeking_community_connector)
            <span class="badge border-0 bg-yellow-2">{{ __('Seeking community connector') }}</span>
        @endif
        <span @class(['badge', 'bg-turquoise-1 border-0' => $model->paid])>{{ $model->paid ? __('Paid') : __('Volunteer') }}</span>
    </p>
</x-card>
