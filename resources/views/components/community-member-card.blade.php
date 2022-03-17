<article class="box card card--community-member stack">
    <header>
        <x-heading :level="$level"><a href="{{ localized_route('community-members.show', $communityMember) }}">{{ $communityMember->name }}</a></x-heading>
        <p><strong>{{ __('Individual community member') }}</strong></p>
    </header>
    {{ $slot ?? '' }}
    {{ $actions ?? '' }}
    @isset($project)
    <details class="match">
        <summary>{!! $communityMember->projectMatch($project) !!}</summary>
        <ul role="list">
        @foreach($communityMember->projectMatches($project) as $match)
            <li>
                @if($match['value']) <x-heroicon-s-check width="24" height="24" style="margin-bottom: -0.1875em;" aria-hidden="true" /><span class="visually-hidden">{{ __('Yes: ') }}</span> @else <x-heroicon-s-x width="24" height="24" style="margin-bottom: -0.1875em;" aria-hidden="true" /><span class="visually-hidden">{{ __('No: ') }}</span> @endif {{ $match['name'] }}
        </li>
        @endforeach
        </ul>
    </details>
    @endisset
</article>
