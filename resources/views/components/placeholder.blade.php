@props([
    'width' => '1600',
    'height' => '900',
    'fill' => 'currentColor'
])

<svg xmlns="http://www.w3.org/2000/svg" width="{{ $width }}" height="{{ $height }}" viewBox="0 0 {{ $width }} {{ $height }}">
    <rect fill="{{ $fill }}" width="{{ $width }}" height="{{ $height }}"/>
</svg>
