<x-app-wide-layout>
    <x-slot name="title">{{ $regulatedOrganization->name }}</x-slot>
    <x-slot name="header">
        <div class="stack">
            <h1 id="regulated-organization">
                {{ $regulatedOrganization->name }}
            </h1>
            <div class="meta stack">
                <p><strong>{{ Str::ucfirst(__('regulated-organization.types.' . $regulatedOrganization->type)) }}</strong> &middot; {{ $regulatedOrganization->locality }}, {{ $regulatedOrganization->region }}</p>
            </div>
            @if($regulatedOrganization->social_links && count($regulatedOrganization->social_links) > 0 || $regulatedOrganization->website_link)
                <ul role="list" class="cluster">
                    @if($regulatedOrganization->website_link)
                        <li>
                            <a class="weight:semibold with-icon" href="{{ $regulatedOrganization->website_link }}"><x-heroicon-o-globe-alt class="icon" />{{ __('Website') }}</a>
                        </li>
                    @endif
                    @if($regulatedOrganization->social_links)
                        @foreach($regulatedOrganization->social_links as $key => $value)
                            <li>
                                <a class="weight:semibold with-icon" href="{{ $value }}">@svg('forkawesome-' . str_replace('_', '', $key), 'icon'){{ Str::studly($key) }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            @endif
        </div>
    </x-slot>


    @if(Auth::user()->hasRequestedToJoin($regulatedOrganization))
        <form action="{{ localized_route('requests.cancel') }}" method="POST">
            @csrf
            <button>{{ __('Cancel request to join :regulated-organization', ['regulated-organization' => $regulatedOrganization->name]) }}</button>
        </form>
    @endif

    <div class="with-sidebar">
        <nav class="secondary" aria-labelledby="regulated-organization">
            <ul role="list">
                <li>
                    <x-nav-link :href="localized_route('regulated-organizations.show', $regulatedOrganization)" :active="request()->routeIs(locale() . '.regulated-organizations.show')">{{ __('About') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('regulated-organizations.show-projects', $regulatedOrganization)" :active="request()->routeIs(locale() . '.regulated-organizations.show-projects')">{{ __('Projects') }}</x-nav-link>
                </li>
            </ul>
        </nav>
        <div class="stack">
            @if(request()->routeIs(locale() . '.regulated-organizations.show'))
                <h2 class="repel">{{ __('About') }} @can('update', $regulatedOrganization)<a class="cta secondary" href="{{ localized_route('regulated-organizations.edit', $regulatedOrganization) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a>@endcan</h2>
                @include('regulated-organizations.partials.about')
            @elseif(request()->routeIs(locale() . '.regulated-organizations.show-projects'))
                <h2 class="repel">{{ __('Projects') }} @can('update', $regulatedOrganization)<a class="cta secondary" href="{{ localized_route('projects.create', $regulatedOrganization) }}">{{ __('Create a project') }}</a>@endcan</h2>
                @include('regulated-organizations.partials.projects')
            @endif
        </div>
    </div>
</x-app-wide-layout>
