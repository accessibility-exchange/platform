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


    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="community-member">
            <ul role="list">
                <x-nav-link :href="localized_route('community-members.show', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show')">{{ __('About') }}</x-nav-link>
                <x-nav-link :href="localized_route('community-members.show-interests', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-interests')">{{ __('Interests') }}</x-nav-link>
                @can('viewPersonalDetails', $communityMember)
                <x-nav-link :href="localized_route('community-members.show-experiences', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-experiences')">{{ __('Experience') }}</x-nav-link>
                @endcan
                @can('viewPersonalDetails', $communityMember)
                <x-nav-link :href="localized_route('community-members.show-access-needs', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-access-needs')">{{ __('Access needs') }}</x-nav-link>
                @endcan
            </ul>
        </nav>

        <div class="stack">
        @if(request()->routeIs(locale() . '.community-members.show'))
            <h2>{{ __('About :name', ['name' => $communityMember->firstName()]) }}</h2>
            @include('community-members.partials.about', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.community-members.show-interests'))
            <h2>{{ __('Interests') }}</h2>
            @include('community-members.partials.interests', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.community-members.show-experiences'))
            <h2>{{ __('Experiences') }}</h2>
            @include('community-members.partials.experiences', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.community-members.show-access-needs'))
            <h2>{{ __('Access needs') }}</h2>
            @include('community-members.partials.access-needs', ['level' => 3])
        @endif
        </div>
    </div>
</x-app-wide-layout>
