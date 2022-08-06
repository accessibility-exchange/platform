<x-app-wide-tabbed-layout>
    <x-slot name="title">{{ __('Projects') }}</x-slot>
    <x-slot name="header">
        <h1 itemprop="name" id="projects">
            {{ __('Projects') }}
        </h1>
        <a href="{{ localized_route('projects.index') }}" class="cta secondary">{{ __('Browse all projects') }}</a>
    </x-slot>
    @if(
        ($user->context === 'organization' && ($user->organization->isConsultant() || $user->organization->isConnector() || $user->organization->isParticipant()))
        || ($user->context === 'individual' && ($user->individual->isConsultant() || $user->individual->isConnector()) && $user->individual->isParticipant())
    )
    <nav aria-labelledby="projects" class="full bg-white mb-12 shadow-md">
        <div class="center center:wide">
            <ul role="list" class="flex gap-6 -mt-4">
                @if($user->context === 'organization')
                    @if($user->organization->isConsultant() || $user->organization->isConnector())
                    <li class="w-full">
                        <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-projects')" :active="request()->localizedRouteIs('projects.my-projects')">
                            {{ __('Projects I am contracted for') }}
                        </x-nav-link>
                    </li>
                    @endif
                    @if($user->organization->isParticipant())
                    <li class="w-full">
                        <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="($user->organization->isConsultant() || $user->organization->isConnector()) ? localized_route('projects.my-participating-projects') : localized_route('projects.my-projects')" :active="($user->organization->isConsultant() || $user->organization->isConnector()) ? request()->localizedRouteIs('projects.my-participating-projects') : request()->localizedRouteIs('projects.my-projects')">
                            {{ __('Projects I am participating in') }}
                        </x-nav-link>
                    </li>
                    @endif
                    <li class="w-full">
                        <x-nav-link class="inline-flex items-center justify-center w-full border-t-0" :href="localized_route('projects.my-running-projects')" :active="request()->localizedRouteIs('projects.my-running-projects')">
                            {{ __('Projects I am running') }}
                        </x-nav-link>
                    </li>
                @endif
                @if($user->context === 'individual')
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
                @endif
            </ul>
        </div>
    </nav>
    @endif

    @switch($user->context)
        @case('organization')
            @include(isset($section) ? 'projects.my-projects.'.$section : 'projects.my-projects.running')
            @break
        @case('regulated-organization')
            @include('projects.my-projects.running')
            @break
        @default
            @include(isset($section) ? 'projects.my-projects.'.$section : 'projects.my-projects.participating')
    @endswitch

</x-app-wide-tabbed-layout>
