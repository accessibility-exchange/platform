<x-slot name="title">
    {{ __('Module - :title', ['title' => $module->title]) }}
</x-slot>

<x-slot name="header">
    <ol class="breadcrumbs" role="list">
        <li><a href="{{ localized_route('resource-collections.index') }}">{{ __('Resources') }}</a></li>
        <li><a href="{{ localized_route('courses.show', $module->course) }}">{{ $module->course->title }}</a></li>
    </ol>
    <h1 id="module-title">
        {{ $module->title }}
    </h1>
</x-slot>

<div>
    <div class="stack ml-2 mr-2">
        <div class="stack w-full" wire:ignore x-data="vimeoPlayer({
            url: '{{ $module->video }}',
            byline: false,
            dnt: true,
            pip: true,
            portrait: false,
            responsive: true,
            speed: true,
            title: false
        })" @play="Livewire.emit('onPlayerStart')"
            @ended="Livewire.emit('onPlayerEnd')">
        </div>
        <p>{{ $module->introduction }}</p>
    </div>
</div>
