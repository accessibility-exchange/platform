<section
    {{ $attributes->merge(['class' => 'space-y-6 rounded bg-white px-6 py-8 shadow-md', 'aria-labelledby' => Illuminate\Support\Str::slug($title)]) }}>
    <h3 id="{{ Str::slug($title) }}">{{ $title }}</h3>
    {{ $slot }}
</section>
