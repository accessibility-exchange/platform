<x-app-wide-layout>
    <x-slot name="title">{{ __('My community member page (draft)') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('My community member page (draft)') }}
        </h1>

    </x-slot>

    <h2>{{ __('Preview') }}</h2>
    <div class="preview flow">
        <div class="meta">
            @if($communityMember->getMedia('picture')->first())
            <img class="float-left" src="{{ $communityMember->getMedia('picture')->first()->getUrl('thumb') }}" alt="{{ $communityMember->picture_alt }}" />
            @else
            <img class="float-left" src="https://source.boringavatars.com/bauhaus/192/{{ $communityMember->name }}?colors=264653,2a9d8f,e9c46a,f4a261,e76f51" alt="{{ $communityMember->name }}" />
            @endif
            <h3>{{ $communityMember->name }}</h3>
            <p>{{ __('Individual community member') }}</p>
            <p>{{ $communityMember->locality }}, {{ get_region_name($communityMember->region, ["CA"], locale()) }}</p>
            @if($communityMember->pronouns)
            <p>{{ $communityMember->pronouns }}</p>
            @endif
        </div>
        <div class="flow" id="about">
            <h3>{{ __('About :name', ['name' => $communityMember->firstName()]) }}</h3>
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="{{ localized_route('community-members.edit', $communityMember) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a></p>

            {!! Illuminate\Mail\Markdown::parse($communityMember->bio) !!}

            @if($communityMember->links)
            <h4>{{ $communityMember->firstName() }}’s links</h4>
            <ul>
                @foreach($communityMember->links as $link)
                <li><a href="{{ $link['url'] }}" rel="external">{{ $link['text'] }}</a></li>
                @endforeach
            </ul>
            @endif

            @if($communityMember->creator === 'other')
            <p><em>{{ __('This page was created by :creator, :name’s :relationship.', ['creator' => $communityMember->creator_name, 'name' => $communityMember->firstName(), 'relationship' => $communityMember->creator_relationship]) }}</em></p>
            @endif
        </div>
        <div class="flow" id="interests-and-goals">
            <h3>{{ __('Interests and goals') }}</h3>
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests and goals') . '</span>']) !!}</a></p>

            @include('community-members.boilerplate.interests-and-goals', ['level' => 4])
        </div>
        <div class="flow" id="lived-experience">
            <h3>{{ __('Lived experience') }}</h3>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Lived experience') . '</span>']) !!}</a></p>

            @include('community-members.boilerplate.lived-experience', ['level' => 4])
        </div>
        <div class="flow" id="professional-experience">
            <h3>{{ __('Professional experience') }}</h3>
            <x-privacy-indicator level="public">
                <strong>{{ __('This information is public.') }}</strong> {{ __('It is visible to anyone with an account on this website.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Professional experience') . '</span>']) !!}</a></p>

            @include('community-members.boilerplate.professional-experience', ['level' => 4])
        </div>
        <div class="flow" id="access-needs">
            <h3>{{ __('Access needs') }}</h3>
            <x-privacy-indicator level="private">
                <strong>{{ __('This information is not public.') }}</strong> {{ __('It is only visible to regulated entities who work with you.') }}
            </x-privacy-indicator>
            <p><a class="button" href="#">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Access needs') . '</span>']) !!}</a></p>

            @include('community-members.boilerplate.access-needs', ['level' => 4])
        </div>
    </div>
    <div class="steps flow">
        <h2>Steps to publish</h2>

        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('community-members.edit', $communityMember) }}">{{ __('Interests and goals') }}</a><br />
            <small>Completed</small>
        </p>
        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('community-members.edit', $communityMember) }}">{{ __('Lived experience') }}</a><br />
            <small>Completed</small>
        </p>
        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('community-members.edit', $communityMember) }}">{{ __('Access needs') }}</a><br />
            <small>Completed</small>
        </p>

        <h3>Optional:</h3>

        <p>
            <x-heroicon-s-check-circle class="icon" style="color: green" /> <a href="{{ localized_route('community-members.edit', $communityMember) }}">{{ __('Professional experience') }}</a><br />
            <small>Completed</small>
        </p>

        @can('update', $communityMember)
        @if($communityMember->checkStatus('draft'))
        <form action="{{ localized_route('community-members.update-publication-status', $communityMember) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-hearth-input type="submit" name="publish" :value="__('Publish my page')" />
        </form>
        @endif
        @endcan
    </div>
</x-app-wide-layout>
