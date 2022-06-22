@props([
    'mediaWidth' => 'md:w-1/2',
    'textWidth' => 'md:w-1/2',
    'first' => 'media'
])

<div {{ $attributes->merge(['class' => 'flex flex-row flex-wrap md:flex-nowrap gap-6 items-stretch']) }}>
    @if($first === 'media')
    <div class="w-full {{ $mediaWidth }}">
        {{ $media }}
    </div>
    @endif
    <div class="stack w-full {{ $textWidth }}">
        {{ $slot }}
    </div>
    @if($first === 'text')
    <div class="w-full {{ $mediaWidth }}">
        {{ $media }}
    </div>
    @endif
</div>
