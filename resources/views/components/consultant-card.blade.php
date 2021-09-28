<article class="card card--consultant flow">
    <header>
        <x-header :level="$level"><a href="{{ localized_route('consultants.show', $consultant) }}">{{ $consultant->name }}</a></x-header>
        <p><strong>{{ __('Individual consultant') }}</strong></p>
    </header>
    {{ $slot ?? '' }}
    {{ $actions ?? '' }}
</article>
