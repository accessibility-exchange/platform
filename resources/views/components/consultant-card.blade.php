<article class="card card--consultant flow">
    <header>
        <x-header :level="$level"><a href="{{ localized_route('consultants.show', $consultant) }}">{{ $consultant->name }}</a></x-header>
        <p><strong>{{ __('Individual consultant') }}</strong></p>
    </header>
    {{ $slot ?? '' }}
    {{ $actions ?? '' }}
    @isset($project)
    <details class="match">
        <summary>{!! __('Matches <strong>:howmuch</strong> project criteria', ['howmuch' => 'some']) !!}</summary>
        <ul role="list">
        @foreach($consultant->projectMatches($project) as $match)
            <li>
                @if($match['value']) <x-heroicon-s-check width="24" height="24" style="margin-bottom: -0.1875em;" aria-hidden="true" /> @else <x-heroicon-s-x width="24" height="24" style="margin-bottom: -0.1875em;" aria-hidden="true" /><span class="visually-hidden">{{ __('Not') }}</span> @endif {{ $match['name'] }}
        </li>
        @endforeach
        </ul>
    </details>
    @endisset
</article>
