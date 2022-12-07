@props([
    'level' => 2,
    'model' => null,
])

<x-card class="resource" title-class="h4">
    <x-slot name="title"><a href="{{ localized_route('resources.show', $model) }}">{{ $model->title }}</a>
    </x-slot>
    <p><strong>{{ $model->contentType?->name ?? Str::ucfirst(__('Resource')) }}</strong></p>
    @if ($model->summary)
        {!! Str::markdown($model->summary) !!}
    @endif
</x-card>
