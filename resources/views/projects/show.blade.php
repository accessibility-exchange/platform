<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1 id="project">
            {{ $project->name }}
        </h1>
        <p><strong>{!! __('Accessibility project by :entity', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</strong></p>
        <p><strong>{{ __('Project status') }}:</strong> @if($project->started()){{ __('In progress') }}@else{{ __('Not started') }}@endif</p>
        @if($project->started())
        <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }}</p>
        @endif
        @if(Auth::user()->communityMember)
        @if(!Auth::user()->communityMember->projectsOfInterest->contains($project->id))
        <form action="{{ localized_route('community-members.express-interest', Auth::user()->communityMember) }}" method="post">
            @csrf
            <x-hearth-input type="hidden" name="project_id" :value="$project->id" />
            <x-hearth-button type="submit">{{ __('I’m interested in this project') }}</x-hearth-button>
        </form>
        @else
        <form action="{{ localized_route('community-members.remove-interest', Auth::user()->communityMember) }}" method="post">
            @csrf
            <x-hearth-input type="hidden" name="project_id" :value="$project->id" />
            <x-hearth-button type="submit">{{ __('I’m not interested in this project') }}</x-hearth-button>
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
        <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Project dashboard') }}</a>
        @endcan
    </x-slot>

    <div class="with-sidebar">
        <div class="stack">
            <nav aria-labelledby="project">
                <ul role="list">
                    <x-nav-link :href="localized_route('projects.show', $project)" :active="request()->routeIs(locale() . '.projects.show')">
                        {{ __('Project overview') }}
                    </x-nav-link>
                    <x-nav-link :href="localized_route('projects.show-team', $project)" :active="request()->routeIs(locale() . '.projects.show-team')">
                        {{ __('Project team') }}
                    </x-nav-link>
                    <x-nav-link :href="localized_route('projects.show-engagements', $project)" :active="request()->routeIs(locale() . '.projects.show-engagements')">
                        {{ __('Engagements') }}
                    </x-nav-link>
                    <x-nav-link :href="localized_route('projects.show-outcomes', $project)" :active="request()->routeIs(locale() . '.projects.show-outcomes')">
                        {{ __('Outcomes and reports') }}
                    </x-nav-link>
                </ul>
            </nav>
        </div>
        <div class="stack">
            @if(request()->routeIs(locale() . '.projects.show'))
            <h2>{{ __('Project overview') }}</h2>
            @include('projects.partials.overview')
        @elseif(request()->routeIs(locale() . '.projects.show-team'))
            <h2>{{ __('Project team') }}</h2>
            @include('projects.partials.team')
        @elseif(request()->routeIs(locale() . '.projects.show-engagements'))
            <h2>{{ __('Engagements') }}</h2>
            @include('projects.partials.engagements')
        @elseif(request()->routeIs(locale() . '.projects.show-outcomes'))
            <h2>{{ __('Outcomes and reports') }}</h2>
            @include('projects.partials.outcomes')
        @endif
        </div>
    </div>
    {{-- TODO: Contact project team. --}}

</x-app-wide-layout>
