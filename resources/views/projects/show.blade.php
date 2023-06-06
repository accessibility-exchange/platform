<x-app-layout page-width="wide">
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        @if (auth()->hasUser() &&
            auth()->user()->isAdministrator() &&
            $project->projectable->checkStatus('suspended'))
            @push('banners')
                <x-banner type="error" icon="heroicon-s-ban">{{ __('This account has been suspended.') }}</x-banner>
            @endpush
        @endif
        @if ($project->checkStatus('draft'))
            @push('banners')
                <x-banner type="warning" icon="">{{ __('You are previewing your project page.') }}</x-banner>
            @endpush
        @endif
        <ol class="breadcrumbs" role="list">
            @can('update', $project)
                <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            @else
                <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('Projects') }}</a></li>
                <li><a href="{{ localized_route('projects.all-projects') }}">{{ __('Browse all projects') }}</a></li>
            @endcan
        </ol>
        <h1 id="project">{{ $project->name }}</h1>
        <div class="stack">
            <p class="h4">
                {{ safe_inlineMarkdown('Accessibility project by [:projectable](:url)', [
                    'projectable' => $project->projectable->name,
                    'url' => localized_route('regulated-organizations.show', $project->projectable),
                ]) }}
            </p>

            <p><x-timeframe :start="$project->start_date" :end="$project->end_date" /></p>

            <div class="repel">
                <span class="badge">{{ $project->status }}</span>
                @can('manage', $project)
                    <a class="ml-auto"
                        href="{{ localized_route('projects.manage', $project) }}">{{ __('Manage this project') }}</a>
                @endcan
                @can('update', $project)
                    @if ($project->checkStatus('published'))
                        <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST"
                            novalidate>
                            @csrf
                            @method('PUT')
                            <button class="secondary" name="unpublish" value="1">{{ __('Unpublish') }}</button>
                        </form>
                    @elseif($project->checkStatus('draft') && $project->isPublishable())
                        <span class="badge">{{ __('Draft mode') }}</span>
                        <form action="{{ localized_route('projects.update-publication-status', $project) }}" method="POST"
                            novalidate>
                            @csrf
                            @method('PUT')
                            <button class="secondary" name="publish" value="1"
                                @cannot('publish', $project) @ariaDisabled @endcannot>{{ __('Publish') }}</button>
                        </form>
                    @endif
                    {{-- <a class="button" href="{{ localized_route('projects.manage', $project) }}">{{ __('Manage project') }}</a> --}}
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
                    {{-- TODO: Enable when there is the ability to upload reports --}}
                    {{-- <li>
                        <x-nav-link :href="localized_route('projects.show-outcomes', $project)" :active="request()->localizedRouteIs('projects.show-outcomes')">
                            {{ __('Outcomes and reports') }}
                        </x-nav-link>
                    </li> --}}
                </ul>
            </nav>
        </div>
        <div class="stack">
            @if (request()->localizedRouteIs('projects.show'))
                <x-section-heading :name="__('Project overview')" :model="$project" :href="localized_route('projects.edit', $project)" />
                @include('projects.partials.overview')
            @elseif(request()->localizedRouteIs('projects.show-team'))
                <x-section-heading :name="__('Project team')" :model="$project" :href="localized_route('projects.edit', ['project' => $project, 'step' => 2])" />
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

</x-app-layout>
