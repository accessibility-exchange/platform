<x-app-wide-tabbed-layout>
    <x-slot name="title">
        @section('title'){{ __('Manage project') }}@show
        </x-slot>
        <x-slot name="header">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
                @yield('breadcrumbs')
            </ol>
            <h1 id="project">
                {{ $project->name }}
            </h1>
            <div class="flex flex-col gap-6 md:flex-row md:justify-between">
                <div class="stack">
                    <p><strong>{{ __('Project duration') }}</strong><br />
                        {{ $project->start_date && $project->end_date ? $project->start_date->translatedFormat('F Y') . ' – ' . $project->end_date?->translatedFormat('F Y') : 'n/a' }}
                    </p>
                    <div class="badge badge--status">{{ $project->status }}</div>
                </div>
                {{-- TODO: cancel project --}}
                {{-- <div> --}}
                {{-- <button class="borderless destructive">{{ __('Cancel project') }}</button> --}}
                {{-- </div> --}}
            </div>
        </x-slot>

    @section('navigation')
        <nav class="nav--tabbed" aria-labelledby="{{ __(':name navigation', ['name' => $project->name]) }}">
            <div class="center center:wide">
                <ul class="-mt-4 flex gap-6" role="list">
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('projects.manage', $project)"
                            :active="request()->localizedRouteIs('projects.manage', $project)">
                            {{ __('Manage') }}
                        </x-nav-link>
                    </li>
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('projects.manage-estimates-and-agreements', $project)"
                            :active="request()->localizedRouteIs(
                                'projects.manage-estimates-and-agreements',
                                $project,
                            )">
                            {{ __('Estimates and agreements') }}
                        </x-nav-link>
                    </li>
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('projects.suggested-steps', $project)"
                            :active="request()->localizedRouteIs('projects.suggested-steps', $project)">
                            {{ __('Suggested steps') }}
                        </x-nav-link>
                    </li>
                </ul>
            </div>
        </nav>
    @show

    @section('content')
        <h2 class="visually-hidden">{{ __('Manage') }}</h2>

        <x-manage-grid>
            <x-manage-columns class="col-start-1 col-end-2">
                {{-- TODO: manage participant selection criteria
                <x-manage-section :title="__('Participant selection criteria')">
                </x-manage-section> --}}
            </x-manage-columns>
            <x-manage-columns class="col-start-2 col-end-4">
                <x-manage-section :title="__('Engagement')">
                    <p>{{ __('An engagement involves a group of people participating in one set way (for example, a focus group or survey).') }}
                    </p>
                    @if ($project->allEngagements->count())
                        <a class="cta secondary"
                            href="{{ localized_route('engagements.show-language-selection', $project) }}">
                            @svg('heroicon-o-plus-circle')
                            {{ __('Create engagement') }}
                        </a>
                    @endif
                    @forelse($project->allEngagements as $engagement)
                        <x-card.engagement :model="$engagement" :level="4" />
                    @empty
                        <div class="box box--alt stack">
                            <p>{{ __('You have not added any engagements yet.') }}</p>
                            <p>
                                <a class="cta secondary"
                                    href="{{ localized_route('engagements.show-language-selection', $project) }}">
                                    @svg('heroicon-o-plus-circle')
                                    {{ __('Create engagement') }}
                                </a>
                        </div>
                    @endforelse
                </x-manage-section>
            </x-manage-columns>
        </x-manage-grid>
    @show
</x-app-wide-tabbed-layout>
