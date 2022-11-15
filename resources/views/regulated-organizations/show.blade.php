<x-app-wide-layout>
    <x-slot name="title">{{ $regulatedOrganization->getWrittenTranslation('name', $language) }}</x-slot>
    <x-slot name="header">
        @if (auth()->hasUser() &&
            auth()->user()->isAdministrator() &&
            $regulatedOrganization->checkStatus('suspended'))
            @push('banners')
                <div class="banner banner--error">
                    <div class="center center:wide">
                        <p>
                            @svg('heroicon-s-ban', 'icon--lg mr-2')
                            <span>{{ __('This account has been suspended.') }}</span>
                        </p>
                    </div>
                </div>
            @endpush
        @endif
        <div class="stack">
            <h1 class="repel" id="regulated-organization">
                {{ $regulatedOrganization->getWrittenTranslation('name', $language) }}
                @if ($regulatedOrganization->checkStatus('draft'))
                    <span class="badge ml-auto">{{ __('Draft mode') }}</span>
                @endif
                @can('update', $regulatedOrganization)
                    <form
                        action="{{ localized_route('regulated-organizations.update-publication-status', $regulatedOrganization) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        @if ($regulatedOrganization->checkStatus('published'))
                            <button class="secondary" name="unpublish" type="submit"
                                value="1">{{ __('Unpublish') }}</button>
                        @else
                            <button class="secondary" name="publish" type="submit" value="1"
                                @cannot('publish', $regulatedOrganization)) @ariaDisabled @endcannot>{{ __('Publish') }}</button>
                        @endif
                    </form>
                @endcan
            </h1>
            <p class="meta">
                <strong>{{ Str::ucfirst(__('regulated-organization.types.' . $regulatedOrganization->type)) }}</strong><br />
                @foreach ($regulatedOrganization->sectors as $sector)
                    {{ $sector->name }}@if (!$loop->last)
                        ,
                    @endif
                @endforeach
                <br />
                {{ $regulatedOrganization->locality }}, {{ $regulatedOrganization->region }}
            </p>
            <div class="repel">
                <ul class="cluster" role="list">
                    @if (($regulatedOrganization->social_links && count($regulatedOrganization->social_links) > 0) ||
                        $regulatedOrganization->website_link)
                        @if ($regulatedOrganization->website_link)
                            <li>
                                <a class="with-icon font-semibold" href="{{ $regulatedOrganization->website_link }}">
                                    @svg('heroicon-o-globe-alt')
                                    {{ __('Website', [], !is_signed_language($language) ? $language : locale()) }}
                                </a>
                            </li>
                        @endif
                        @if ($regulatedOrganization->social_links)
                            @foreach ($regulatedOrganization->social_links as $key => $value)
                                <li>
                                    <a class="with-icon font-semibold"
                                        href="{{ $value }}">@svg('forkawesome-' . str_replace('_', '', $key), 'icon'){{ Str::studly($key) }}</a>
                                </li>
                            @endforeach
                        @endif
                    @endif
                </ul>

                <div class="repel">
                    @can('receiveNotifications')
                        @if (Auth::user()->isReceivingNotificationsFor($regulatedOrganization))
                            <form action="{{ localized_route('notification-list.remove') }}" method="post">
                                @csrf
                                <x-hearth-input name="notificationable_type" type="hidden" :value="get_class($regulatedOrganization)" />
                                <x-hearth-input name="notificationable_id" type="hidden" :value="$regulatedOrganization->id" />

                                <button class="secondary">{{ __('Remove from my notification list') }}</button>
                            </form>
                        @else
                            <form action="{{ localized_route('notification-list.add') }}" method="post">
                                @csrf
                                <x-hearth-input name="notificationable_type" type="hidden" :value="get_class($regulatedOrganization)" />
                                <x-hearth-input name="notificationable_id" type="hidden" :value="$regulatedOrganization->id" />

                                <button class="secondary">{{ __('Add to my notification list') }}</button>
                            </form>
                        @endif
                    @endcan

                    @can('block', $regulatedOrganization)
                        <x-block-modal :blockable="$regulatedOrganization" />
                    @endcan
                </div>
            </div>
        </div>
    </x-slot>

    <x-language-changer :model="$regulatedOrganization" />

    <div class="with-sidebar">
        <nav class="secondary" aria-labelledby="regulated-organization">
            <ul role="list">
                <li>
                    <x-nav-link :href="localized_route('regulated-organizations.show', $regulatedOrganization)" :active="request()->localizedRouteIs('regulated-organizations.show')">{{ __('About') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('regulated-organizations.show-projects', $regulatedOrganization)" :active="request()->localizedRouteIs('regulated-organizations.show-projects')">{{ __('Projects') }}</x-nav-link>
                </li>
            </ul>
        </nav>
        <div class="stack">
            @if (request()->localizedRouteIs('regulated-organizations.show'))
                <h2 class="repel">{{ __('About') }} @can('update', $regulatedOrganization)
                        <a class="cta secondary"
                            href="{{ localized_route('regulated-organizations.edit', $regulatedOrganization) }}">@svg('heroicon-o-pencil', 'mr-1')
                            {!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a>
                    @endcan
                </h2>
                @include('regulated-organizations.partials.about')
            @elseif(request()->localizedRouteIs('regulated-organizations.show-projects'))
                <h2 class="repel">{{ __('Projects') }} @can('update', $regulatedOrganization)
                        <a class="cta secondary"
                            href="{{ $regulatedOrganization->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create a project') }}</a>
                    @endcan
                </h2>
                @include('regulated-organizations.partials.projects')
            @endif
        </div>
    </div>
</x-app-wide-layout>
