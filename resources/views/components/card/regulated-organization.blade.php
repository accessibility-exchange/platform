@props([
    'level' => 2,
    'model' => null,
])

<article class="box card stack regulated-organization">
    <x-heading class="h4" :level="$level"><a
            href="{{ localized_route('regulated-organizations.show', $model) }}">{{ $model->name }}</a>
    </x-heading>
    <p>{{ __('Federally regulated organization') }}</p>
</article>
