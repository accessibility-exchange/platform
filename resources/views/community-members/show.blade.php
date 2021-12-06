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
        @if($communityMember->checkStatus('published'))
        <form action="{{ localized_route('community-members.update-publication-status', $communityMember) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish my page')" />
        </form>
        @endif
        @endcan
    </x-slot>


    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="community-member">
            <ul role="list">
                <x-nav-link :href="localized_route('community-members.show', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show')">{{ __('About') }}</x-nav-link>
                <x-nav-link :href="localized_route('community-members.show-interests-and-goals', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-interests-and-goals')">{{ __('Interests and goals') }}</x-nav-link>
                @can('viewPersonalDetails', $communityMember)
                <x-nav-link :href="localized_route('community-members.show-lived-experience', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-lived-experience')">{{ __('Lived experience') }}</x-nav-link>
                @endcan
                <x-nav-link :href="localized_route('community-members.show-professional-experience', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-professional-experience')">{{ __('Professional experience') }}</x-nav-link>
                @can('viewPersonalDetails', $communityMember)
                <x-nav-link :href="localized_route('community-members.show-access-needs', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-access-needs')">{{ __('Access needs') }}</x-nav-link>
                @endcan
            </ul>
        </nav>

        <div class="flow">
        @if(request()->routeIs(locale() . '.community-members.show'))
            <h2>{{ __('About :name', ['name' => $communityMember->firstName()]) }}</h2>
            @can('update', $communityMember)
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="{{ localized_route('community-members.edit', $communityMember) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a></p>
            @endcan

            @if($communityMember->bio)
            <x-markdown class="flow">{{ $communityMember->bio }}</x-markdown>
            @endif

            @if($communityMember->links)
            <h3>{{ __(':name’s links', ['name' => $communityMember->firstName()]) }}</h3>
            <ul>
                @foreach($communityMember->links as $key => $link)
                <li><a href="{{ $link }}" rel="external">{{ $key }}</a></li>
                @endforeach
            </ul>
            @endif

            @if($communityMember->creator === 'other')
            <p><em>{{ __('This page was created by :creator, :name’s :relationship.', ['creator' => $communityMember->creator_name, 'name' => $communityMember->firstName(), 'relationship' => $communityMember->creator_relationship]) }}</em></p>
            @endif
        @elseif(request()->routeIs(locale() . '.community-members.show-interests-and-goals'))
            <h2>{{ __('Interests and goals') }}</h2>
            @can('update', $communityMember)
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests and goals') . '</span>']) !!}</a></p>
            @endcan
            @include('community-members.boilerplate.interests-and-goals', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.community-members.show-lived-experience'))
            <h2>{{ __('Lived experience') }}</h2>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Lived experience') . '</span>']) !!}</a></p>
            @include('community-members.boilerplate.lived-experience', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.community-members.show-professional-experience'))
            <h2>{{ __('Professional experience') }}</h2>
            @can('update', $communityMember)
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Professional experience') . '</span>']) !!}</a></p>
            @endcan
            @include('community-members.boilerplate.professional-experience', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.community-members.show-access-needs'))
            <h2>{{ __('Access needs') }}</h2>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Access needs') . '</span>']) !!}</a></p>
            @include('community-members.boilerplate.access-needs', ['level' => 3])
        @endif
        </div>
    </div>
</x-app-wide-layout>
