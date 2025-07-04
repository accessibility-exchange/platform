@props([
    'level' => 3,
    'model' => null,
])

<x-card class="tool px-0 pb-0" title-class="h3">
    <x-slot name="title">
        <a href="{{ localized_route('tools.show', $model) }}">{{ $model->title }}</a>
    </x-slot>
    @isset($model->description)
        {!! Str::markdown($model->description, config('markdown')) !!}
    @endisset
</x-card>
