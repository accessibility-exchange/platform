<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1 id="project">
            {{ $project->name }}
        </h1>
        <p>{!! __('Accessibility project by :entity', ['entity' => '<a href="' . localized_route('entities.show', $project->entity) . '">' . $project->entity->name . '</a>']) !!}</p>
        <p><strong>{{ __('Status:') }}</strong> {{ $project->step() }}</p>
        @if($project->started())
        <p><strong>{{ __('Started') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }}</p>
        @else
        <p><strong>{{ __('Starting') }}:</strong> {{ $project->start_date->translatedFormat('F Y') }}</p>
        @endif
        @if($project->completed())
        <p><strong>{{ __('Completed') }}:</strong> {{ $project->end_date->translatedFormat('F Y') }}</p>
        @endif
        @if(!$project->completed() && Auth::user()->communityMember)
        @if($project->confirmedParticipants->contains(Auth::user()->communityMember))
        <p><a href="{{ localized_route('projects.participate', $project) }}" class="button">{{ __('Project dashboard') }}</a></p>
        @elseif(!Auth::user()->communityMember->projectsOfInterest->contains($project->id))
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
            @if(!$project->hasBuiltTeam())
            <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <x-hearth-input type="submit" name="unpublish" :value="__('Unpublish my project')" />
            </form>
            @endif
        @endif
        <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Project dashboard') }}</a>
        @endcan
    </x-slot>

    <div class="has-nav-secondary">
        <nav class="secondary" aria-labelledby="project">
            <ul role="list">
                <x-nav-link :href="localized_route('projects.show', $project)" :active="request()->routeIs(locale() . '.projects.show')">{{ __('Project overview') }}</x-nav-link>
                <x-nav-link :href="localized_route('projects.show-who-were-looking-for', $project)" :active="request()->routeIs(locale() . '.projects.show-who-were-looking-for')">{{ __('Who we’re looking for') }}</x-nav-link>
                <x-nav-link :href="localized_route('projects.show-accessibility-and-accomodations', $project)" :active="request()->routeIs(locale() . '.projects.show-accessibility-and-accomodations')">{{ __('Accessibility and accomodations') }}</x-nav-link>
                @if($project->completed())
                <x-nav-link :href="localized_route('projects.show-community-experiences', $project)" :active="request()->routeIs(locale() . '.projects.show-community-experiences')">{{ __('Community experiences') }}</x-nav-link>
                @endif
            </ul>
        </nav>

        <div class="stack">
        @if(request()->routeIs(locale() . '.projects.show'))
            <h2>{{ __('Project overview') }}</h2>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('project overview') . '</span>']) !!}</a></p>
            @endcan
            @include('projects.partials.project-overview', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.projects.show-who-were-looking-for'))
            <h2>{{ __('Who we’re looking for') }}</h2>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('who we’re looking for') . '</span>']) !!}</a></p>
            @endcan
            @include('projects.partials.who-were-looking-for', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.projects.show-accessibility-and-accomodations'))
            <h2>{{ __('Accessibility and accomodations') }}</h2>
            @can('update', $project)
            <p><a class="button" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('accessibility and accomodations') . '</span>']) !!}</a></p>
            @endcan
            @include('projects.partials.accessibility-and-accomodations', ['level' => 3])
        @elseif(request()->routeIs(locale() . '.projects.show-community-experiences'))
            <h2>{{ __('Community experiences') }}</h2>
            @include('projects.partials.community-experiences', ['level' => 3])
        @endif
        </div>
    </div>

</x-app-wide-layout>
