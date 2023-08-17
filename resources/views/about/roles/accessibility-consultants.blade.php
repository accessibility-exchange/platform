<x-app-layout page-width="wide" header-class="full header--consultants stack">
    <x-slot name="title">{{ __('Accessibility Consultants') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack pb-12 pt-4">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
                @if (request()->localizedRouteIs('about.individual-accessibility-consultants'))
                    <li><a
                            href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for individuals') }}</a>
                    </li>
                @elseif(request()->localizedRouteIs('about.organization-accessibility-consultants'))
                    <li><a
                            href="{{ localized_route('about.for-community-organizations') }}">{{ __('How this works for Community Organizations') }}</a>
                    </li>
                @endif
            </ol>
            <h1 class="w-1/2">
                {{ __('Accessibility Consultants') }}
            </h1>
            <x-interpretation name="{{ __('Accessibility Consultants', [], 'en') }}"
                namespace="accessibility_consultants" />
        </div>
    </x-slot>

    <div class="-mb-8 space-y-16 px-0">
        <x-section class="stack:lg" aria-labelledby="experiences">
            <h2 class="text-center" id="experiences">
                {{ __('What experiences should I have to be an :role?', ['role' => __('Accessibility Consultant')]) }}
            </h2>
            <x-interpretation
                name="{{ __('What experiences should I have to be an :role?', ['role' => __('Accessibility Consultant', [], 'en')], 'en') }}"
                namespace="accessibility_consultants" />
            <div class="stack flex h-full flex-col items-center justify-center">
                <p>{{ __('Ideally, an Accessibility Consultant has:') }}
                <ul class="flex w-1/2 flex-col">
                    <li class="mx-auto">{{ __('lived experience of disability or of being Deaf, or of both') }}</li>
                    <li class="mx-auto">
                        {{ __('experience working with organizations to create inclusive consultations, identify barriers, and create accessibility plans.') }}
                    </li>
                </ul>
                </p>
            </div>
        </x-section>

        <x-section class="stack:lg" aria-labelledby="how">
            <div class="text-center">
                <h2 id="how">
                    {{ __('How does being an :role work?', ['role' => __('Accessibility Consultant')]) }}</h2>
                <x-interpretation
                    name="{{ __('How does being an :role work?', ['role' => __('Accessibility Consultant', [], 'en')], 'en') }}"
                    namespace="accessibility_consultants" />
            </div>
            <div class="grid">
                <div class="stack border--magenta border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('1. Sign up for the website and build your Accessibility Consultant profile') }}</h3>
                    <x-interpretation
                        name="{{ __('1. Sign up for the website and build your Accessibility Consultant profile', [], 'en') }}"
                        namespace="accessibility_consultants" />
                    <p>{{ __('Share some information about yourself so governments and businesses can get to know you and what you may be able to help them with.') }}
                    </p>
                    @if (request()->localizedRouteIs('about.individual-accessibility-consultants'))
                        <p><a
                                href="{{ localized_route('about.individual-accessibility-consultants-what-we-ask-for') }}">{{ __('What information do we ask for?') }}</a>
                        </p>
                    @endif
                    <p><a href="{{ localized_route('about.privacy-policy') }}">{{ __('Read our privacy policy') }}</a>
                    </p>
                </div>

                <div class="stack border--magenta border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('2. Find projects that are looking for an Accessibility Consultant') }}</h3>
                    <x-interpretation
                        name="{{ __('2. Find projects that are looking for an Accessibility Consultant', [], 'en') }}"
                        namespace="accessibility_consultants" />
                    <p>{{ __('Access governments and businesses who are looking for an accessibility consultant to help with a project.') }}
                    </p>
                </div>

                <div class="stack border--magenta border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('3. Work directly with governments and businesses') }}</h3>
                    <x-interpretation name="{{ __('3. Work directly with governments and businesses', [], 'en') }}"
                        namespace="accessibility_consultants" />
                    <p>{{ __('Coordinate directly with governments and businesses on what they would like you to help with.') }}</a>
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
