<x-app-layout page-width="wide" header-class="full header--connectors stack">
    <x-slot name="title">{{ __('Community Connectors') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack pb-12 pt-4">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
                @if (request()->localizedRouteIs('about.individual-community-connectors'))
                    <li><a
                            href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for individuals') }}</a>
                    </li>
                @elseif(request()->localizedRouteIs('about.organization-community-connectors'))
                    <li><a
                            href="{{ localized_route('about.for-community-organizations') }}">{{ __('How this works for Community Organizations') }}</a>
                    </li>
                @endif
            </ol>
            <h1 class="w-1/2">
                {{ __('Community Connectors') }}
            </h1>
            <x-interpretation name="{{ __('Community Connectors', [], 'en') }}" />
        </div>
    </x-slot>

    <div class="-mb-8 space-y-16 px-0">
        <x-section class="stack:lg" aria-labelledby="experiences">
            <h2 class="text-center" id="experiences">
                {{ __('What experiences should I have to be a :role?', ['role' => __('Community Connector')]) }}</h2>
            <x-interpretation class="interpretation--center"
                name="{{ __('What experiences should I have to be a :role?', ['role' => __('Community Connector', [], 'en')], 'en') }}" />
            <div class="stack flex h-full flex-col items-center justify-center">
                <p>{{ __('Ideally a Community Connector has:') }}
                <ul class="flex w-1/2 flex-col">
                    <li class="mx-auto">{{ __('lived experience of disability, or of being Deaf, or both') }}</li>
                    <li class="mx-auto">
                        {{ __('a network and is able to conduct effective outreach to people with disabilities and Deaf persons in particular geographic communities and social groups (for example, Indigenous communities).') }}
                    </li>
                </ul>
                </p>
            </div>
        </x-section>

        <x-section class="stack:lg" aria-labelledby="how">
            <h2 class="text-center" id="how">
                {{ __('How does being a :role work?', ['role' => __('Community Connector')]) }}</h2>
            <x-interpretation class="interpretation--center"
                name="{{ __('How does being a :role work?', ['role' => __('Community Connector', [], 'en')], 'en') }}" />
            <div class="grid">
                <div class="stack border--yellow border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('1. Sign up for the website and build your Community Connector profile') }}</h3>
                    @if (request()->localizedRouteIs('about.individual-community-connectors'))
                        <x-interpretation
                            name="{{ __('1. Sign up for the website and build your Community Connector profile', [], 'en') }}" />
                    @else
                        <x-interpretation
                            name="{{ __('1. Sign up for the website and build your Community Connector profile', [], 'en') }}"
                            namespace="community_connectors-individual" />
                    @endif
                    <p>{{ __('Share some information about yourself, including which communities you are connected to, so governments and businesses can get to know you and how you may be able to help them.') }}
                    </p>
                    @if (request()->localizedRouteIs('about.individual-community-connectors'))
                        <p><a
                                href="{{ localized_route('about.individual-community-connectors-what-we-ask-for') }}">{{ __('What information do we ask for?') }}</a>
                        </p>
                    @endif
                    <p><a href="{{ localized_route('about.privacy-policy') }}">{{ __('Read our privacy policy') }}</a>
                    </p>
                </div>

                <div class="stack border--yellow border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('2. Find projects that are looking for a Community Connector') }}</h3>
                    <x-interpretation
                        name="{{ __('2. Find projects that are looking for a Community Connector', [], 'en') }}" />
                    <p>{{ __('Access governments and businesses who are looking for a Community Connector to help with a project.') }}
                    </p>
                </div>

                <div class="stack border--yellow border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('3. Work directly with governments and businesses') }}</h3>
                    <x-interpretation name="{{ __('3. Work directly with governments and businesses', [], 'en') }}" />
                    <p>{{ __('Coordinate directly with governments and businesses on what help they need and whom they are seeking.') }}</a>
                    </p>
                </div>
            </div>
        </x-section>

        {{-- <x-section aria-labelledby="faq">
            <h2 class="text-center" id="faq">{{ __('Frequently asked questions') }}</h2>

            <p>TODO.</p>
        </x-section> --}}

        <x-section class="accent--color text-center">
            @include('partials.have-more-questions')
        </x-section>

        @include('partials.join')
    </div>
</x-app-layout>
