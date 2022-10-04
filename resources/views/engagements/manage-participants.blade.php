<x-app-wide-tabbed-layout>
    <x-slot name="title">
        @section('title'){{ __('Manage participants') }}@show
        </x-slot>
        <x-slot name="header">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
                <li><a href="{{ localized_route('projects.manage', $project) }}">{{ $project->name }}</a></li>
                <li><a href="{{ localized_route('engagements.manage', $engagement) }}">{{ $engagement->name }}</a></li>
                @yield('breadcrumbs')
            </ol>
            <h1 id="project">
                {{ __('Manage participants') }}
            </h1>
        </x-slot>

    @section('navigation')
        <nav class="full mb-12 bg-white shadow-md"
            aria-labelledby="{{ __(':name participants navigation', ['name' => $engagement->name]) }}">
            <div class="center center:wide">
                <ul class="-mt-4 flex gap-6" role="list">
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('engagements.manage-participants', $engagement)"
                            :active="request()->localizedRouteIs('engagements.manage-participants', $engagement)">
                            {{ __('Participants') }}
                        </x-nav-link>
                    </li>
                    <li class="w-full">
                        <x-nav-link class="inline-flex w-full items-center justify-center border-t-0" :href="localized_route('engagements.manage-access-needs', $engagement)"
                            :active="request()->localizedRouteIs('engagements.manage-access-needs', $engagement)">
                            {{ __('Access Needs') }}
                        </x-nav-link>
                    </li>
                </ul>
            </div>
        </nav>
    @show

    @section('content')
        <div class="repel">
            <h2>{{ __('Participants') }}</h2>
            @can('manageParticipants', $engagement)
                <a class="cta secondary with-icon" href="#">
                    <x-heroicon-o-plus-circle role="presentation" aria-hidden="true" />
                    {{ __('Add participant') }}
                </a>
            @endcan
        </div>
    @show
</x-app-wide-tabbed-layout>
