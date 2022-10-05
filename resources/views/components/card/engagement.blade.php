@props([
    'level' => 2,
    'model' => null,
])

<article class="box card stack engagement">
    <x-heading class="h4" :level="$level"><a
            href="{{ localized_route('engagements.show', $model) }}">{{ $model->name }}</a>
    </x-heading>
    @if ($model->format)
        <p><strong>{{ $model->display_format }}</strong></p>
    @endif
</article>
