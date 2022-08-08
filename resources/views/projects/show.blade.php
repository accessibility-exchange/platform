<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}@if($project->checkStatus('draft')) ({{ __('draft') }})@endif</x-slot>
    <x-slot name="header">
        <h1 id="project">
            {{ $project->name }}@if($project->checkStatus('draft')) ({{ __('draft') }})@endif
        </h1>
        <p><strong>{!! __('Accessibility project by :projectable', ['projectable' => '<a href="' . localized_route('regulated-organizations.show', $project->projectable) . '">' . $project->projectable->name . '</a>']) !!}</strong></p>
        <p><strong>{{ __('Project status') }}:</strong> @if($project->started()){{ __('In progress') }}@else{{ __('Not started') }}@endif</p>
        @if($project->start_date && $project->started())
        <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }}</p>
        @endif
        @if(Auth::user()->individual)
        @if(!Auth::user()->individual->projectsOfInterest->contains($project->id))
        <form action="{{ localized_route('individuals.express-interest', Auth::user()->individual) }}" method="post">
            @csrf
            <x-hearth-input type="hidden" name="project_id" :value="$project->id" />
            <button>{{ __('I’m interested in this project') }}</button>
        </form>
        @else
        <form action="{{ localized_route('individuals.remove-interest', Auth::user()->individual) }}" method="post">
            @csrf
            <x-hearth-input type="hidden" name="project_id" :value="$project->id" />
            <button type="submit">{{ __('I’m not interested in this project') }}</button>
        </form>
        @endif
        @endif
        @can('update', $project)
        @if($project->checkStatus('published'))
            <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish')" />
            </form>
        @endif
        <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Manage project') }}</a>
        @endcan
    </x-slot>

    @can('update', $project)
        <x-translation-manager :model="$project" />
    @else
        <x-language-changer :model="$project" />
    @endcan

    <div class="with-sidebar">
        <div class="stack">
            <nav aria-labelledby="project">
                <ul class="stack" role="list">
                    <li>
                        <x-nav-link :href="localized_route('projects.show', $project)" :active="request()->localizedRouteIs('projects.show')">
                            {{ __('Project overview') }}
                        </x-nav-link>
                    </li>
                    <li>
                        <x-nav-link :href="localized_route('projects.show-team', $project)" :active="request()->localizedRouteIs('projects.show-team')">
                            {{ __('Project team') }}
                        </x-nav-link>
                    </li>
                    <li>
                        <x-nav-link :href="localized_route('projects.show-engagements', $project)" :active="request()->localizedRouteIs('projects.show-engagements')">
                            {{ __('Engagements') }}
                        </x-nav-link>
                    </li>
                    <li>
                        <x-nav-link :href="localized_route('projects.show-outcomes', $project)" :active="request()->localizedRouteIs('projects.show-outcomes')">
                            {{ __('Outcomes and reports') }}
                        </x-nav-link>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="stack">
            @if(request()->localizedRouteIs('projects.show'))
            <h2>{{ __('Project overview') }}</h2>
            @include('projects.partials.overview')
        @elseif(request()->localizedRouteIs('projects.show-team'))
            <h2>{{ __('Project team') }}</h2>
            @include('projects.partials.team')
        @elseif(request()->localizedRouteIs('projects.show-engagements'))
            <h2>{{ __('Engagements') }}</h2>
            @include('projects.partials.engagements')
        @elseif(request()->localizedRouteIs('projects.show-outcomes'))
            <h2>{{ __('Outcomes and reports') }}</h2>
            @include('projects.partials.outcomes')
        @endif
        </div>
    </div>
    {{-- TODO: Contact project team. --}}

</x-app-wide-layout>
