<x-app-wide-layout>
    <x-slot name="title">{{ __('Projects') }}</x-slot>
    <x-slot name="header">
        <div class="full bg-white -mt-12 py-12 border-b-grey-3 border-solid border-b border-x-0 border-t-0">
            <div class="center center:wide">
                <h1 itemprop="name" id="projects">
                    {{ __('Projects') }}
                </h1>
                <a href="{{ localized_route('projects.index') }}" class="cta secondary">{{ __('Browse all projects') }}</a>
            </div>
        </div>
    </x-slot>

    <nav aria-labelledby="projects" class="full bg-white mb-12 shadow-md">
        <div class="center center:wide">
            <ul role="list" class="flex gap-6 -mt-4">
                @switch($user->context)
                    @case('organization')
                        <li class="w-full">
                            <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-projects')" :active="request()->localizedRouteIs('projects.my-projects')">
                                {{ __('Projects I am contracted for') }}
                            </x-nav-link>
                        </li>
                        <li class="w-full">
                            <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-participating-projects')" :active="request()->localizedRouteIs('projects.my-participating-projects')">
                                {{ __('Projects I am participating in') }}
                            </x-nav-link>
                        </li>
                        <li class="w-full">
                            <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-running-projects')" :active="request()->localizedRouteIs('projects.my-running-projects')">
                                {{ __('Projects I am running') }}
                            </x-nav-link>
                        </li>
                        @break
                    @case('regulated-organization')
                        <li class="w-full">
                            <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-projects')" :active="request()->localizedRouteIs('projects.my-projects')">
                                {{ __('Projects I am running') }}
                            </x-nav-link>
                        </li>
                        @break
                    @default
                        <li class="w-full">
                            <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-projects')" :active="request()->localizedRouteIs('projects.my-projects')">
                                {{ __('Projects I am participating in') }}
                            </x-nav-link>
                        </li>
                        <li class="w-full">
                            <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-contracted-projects')" :active="request()->localizedRouteIs('projects.my-contracted-projects')">
                                {{ __('Projects I am contracted for') }}
                            </x-nav-link>
                        </li>
                @endswitch
            </ul>
        </div>
    </nav>

    @switch($user->context)
        @case('organization')
            @include(isset($section) ? 'projects.my-projects.'.$section : 'projects.my-projects.contracted')
            @break
        @case('regulated-organization')
            @include('projects.my-projects.running')
            @break
        @default
            @include(isset($section) ? 'projects.my-projects.'.$section : 'projects.my-projects.participating')
    @endswitch

</x-app-wide-layout>
