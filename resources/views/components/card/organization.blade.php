@props([
    'level' => 2,
    'model' => null,
])

<article class="box card organization">
    <div class="stack">
        <x-heading class="h4 mt-0" :level="$level"><a
                href="{{ localized_route('organizations.show', $model) }}">{{ $model->name }}</a></x-heading>
        <p>{{ __('Organization') }}</p>
    </div>
</article>
