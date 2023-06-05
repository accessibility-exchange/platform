@props([
    'level' => 2,
    'model' => null,
])

<x-card class="resource" title-class="h4">
    <x-slot name="title"><a href="{{ localized_route('resources.show', $model) }}">{{ $model->title }}</a>
    </x-slot>
    <p><strong>{{ $model->contentType?->name ?? __('Resource') }}</strong> {{ __('by') }} <strong>
            @if ($model->authorOrganization)
                {{ $model->authorOrganization->name }}
            @else
                {{ $model->author }}
            @endif
        </strong></p>
    <p class="font-semibold">{{ __('Languages') }}:
        {{ implode(', ', Arr::map(array_keys($model->getTranslations('url')), fn($code) => get_language_exonym($code, null, true, true))) }}
    </p>
    @if ($model->summary)
        {!! Str::markdown($model->summary, SAFE_MARKDOWN_OPTIONS) !!}
    @endif
</x-card>
