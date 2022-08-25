<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
        @can('update', $project)
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        @else
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('Projects') }}</a></li>
            <li><a href="{{ localized_route('projects.index') }}">{{ __('Browse all projects') }}</a></li>
        @endcan
        </ol>
        <h1 id="project">{{ $project->name }}</h1>
        <div class="stack">
            <p class="h4">{!! __('Accessibility project by :projectable', ['projectable' => '<a href="' . localized_route('regulated-organizations.show', $project->projectable) . '">' . $project->projectable->name . '</a>']) !!}</p>
            <p>{!! $project->timeframe() !!}</p>
            <div class="repel">
                @if($project->checkStatus('draft'))
                    <span class="badge">{{ __('Draft') }}</span>
                @elseif($project->started())
                    <span class="badge">{{ __('In progress') }}</span>
                @elseif($project->finished())
                    <span class="badge">{{ __('Completed') }}</span>
                @else
                    <span class="badge">{{ __('Upcoming') }}</span>
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
                @can('manage', $project)
                    <a class="ml-auto" href="{{ localized_route('projects.manage', $project) }}">{{ __('Manage this project') }}</a>
                @endcan
                @can('publish', $project)
                    @if($project->checkStatus('published'))
                    <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <button class="secondary" name="unpublish" value="1">{{ __('Unpublish') }}</button>
                    </form>
                    @elseif($project->checkStatus('draft') && $project->isPublishable())
                    <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <button class="secondary" name="publish" value="1">{{ __('Publish') }}</button>
                    </form>
                    @endif
{{--                    <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Manage project') }}</a>--}}
                @endcan
            </div>
        </div>
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
            <h2 class="repel">{{ __('Project overview') }} @can('update', $project)<a class="cta secondary" href="{{ localized_route('projects.edit', $project) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Project overview') . '</span>']) !!}</a>@endcan</h2>
            @include('projects.partials.overview')
        @elseif(request()->localizedRouteIs('projects.show-team'))
            <h2 class="repel">{{ __('Project team') }} @can('update', $project)<a class="cta secondary" href="{{ localized_route('projects.edit', ['project' => $project, 'step' => 2]) }}">{!! __('Edit :section', ['section' => '<span class="visually-hidden">' . __('Project team') . '</span>']) !!}</a>@endcan</h2>
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
