<article class="box card card--individual stack">
    <header>
        <x-heading :level="$level"><a
                href="{{ localized_route('individuals.show', $individual) }}">{{ $individual->name }}</a></x-heading>
        <p><strong>{{ __('Individual') }}</strong></p>
    </header>
    {{ $slot ?? '' }}
    {{ $actions ?? '' }}
    @isset($project)
        <details class="match">
            <summary>{!! $individual->projectMatch($project) !!}</summary>
            <ul role="list">
                @foreach ($individual->projectMatches($project) as $match)
                    <li>
                        @if ($match['value'])
                            <x-heroicon-s-check aria-hidden="true" style="margin-bottom: -0.1875em;" width="24"
                                height="24" /><span class="visually-hidden">{{ __('Yes: ') }}</span>
                        @else
                            <x-heroicon-s-x aria-hidden="true" style="margin-bottom: -0.1875em;" width="24"
                                height="24" /><span class="visually-hidden">{{ __('No: ') }}</span>
                        @endif {{ $match['name'] }}
                    </li>
                @endforeach
            </ul>
        </details>
    @endisset
</article>
