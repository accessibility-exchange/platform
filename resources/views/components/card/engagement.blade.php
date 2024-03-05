@props([
    'byline' => false,
    'level' => 2,
    'model' => null,
])

<x-card class="engagement" title-class="h4">
    <x-slot name="title">
        <a
            href="@can('update', $model){{ localized_route('engagements.manage', $model) }}@else{{ localized_route('engagements.show', $model) }}@endcan">{{ $model->name }}
            @svg('heroicon-s-chevron-right', 'icon--xl absolute bottom-1/2 right-5')
        </a>
    </x-slot>

    @if ($model->format)
        <p><strong>

                @if ($byline)
                    {{ __(':format by :projectable', [
                        'format' => $model->display_format,
                        'projectable' => $model->project->projectable->name,
                    ]) }}
                @else
                    {{ $model->display_format }}
                @endif
            </strong></p>
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

    @if ($model->signup_by_date)
        <p class="font-semibold">
            @if ($model->signup_by_date >= now())
                {{ __('Sign up open until :date', ['date' => $model->signup_by_date->isoFormat('LL')]) }}
            @else
                {{ __('Sign up closed on :date', ['date' => $model->signup_by_date->isoFormat('LL')]) }}
            @endif
        </p>
    @endif

    <p class="flex flex-wrap gap-2">
        @if ($model->recruitment === 'open-call')
            <span class="badge badge--lavender">{{ __('Seeking participants') }}</span>
        @endif
        @if ($model->extra_attributes->get('seeking_community_connector'))
            <span class="badge badge--yellow">{{ __('Seeking community connector') }}</span>
        @endif
        <span @class(['badge', 'badge--green' => $model->paid])>{{ $model->paid ? __('Paid') : __('Volunteer') }}</span>
    </p>
</x-card>
