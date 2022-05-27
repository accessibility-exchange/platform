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
                    <x-nav-link :href="localized_route('regulated-organizations.show-accessibility-and-inclusion', $regulatedOrganization)" :active="request()->routeIs(locale() . '.regulated-organizations.show-accessibility-and-inclusion')">{{ __('Accessibility and inclusion') }}</x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="localized_route('regulated-organizations.show-projects', $regulatedOrganization)" :active="request()->routeIs(locale() . '.regulated-organizations.show-projects')">{{ __('Projects') }}</x-nav-link>
                </li>
            </ul>
        </nav>
        <div class="stack">

        @if(request()->routeIs(locale() . '.regulated-organizations.show'))
        <div class="stack" id="about">
            <h2>{{ __('About') }}</h2>
            @can('update', $regulatedOrganization)
            <p><a class="button" href="{{ localized_route('regulated-organizations.edit', $regulatedOrganization) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a></p>
            @endcan

            @include('regulated-organizations.boilerplate.about', ['level' => 3])
        </div>
        @elseif(request()->routeIs(locale() . '.regulated-organizations.show-accessibility-and-inclusion'))
        <div class="stack" id="accessibility-and-inclusion">
            <h2>{{ __('Accessibility and inclusion') }}</h2>
            @can('update', $regulatedOrganization)
            <p><a class="button" href="{{ localized_route('regulated-organizations.edit', $regulatedOrganization) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Accessibility and inclusion') . '</span>']) !!}</a></p>
            @endcan

            @include('regulated-organizations.boilerplate.accessibility-and-inclusion', ['level' => 3])
        </div>
        @elseif(request()->routeIs(locale() . '.regulated-organizations.show-projects'))
        <div class="stack" id="projects">
            <h2>{{ __('Projects') }}</h2>
            @can('update', $regulatedOrganization)
                <p><a class="button" href="{{ localized_route('projects.create', $regulatedOrganization) }}">{{ __('Create a project') }}</a></p>
            @endcan

            <div class="stack">
                <h3>{{  __('Current projects') }}</h3>
                @forelse ($regulatedOrganization->currentProjects as $project)
                <x-project-card :project="$project" :level="4" :showRegulatedOrganization="false" />
                @empty
                <p>{{ __('No projects found.') }}</p>
                @endforelse
                <h3>{{  __('Completed projects') }}</h3>
                @forelse ($regulatedOrganization->pastProjects as $project)
                <x-project-card :project="$project" :level="4" :showRegulatedOrganization="false" />
                @empty
                <p>{{ __('No projects found.') }}</p>
                @endforelse
            </div>
        </div>
        @endif
        </div>
    </div>
</x-app-wide-layout>
