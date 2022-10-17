@props(['title' => null])

<section
    {{ $attributes->merge(['class' => 'manage-section', 'aria-labelledby' => Illuminate\Support\Str::slug($title)]) }}>
    <h3 id="{{ Str::slug($title) }}">{{ $title }}</h3>
    {{ $slot }}
</section>
