@props([
    'width' => '1600',
    'height' => '900',
])

<svg {{ $attributes->merge([
    'xmlns' => "http://www.w3.org/2000/svg",
    'class' => "w-full",
    'viewBox' => "0 0 {$width} {$height}",
]) }}>
    <rect fill="currentColor" width="{{ $width }}" height="{{ $height }}" />
</svg>
