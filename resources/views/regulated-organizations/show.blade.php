<x-app-wide-layout>
    <x-slot name="title">{{ $regulatedOrganization->name }}</x-slot>
    <x-slot name="header">
        <h1 id="entity">
            {{ $regulatedOrganization->name }}
        </h1>
    </x-slot>

    <p>{{ $regulatedOrganization->locality }}, {{ get_region_name($regulatedOrganization->region, ["CA"], locale()) }}</p>

    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="entity">
            <ul role="list">
                <x-nav-link :href="localized_route('regulated-organizations.show', $regulatedOrganization)" :active="request()->routeIs(locale() . '.regulated-organizations.show')">{{ __('About') }}</x-nav-link>
                <x-nav-link :href="localized_route('regulated-organizations.show-accessibility-and-inclusion', $regulatedOrganization)" :active="request()->routeIs(locale() . '.regulated-organizations.show-accessibility-and-inclusion')">{{ __('Accessibility and inclusion') }}</x-nav-link>
                <x-nav-link :href="localized_route('regulated-organizations.show-projects', $regulatedOrganization)" :active="request()->routeIs(locale() . '.regulated-organizations.show-projects')">{{ __('Projects') }}</x-nav-link>
            </ul>
        </nav>

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
</x-app-layout>
