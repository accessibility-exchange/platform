<x-app-wide-layout>
    <x-slot name="title">{{ $entity->name }}</x-slot>
    <x-slot name="header">
        <h1 id="entity">
            {{ $entity->name }}
        </h1>
    </x-slot>

    <p>{{ $entity->locality }}, {{ get_region_name($entity->region, ["CA"], locale()) }}</p>

    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="entity">
            <ul role="list">
                <x-nav-link :href="localized_route('entities.show', $entity)" :active="request()->routeIs(locale() . '.entities.show')">{{ __('About') }}</x-nav-link>
                <x-nav-link :href="localized_route('entities.show-accessibility-and-inclusion', $entity)" :active="request()->routeIs(locale() . '.entities.show-accessibility-and-inclusion')">{{ __('Accessibility and inclusion') }}</x-nav-link>
                <x-nav-link :href="localized_route('entities.show-projects', $entity)" :active="request()->routeIs(locale() . '.entities.show-projects')">{{ __('Projects') }}</x-nav-link>
            </ul>
        </nav>

        @if(request()->routeIs(locale() . '.entities.show'))
        <div class="stack" id="about">
            <h2>{{ __('About') }}</h2>
            @can('update', $entity)
            <p><a class="button" href="{{ localized_route('entities.edit', $entity) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('About') . '</span>']) !!}</a></p>
            @endcan

            @include('entities.boilerplate.about', ['level' => 3])
        </div>
        @elseif(request()->routeIs(locale() . '.entities.show-accessibility-and-inclusion'))
        <div class="stack" id="accessibility-and-inclusion">
            <h2>{{ __('Accessibility and inclusion') }}</h2>
            @can('update', $entity)
            <p><a class="button" href="{{ localized_route('entities.edit', $entity) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Accessibility and inclusion') . '</span>']) !!}</a></p>
            @endcan

            @include('entities.boilerplate.accessibility-and-inclusion', ['level' => 3])
        </div>
        @elseif(request()->routeIs(locale() . '.entities.show-projects'))
        <div class="stack" id="projects">
            <h2>{{ __('Projects') }}</h2>
            @can('update', $entity)
                <p><a class="button" href="{{ localized_route('projects.create', $entity) }}">{{ __('Create a project') }}</a></p>
            @endcan

            <div class="stack">
                <h3>{{  __('Current projects') }}</h3>
                @forelse ($entity->currentProjects as $project)
                <x-project-card :project="$project" :level="4" :showEntity="false" />
                @empty
                <p>{{ __('No projects found.') }}</p>
                @endforelse
                <h3>{{  __('Completed projects') }}</h3>
                @forelse ($entity->pastProjects as $project)
                <x-project-card :project="$project" :level="4" :showEntity="false" />
                @empty
                <p>{{ __('No projects found.') }}</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
