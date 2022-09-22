@props([
    'level' => 2,
    'selected' => false,
    'model' => null,
])

<article class="box card organization">
    @if ($selected)
        <x-heroicon-o-check-circle class="absolute bottom-3 right-3 h-8 w-8 text-green-5" role="presentation"
            aria-hidden="true" />
    @endif
    <div class="stack">
        <x-heading class="h4 mt-0" :level="$level"><a
                href="{{ localized_route('organizations.show', $model) }}">{{ $model->name }}</a></x-heading>
        <p>{{ __('Organization') }}</p>
    </div>
</article>
