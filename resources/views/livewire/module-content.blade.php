<x-slot name="title">
    {{ __('Module - :title', ['title' => $module->title]) }}
</x-slot>

<x-slot name="header">
    <a href="{{ localized_route('courses.show', $module->course->id) }}">{{ __('Back') }} >
        {{ $module->course->title }}</a>
    <h1 id="module-title">
        {{ $module->title }}
    </h1>
</x-slot>

<div>
    <div class="stack ml-2 mr-2">
        <div class="stack w-full" wire:ignore x-data="vimeoPlayer({
            url: '{{ $module->video[locale()] }}',
            byline: false,
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
