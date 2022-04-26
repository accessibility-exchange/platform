<x-app-wide-layout>
    <x-slot name="title">{{ $communityMember->name }}</x-slot>
    <x-slot name="header">
        @if($communityMember->getMedia('picture')->first())
        <img class="float-left"src="{{ $communityMember->getMedia('picture')->first()->getUrl('thumb') }}" alt="{{ $communityMember->picture_alt }}" />
        @else
        <img class="float-left" src="https://source.boringavatars.com/bauhaus/192/{{ $communityMember->name }}?colors=264653,2a9d8f,e9c46a,f4a261,e76f51" alt="{{ $communityMember->name }}" />
        @endif
        <h1 id="community-member">
            {{ $communityMember->name }}
        </h1>
        <div class="meta">
            <p>{{ __('Individual community member') }}</p>
            <p>{{ $communityMember->locality }}, {{ get_region_name($communityMember->region, ["CA"], locale()) }}</p>
            @if($communityMember->pronouns)
            <p>{{ $communityMember->pronouns }}</p>
            @endif
        </div>
        @can('update', $communityMember)
        <form action="{{ localized_route('community-members.update-publication-status', $communityMember) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            @if($communityMember->checkStatus('published'))
            <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish my page')" />
            @else
            <x-hearth-input type="submit" name="publish" :value="__('Publish my page')" />
            @endif
        </form>
        @endcan
    </x-slot>

    <div class="with-sidebar">
        <nav class="secondary" aria-labelledby="community-member">
            <ul role="list">
                <li>
                    <x-nav-link :href="localized_route('community-members.show', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show')">{{ __('About') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('community-members.show-experiences', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-experiences')">{{ __('Experience') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('community-members.show-interests', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-interests')">{{ __('Interests') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('community-members.show-access-needs', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-access-needs')">{{ __('Access needs') }}</x-nav-link>
                </li>
            </ul>
        </nav>

        <div class="stack">
        @if(request()->routeIs(locale() . '.community-members.show'))
            <h2 class="repel">{{ __('About') }} @can('update', $communityMember)<a class="cta secondary" href="{{ localized_route('community-members.edit', $communityMember) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a>@endcan</h2>
            @include('community-members.partials.about')
        @elseif(request()->routeIs(locale() . '.community-members.show-experiences'))
            <h2 class="repel">{{ __('Experiences') }} <a class="cta secondary" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Experiences') . '</span>']) !!}</a></h2>
            @include('community-members.partials.experiences')
        @elseif(request()->routeIs(locale() . '.community-members.show-interests'))
            <h2 class="repel">{{ __('Interests') }} <a class="cta secondary" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests') . '</span>']) !!}</a></h2>
            @include('community-members.partials.interests')
        @elseif(request()->routeIs(locale() . '.community-members.show-access-needs'))
            <h2 class="repel">{{ __('Access needs') }}</h2>
            @include('community-members.partials.access-needs')
        @endif
        </div>
    </div>
</x-app-wide-layout>
