<x-app-layout page-width="wide">
    <x-slot name="title">{{ $individual->name }}</x-slot>
    <x-slot name="header">
        @if (auth()->hasUser() &&
            auth()->user()->isAdministrator() &&
            $individual->user->checkStatus('suspended'))
            @push('banners')
                <x-banner type="error" icon="heroicon-s-ban">{{ __('This account has been suspended.') }}</x-banner>
            @endpush
        @endif
        @if ($individual->checkStatus('draft'))
            @push('banners')
                <x-banner type="warning" icon="">{{ __('You are previewing your public page.') }}</x-banner>
            @endpush
        @endif
        <div class="with-sidebar">
            <div class="stack">
                <div class="repel">
                    <h1>
                        <span id="individual">{{ $individual->name }}</span>
                    </h1>

                    @if ($individual->checkStatus('draft'))
                        <span class="badge ml-auto">{{ __('Draft mode') }}</span>
                    @endif

                    @can('update', $individual)
                        <form action="{{ localized_route('individuals.update-publication-status', $individual) }}"
                            method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            @if ($individual->checkStatus('published'))
                                <button class="secondary" name="unpublish" type="submit"
                                    value="1">{{ __('Unpublish') }}</button>
                            @else
                                <button class="secondary" name="publish" type="submit" value="1"
                                    @cannot('publish', $individual)) @ariaDisabled @endcannot>{{ __('Publish') }}</button>
                            @endif
                        </form>
                    @endcan
                    @can('block', $individual)
                        <x-block-modal :blockable="$individual" />
                    @endcan
                </div>
                <div class="meta">
                    @if ($individual->pronouns)
                        <p>{{ $individual->pronouns }}</p>
                    @endif
                    <p>
                        @if ($individual->locality)
                            {{ $individual->locality }},
                        @endif{{ get_region_name($individual->region, ['CA'], locale()) }}
                    </p>
                    <p><strong>{{ implode(', ', $individual->display_roles) }}</strong>
                        @can('update', $individual)
                            <a href="{{ localized_route('individuals.show-role-edit') }}">@svg('heroicon-o-pencil', 'mr-1')
                                {{ __('Edit') }}</a>
                        @endcan
                    </p>
                </div>
                @if (($individual->social_links && count($individual->social_links) > 0) || !empty($individual->website_link))
                    <ul class="cluster" role="list">
                        @if ($individual->social_links)
                            @foreach ($individual->social_links as $key => $value)
                                <li>
                                    <a class="with-icon font-semibold"
                                        href="{{ $value }}">@svg('forkawesome-' . str_replace('_', '', $key), 'icon'){{ Str::studly($key) }}</a>
                                </li>
                            @endforeach
                        @endif
                        @if (!empty($individual->website_link))
                            <li>
                                <a class="with-icon font-semibold" href="{{ $individual->website_link }}">
                                    @svg('heroicon-o-globe-alt')
                                    {{ __('Website', [], !is_signed_language($language) ? $language : locale()) }}
                                </a>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
    </x-slot>

    <x-language-changer :model="$individual" />
    <div class="with-sidebar">
        <nav class="secondary" aria-labelledby="individual">
            <ul role="list">
                <li>
                    <x-nav-link :href="localized_route('individuals.show', $individual)" :active="request()->localizedRouteIs('individuals.show')">{{ __('About') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('individuals.show-experiences', $individual)" :active="request()->localizedRouteIs('individuals.show-experiences')">{{ __('Experiences') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('individuals.show-interests', $individual)" :active="request()->localizedRouteIs('individuals.show-interests')">{{ __('Interests') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route(
                        'individuals.show-communication-and-consultation-preferences',
                        $individual,
                    )" :active="request()->localizedRouteIs(
                        'individuals.show-communication-and-consultation-preferences',
                    )">
                        {{ __('Communication and consultation preferences') }}</x-nav-link>
                </li>
            </ul>
        </nav>

        <div class="stack">
            @if (request()->localizedRouteIs('individuals.show'))
                <h2 class="repel">{{ __('About') }} @can('update', $individual)
                        <a class="cta secondary"
                            href="{{ localized_route('individuals.edit', $individual) }}">@svg('heroicon-o-pencil', 'mr-1')
                            {!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a>
                    @endcan
                </h2>
                @include('individuals.partials.about')
            @elseif(request()->localizedRouteIs('individuals.show-experiences'))
                <h2 class="repel">{{ __('Experiences') }} @can('update', $individual)
                        <a class="cta secondary"
                            href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => $individual->isConnector() ? 3 : 2]) }}">@svg('heroicon-o-pencil', 'mr-1')
                            {!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Experiences') . '</span>']) !!}</a>
                    @endcan
                </h2>
                @include('individuals.partials.experiences')
            @elseif(request()->localizedRouteIs('individuals.show-interests'))
                <h2 class="repel">{{ __('Interests') }} @can('update', $individual)
                        <a class="cta secondary"
                            href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => $individual->isConnector() ? 4 : 3]) }}">@svg('heroicon-o-pencil', 'mr-1')
                            {!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Interests') . '</span>']) !!}</a>
                    @endcan
                </h2>
                @include('individuals.partials.interests')
            @elseif(request()->localizedRouteIs('individuals.show-communication-and-consultation-preferences'))
                <h2 class="repel">{{ __('Communication and consultation preferences') }} @can('update', $individual)
                        <a class="cta secondary"
                            href="{{ localized_route('individuals.edit', ['individual' => $individual, 'step' => $individual->isConnector() ? 5 : 4]) }}">@svg('heroicon-o-pencil', 'mr-1')
                            {!! __('Edit :section', [
                                'section' => '<span class="visually-hidden">' . __('Communication and consultation preferences') . '</span>',
                            ]) !!}</a>
                    @endcan
                </h2>
                @include('individuals.partials.communication-and-consultation-preferences')
            @endif
        </div>
    </div>
</x-app-layout>
