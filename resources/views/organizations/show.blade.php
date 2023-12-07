<x-app-layout page-width="wide">
    <x-slot name="title">{{ $organization->getTranslation('name', $language) }}</x-slot>
    <x-slot name="header">
        @if (auth()->hasUser() &&
                auth()->user()->isAdministrator() &&
                $organization->checkStatus('suspended'))
            @push('banners')
                <x-banner type="error" icon="heroicon-s-ban">{{ __('This account has been suspended.') }}</x-banner>
            @endpush
        @endif
        @if ($organization->checkStatus('draft'))
            @push('banners')
                <x-banner type="warning" icon="">{{ __('You are previewing your organization’s page.') }}</x-banner>
            @endpush
        @endif
        <div class="stack">
            <div class="repel">
                <h1 id="organization">
                    {{ $organization->getTranslation('name', $language) }}
                </h1>
                @if ($organization->checkStatus('draft'))
                    <span class="badge ml-auto">{{ __('Draft mode') }}</span>
                    <x-interpretation name="{{ __('You are previewing your organization’s page.', [], 'en') }}" />
                @else
                    <x-interpretation name="{{ __('Community Organization page', [], 'en') }}" />
                @endif
                @can('update', $organization)
                    <form action="{{ localized_route('organizations.update-publication-status', $organization) }}"
                        method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        @if ($organization->checkStatus('published'))
                            <button class="secondary" name="unpublish" type="submit"
                                value="1">{{ __('Unpublish') }}</button>
                        @else
                            <button class="secondary" name="publish" type="submit" value="1"
                                @cannot('publish', $organization)) @ariaDisabled @endcannot>{{ __('Publish') }}</button>
                        @endif
                    </form>
                @endcan
            </div>
            <p class="meta">
                <strong>{{ App\Enums\OrganizationType::labels()[$organization->type] }}</strong><br />
                @foreach ($organization->roles as $role)
                    {{ $role }}@if (!$loop->last)
                        ,
                    @endif
                @endforeach
                <br />
                {{ $organization->locality }}, {{ $organization->region }}
            </p>
            <div class="repel">
                <ul class="cluster" role="list">
                    @if (($organization->social_links && count($organization->social_links) > 0) || $organization->website_link)
                        @if ($organization->website_link)
                            <li>
                                <a class="with-icon font-semibold" href="{{ $organization->website_link }}">
                                    @svg('heroicon-o-globe-alt')
                                    {{ __('Website', [], $language) }}
                                </a>
                            </li>
                        @endif
                        @if ($organization->social_links)
                            @foreach ($organization->social_links as $key => $value)
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
                        @if (Auth::user()->isReceivingNotificationsFor($organization))
                            <form action="{{ localized_route('notification-list.remove') }}" method="post">
                                @csrf
                                <x-hearth-input name="notificationable_type" type="hidden" :value="get_class($organization)" />
                                <x-hearth-input name="notificationable_id" type="hidden" :value="$organization->id" />

                                <button class="secondary">{{ __('Remove from my notification list') }}</button>
                            </form>
                        @else
                            <form action="{{ localized_route('notification-list.add') }}" method="post">
                                @csrf
                                <x-hearth-input name="notificationable_type" type="hidden" :value="get_class($organization)" />
                                <x-hearth-input name="notificationable_id" type="hidden" :value="$organization->id" />

                                <button class="secondary">{{ __('Add to my notification list') }}</button>
                            </form>
                        @endif
                    @endcan

                    @can('block', $organization)
                        <x-block-modal :blockable="$organization" />
                    @endcan
                </div>
            </div>
        </div>
    </x-slot>

    <x-language-changer :model="$organization" :currentLanguage="$language" />

    <div class="with-sidebar">
        <nav class="secondary" aria-labelledby="organization">
            <ul role="list">
                <li>
                    <x-nav-link :href="localized_route('organizations.show', $organization)" :active="request()->localizedRouteIs('organizations.show')">{{ __('About') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-constituencies', $organization)" :active="request()->localizedRouteIs('organizations.show-constituencies')">
                        {{ __('Communities we :represent_or_serve_and_support', ['represent_or_serve_and_support' => $organization->type === 'representative' ? __('represent') : __('serve and support')]) }}
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-interests', $organization)" :active="request()->localizedRouteIs('organizations.show-interests')">{{ __('Interests') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-projects', $organization)" :active="request()->localizedRouteIs('organizations.show-projects')">{{ __('Projects') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('organizations.show-contact-information', $organization)" :active="request()->localizedRouteIs('organizations.show-contact-information')">{{ __('Contact information') }}</x-nav-link>
                </li>
            </ul>
        </nav>
        <div class="stack">
            @if (request()->localizedRouteIs('organizations.show'))
                <x-section-heading :name="__('About')" :model="$organization" :href="localized_route('organizations.edit', $organization)" />
                <x-interpretation name="{{ __('About', [], 'en') }}" />
                @include('organizations.partials.about')
            @elseif(request()->localizedRouteIs('organizations.show-constituencies'))
                <x-section-heading :name="__('Communities we :represent_or_serve_and_support', [
                    'represent_or_serve_and_support' =>
                        $organization->type === 'representative' ? __('represent') : __('serve and support'),
                ])" :model="$organization" :href="localized_route('organizations.edit', ['organization' => $organization, 'step' => 2])" />
                <x-interpretation
                    name="{{ __(
                        'Communities we :represent_or_serve_and_support',
                        [
                            'represent_or_serve_and_support' =>
                                $organization->type === 'representative' ? __('represent') : __('serve and support'),
                        ],
                        'en',
                    ) }}" />
                @include('organizations.partials.constituencies')
            @elseif(request()->localizedRouteIs('organizations.show-interests'))
                <x-section-heading :name="__('Interests')" :model="$organization" :href="localized_route('organizations.edit', ['organization' => $organization, 'step' => 3])" />
                <x-interpretation name="{{ __('Interests', [], 'en') }}" />
                @include('organizations.partials.interests')
            @elseif(request()->localizedRouteIs('organizations.show-projects'))
                <h2 class="repel">{{ __('Projects') }} @can('update', $organization)
                        <a class="cta secondary"
                            href="{{ $organization->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create a project') }}</a>
                    @endcan
                </h2>
                <x-interpretation name="{{ __('Projects', [], 'en') }}" />
                @include('organizations.partials.projects')
            @elseif(request()->localizedRouteIs('organizations.show-contact-information'))
                <x-section-heading :name="__('Contact information')" :model="$organization" :href="localized_route('organizations.edit', ['organization' => $organization, 'step' => 4])" />
                <x-interpretation name="{{ __('Contact information', [], 'en') }}" />
                @include('organizations.partials.contact-information')
            @endif
        </div>
    </div>
</x-app-layout>
