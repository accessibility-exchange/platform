<x-app-wide-layout>
    <x-slot name="header">
        <h1>
            {{ $entity->name }}
        </h1>
    </x-slot>

    <p>{{ $entity->locality }}, {{ get_region_name($entity->region, ["CA"], locale()) }}</p>

    <div class="tabs flow" x-data="tabs(window.location.hash ? window.location.hash.substring(1) : 'about')" x-on:resize.window="enabled = window.innerWidth > 1023">
        <h2 x-show="!enabled">{{ __('Contents') }}</h2>
        <ul x-bind="tabList">
            <li x-bind="tabWrapper"><a href="#about" x-bind="tab">{{ __('entity.section_about') }}</a></li>
            <li x-bind="tabWrapper"><a href="#accessibility-and-inclusion" x-bind="tab">{{ __('entity.section_accessibility_and_inclusion') }}</a></li>
            <li x-bind="tabWrapper"><a href="#projects" x-bind="tab">{{ __('entity.section_projects') }}</a></li>
        </ul>

        <div class="flow" id="about" x-bind="tabpanel">
            <h2>{{ __('entity.section_about') }}</h2>
            @can('update', $entity)
            <p><a class="button" href="{{ localized_route('entities.edit', $entity) }}">{!! __('entity.edit_section', ['section' => '<span class="visually-hidden">' . __('entity.section_about') . '</span>']) !!}</a></p>
            @endcan

            @include('entities.boilerplate.about', ['level' => 3])
        </div>

        <div class="flow" id="accessibility-and-inclusion" x-bind="tabpanel">
            <h2>{{ __('entity.section_accessibility_and_inclusion') }}</h2>
            @can('update', $entity)
            <p><a class="button" href="{{ localized_route('entities.edit', $entity) }}">{!! __('entity.edit_section', ['section' => '<span class="visually-hidden">' . __('entity.section_accessibility_and_inclusion') . '</span>']) !!}</a></p>
            @endcan

            @include('entities.boilerplate.accessibility-and-inclusion', ['level' => 3])
        </div>

        <div class="flow" id="projects" x-bind="tabpanel">
            <h2>{{ __('entity.section_projects') }}</h2>
            @can('update', $entity)
            @if(count($entity->projects) > 0)
                <p><a class="button" href="#">{!! __('entity.edit_section', ['section' => '<span class="visually-hidden">' . __('entity.section_projects') . '</span>']) !!}</a></p>
            @else
                <p><a class="button" href="{{ localized_route('projects.create', $entity) }}">{{ __('entity.create_project') }}</a></p>
            @endif
            @endcan

            <div class="flow">
                @forelse ($entity->projects as $project)
                <x-project-card :project="$project" :showEntity="false" />
                @empty
                <p>{{ __('project.none_found') }}</p>
                @endforelse
            </div>
    </div>
</x-app-layout>
