<article class="box card stack engagement">
    <x-heading class="h4" :level="$level"><a
            href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a>
    </x-heading>
    <p>{{ __('Engagement') }}</p>
</article>
