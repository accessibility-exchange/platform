<x-app-wide-layout>
    <x-slot name="title">{{ $communityMember->name }}</x-slot>
    <x-slot name="header">
        <div class="with-sidebar">
            @if($communityMember->getMedia('picture')->first())
            <img class="float:left" src="{{ $communityMember->getMedia('picture')->first()->getUrl('thumb') }}" alt="{{ $communityMember->picture_alt }}" />
            @else
            <form action="">
                {{-- {{ __('Add your photo') }} --}}
            </form>
            @endif
            <div class="stack">
                <h1 class="repel">
                    <span id="community-member">{{ $communityMember->name }}</span> @can('update', $communityMember)
                        <form action="{{ localized_route('community-members.update-publication-status', $communityMember) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            @if($communityMember->checkStatus('published'))
                                <x-hearth-input class="secondary" type="submit" name="unpublish" :value="__('Unpublish')" />
                            @else
                                <x-hearth-input class="secondary" type="submit" name="publish" :value="__('Publish')" />
                            @endif
                        </form>
                    @endcan
                </h1>
                <div class="meta">
                    @if($communityMember->pronouns)
                    <p>{{ $communityMember->pronouns }}</p>
                    @endif
                    <p>@if($communityMember->locality){{ $communityMember->locality }}, @endif{{ get_region_name($communityMember->region, ["CA"], locale()) }}</p>
                    <p><strong>{{ implode(', ', $communityMember->communityRoles()->pluck('name')->toArray()) }}</strong>@can('update', $communityMember) <a href="{{ localized_route('community-members.show-role-edit') }}">{{ __('Edit') }}</a>@endcan</p>
                </div>
                @if($communityMember->social_links && count($communityMember->social_links) > 0 || $communityMember->web_links && count($communityMember->web_links) > 0)
                <ul role="list" class="cluster">
                    @if($communityMember->social_links)
                        @foreach($communityMember->social_links as $key => $value)
                        <li>
                            <a class="weight:semibold with-icon" href="{{ $value }}">@svg('forkawesome-' . str_replace('_', '', $key), 'icon'){{ Str::studly($key) }}</a>
                        </li>
                        @endforeach
                    @endif
                    @if($communityMember->web_links)
                        @foreach($communityMember->web_links as $link)
                            <li>
                                <a class="weight:semibold with-icon" href="{{ $link['url'] }}"><x-heroicon-o-globe-alt class="icon" /> {{ $link['title'] }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="with-sidebar">
        <nav class="secondary" aria-labelledby="community-member">
            <ul role="list">
                <li>
                    <x-nav-link :href="localized_route('community-members.show', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show')">{{ __('About') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('community-members.show-experiences', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-experiences')">{{ __('Experiences') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('community-members.show-interests', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-interests')">{{ __('Interests') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('community-members.show-communication-and-meeting-preferences', $communityMember)" :active="request()->routeIs(locale() . '.community-members.show-communication-and-meeting-preferences')">{{ __('Communication and meeting preferences') }}</x-nav-link>
                </li>
            </ul>
        </nav>

        <div class="stack">
        @if(request()->routeIs(locale() . '.community-members.show'))
            <h2 class="repel">{{ __('About') }} @can('update', $communityMember)<a class="cta secondary" href="{{ localized_route('community-members.edit', $communityMember) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a>@endcan</h2>
            @include('community-members.partials.about')
        @elseif(request()->routeIs(locale() . '.community-members.show-experiences'))
            <h2 class="repel">{{ __('Experiences') }} @can('update', $communityMember)<a class="cta secondary" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 2]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Experiences') . '</span>']) !!}</a>@endcan</h2>
            @include('community-members.partials.experiences')
        @elseif(request()->routeIs(locale() . '.community-members.show-interests'))
            <h2 class="repel">{{ __('Interests') }} @can('update', $communityMember)<a class="cta secondary" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 3]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests') . '</span>']) !!}</a>@endcan</h2>
            @include('community-members.partials.interests')
        @elseif(request()->routeIs(locale() . '.community-members.show-communication-and-meeting-preferences'))
            <h2 class="repel">{{ __('Communication and meeting preferences') }} @can('update', $communityMember)<a class="cta secondary" href="{{ localized_route('community-members.edit', ['communityMember' => $communityMember, 'step' => 4]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Communication and meeting preferences') . '</span>']) !!}</a>@endcan</h2>
            @include('community-members.partials.communication-and-meeting-preferences')
        @endif
        </div>
    </div>
</x-app-wide-layout>
