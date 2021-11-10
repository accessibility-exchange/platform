<x-app-wide-layout>
    <x-slot name="title">{{ $consultant->name }}</x-slot>
    <x-slot name="header">
        @if($consultant->getMedia('picture')->first())
        <img class="float-left"src="{{ $consultant->getMedia('picture')->first()->getUrl('thumb') }}" alt="{{ $consultant->picture_alt }}" />
        @else
        <img class="float-left" src="https://source.boringavatars.com/bauhaus/192/{{ $consultant->name }}?colors=264653,2a9d8f,e9c46a,f4a261,e76f51" alt="{{ $consultant->name }}" />
        @endif
        <h1>
            {{ $consultant->name }}
        </h1>
        <div class="meta">
            <p>{{ __('Individual consultant') }}</p>
            <p>{{ $consultant->locality }}, {{ get_region_name($consultant->region, ["CA"], locale()) }}</p>
            @if($consultant->pronouns)
            <p>{{ $consultant->pronouns }}</p>
            @endif
        </div>
        @can('update', $consultant)
        @if($consultant->checkStatus('published'))
        <form action="{{ localized_route('consultants.update-publication-status', $consultant) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish my page')" />
        </form>
        @endif
        @endcan
    </x-slot>


    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="project">
            <ul role="list">
                <x-nav-link :href="localized_route('consultants.show', $consultant)" :active="request()->routeIs(locale() . '.consultants.show')">{{ __('About') }}</x-nav-link>
                <x-nav-link :href="localized_route('consultants.show-interests-and-goals', $consultant)" :active="request()->routeIs(locale() . '.consultants.show-interests-and-goals')">{{ __('Interests and goals') }}</x-nav-link>
                @can('viewPersonalDetails', $consultant)
                <x-nav-link :href="localized_route('consultants.show-lived-experience', $consultant)" :active="request()->routeIs(locale() . '.consultants.show-lived-experience')">{{ __('Lived experience') }}</x-nav-link>
                @endcan
                <x-nav-link :href="localized_route('consultants.show-professional-experience', $consultant)" :active="request()->routeIs(locale() . '.consultants.show-professional-experience')">{{ __('Professional experience') }}</x-nav-link>
                @can('viewPersonalDetails', $consultant)
                <x-nav-link :href="localized_route('consultants.show-access-needs', $consultant)" :active="request()->routeIs(locale() . '.consultants.show-access-needs')">{{ __('Access needs') }}</x-nav-link>
                @endcan
            </ul>
        </nav>

        <div class="flow">
        @if(request()->routeIs(locale() . '.consultants.show'))
            <h2>{{ __('About :name', ['name' => $consultant->firstName()]) }}</h2>
            @can('update', $consultant)
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="{{ localized_route('consultants.edit', $consultant) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a></p>
            @endcan

            {!! Illuminate\Mail\Markdown::parse($consultant->bio) !!}

            @if($consultant->links)
            <h3>{{ __(':name’s links', ['name' => $consultant->firstName()]) }}</h3>
            <ul>
                @foreach($consultant->links as $link)
                <li><a href="{{ $link['url'] }}" rel="external">{{ $link['text'] }}</a></li>
                @endforeach
            </ul>
            @endif

            @if($consultant->creator === 'other')
            <p><em>{{ __('This page was created by :creator, :name’s :relationship.', ['creator' => $consultant->creator_name, 'name' => $consultant->firstName(), 'relationship' => $consultant->creator_relationship]) }}</em></p>
            @endif
        @elseif(request()->routeIs(locale() . '.consultants.show-interests-and-goals'))
            <h2>{{ __('Interests and goals') }}</h2>
            @can('update', $consultant)
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests and goals') . '</span>']) !!}</a></p>
            @endcan
            @include('consultants.boilerplate.interests-and-goals', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.consultants.show-lived-experience'))
            <h2>{{ __('Lived experience') }}</h2>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Lived experience') . '</span>']) !!}</a></p>
            @include('consultants.boilerplate.lived-experience', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.consultants.show-professional-experience'))
            <h2>{{ __('Professional experience') }}</h2>
            @can('update', $consultant)
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Professional experience') . '</span>']) !!}</a></p>
            @endcan
            @include('consultants.boilerplate.professional-experience', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.consultants.show-access-needs'))
            <h2>{{ __('Access needs') }}</h2>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Access needs') . '</span>']) !!}</a></p>
            @include('consultants.boilerplate.access-needs', ['level' => 3])
        @endif
        </div>
    </div>
</x-app-wide-layout>
