<x-app-wide-tabbed-layout>
    <x-slot name="title">{{ __('Projects') }}</x-slot>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 id="projects" itemprop="name">
                {{ __('Projects') }}
            </h1>
            <a class="cta secondary"
                href="{{ localized_route('projects.all-projects') }}">{{ __('Browse all projects') }}</a>
        </div>
    </x-slot>
    @if (($user->context === 'organization' &&
        ($user->organization->isConsultant() ||
            $user->organization->isConnector() ||
            $user->organization->isParticipant())) ||
        ($user->context === 'individual' &&
            ($user->individual->isConsultant() || $user->individual->isConnector()) &&
            $user->individual->isParticipant()))
        <nav class="full mb-12 bg-white shadow-md" aria-labelledby="projects">
            <div class="center center:wide">
                <ul class="-mt-4 flex gap-6" role="list">
                    @if ($user->context === 'organization')
                        @if ($user->organization->isConsultant() || $user->organization->isConnector())
                            <li class="w-full">
                                <x-nav-link class="inline-flex w-full items-center justify-center border-t-0"
                                    :href="localized_route('projects.my-projects')" :active="request()->localizedRouteIs('projects.my-projects')">
                                    {{ __('Projects I am contracted for') }}
                                </x-nav-link>
                            </li>
                        @endif
                        @if ($user->organization->isParticipant())
                            <li class="w-full">
                                <x-nav-link class="inline-flex w-full items-center justify-center border-t-0"
                                    :href="$user->organization->isConsultant() || $user->organization->isConnector()
                                        ? localized_route('projects.my-participating-projects')
                                        : localized_route('projects.my-projects')" :active="$user->organization->isConsultant() || $user->organization->isConnector()
                                        ? request()->localizedRouteIs('projects.my-participating-projects')
                                        : request()->localizedRouteIs('projects.my-projects')">
                                    {{ __('Projects I am participating in') }}
                                </x-nav-link>
                            </li>
                        @endif
                        <li class="w-full">
                            <x-nav-link class="inline-flex w-full items-center justify-center border-t-0"
                                :href="localized_route('projects.my-running-projects')" :active="request()->localizedRouteIs('projects.my-running-projects')">
                                {{ __('Projects I am running') }}
                            </x-nav-link>
                        </li>
                    @endif
                    @if ($user->context === 'individual')
                        <li class="w-full">
                            <x-nav-link class="inline-flex w-full items-center justify-center border-t-0"
                                :href="localized_route('projects.my-projects')" :active="request()->localizedRouteIs('projects.my-projects')">
                                {{ __('Projects I am participating in') }}
                            </x-nav-link>
                        </li>
                        <li class="w-full">
                            <x-nav-link class="inline-flex w-full items-center justify-center border-t-0"
                                :href="localized_route('projects.my-contracted-projects')" :active="request()->localizedRouteIs('projects.my-contracted-projects')">
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
            @include(isset($section) ? 'projects.my-projects.' . $section : 'projects.my-projects.running')
        @break

        @case('regulated-organization')
            @include('projects.my-projects.running')
        @break

        @default
            @include(isset($section) ? 'projects.my-projects.' . $section : 'projects.my-projects.participating')
    @endswitch

    <div class="full -mb-8 mt-12 bg-turquoise-2 py-12">
        <div class="center center:wide stack text-center">
            <h2>{{ __('Browse all projects') }}</h2>
            <p>{{ __('This includes projects by Regulated Organizations and Community Organizations.') }}</p>
            <p class="mt-8"><a class="cta"
                    href="{{ localized_route('projects.all-projects') }}">{{ __('Browse all projects') }}</a>
            </p>
        </div>
    </div>
</x-app-wide-tabbed-layout>
