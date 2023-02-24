@props([
    'level' => 2,
    'model' => null,
])

<x-card class="resource" title-class="h4">
    <x-slot name="title"><a href="{{ localized_route('courses.show', $model) }}">{{ $model->title }}</a>
    </x-slot>
    <p><strong>{{ __('Training') }}</strong></p>
    @if ($model->introduction)
        {!! Str::markdown($model->introduction) !!}
    @endif
</x-card>
