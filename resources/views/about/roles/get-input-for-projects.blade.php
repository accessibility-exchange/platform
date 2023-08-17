<x-app-layout page-width="wide" header-class="full header--regulated-organization">
    <x-slot name="title">{{ __('Get input for your projects') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack pb-12 pt-4">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
                @if (request()->localizedRouteIs('about.regulated-organization-get-input'))
                    <li><a
                            href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for Regulated Organizations') }}</a>
                    </li>
                @elseif(request()->localizedRouteIs('about.organization-get-input'))
                    <li><a
                            href="{{ localized_route('about.for-community-organizations') }}">{{ __('How this works for Community Organizations') }}</a>
                    </li>
                @endif
            </ol>
            <h1 class="w-1/2">
                {{ __('Get input for your projects') }}
            </h1>

            @if (request()->localizedRouteIs('about.regulated-organization-get-input'))
                <x-interpretation name="{{ __('Get input for your projects', [], 'en') }}"
                    namespace="get_input_for_projects-regulated_organization" />
                <p>{{ __('As a :organizationType, you can engage with individuals to get input for your projects.', ['organizationType' => __('Regulated Organization')]) }}
                </p>
            @else
                <x-interpretation name="{{ __('Get input for your projects', [], 'en') }}"
                    namespace="get_input_for_projects-community_organization" />
                <p>{{ __('As a :organizationType, you can engage with individuals to get input for your projects.', ['organizationType' => __('Community Organization')]) }}
                </p>
            @endif
        </div>
    </x-slot>

    <div class="-mb-8 space-y-16 px-0">
        <x-section class="stack:lg" aria-labelledby="how">
            <div class="center text-center">
                <h2 id="how">{{ __('How does getting input for your projects work?') }}</h2>
                <x-interpretation name="{{ __('How does getting input for your projects work?', [], 'en') }}"
                    namespace="get_input_for_projects" />
            </div>
            <div class="grid">
                <div class="stack border--turquoise border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('1. Sign up and share more about your organization') }}</h3>
                    <x-interpretation name="{{ __('1. Sign up and share more about your organization', [], 'en') }}"
                        namespace="get_input_for_projects" />
                    <p>{{ __('This information will help potential Consultation Participants on the website like individuals with disabilities, and individuals who are Deaf learn about your organization.') }}
                    </p>
                </div>

                <div class="stack border--turquoise border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('2. Share more about your projects and who you are looking to engage') }}</h3>
                    <x-interpretation
                        name="{{ __('2. Share more about your projects and who you are looking to engage', [], 'en') }}"
                        namespace="get_input_for_projects" />
                    <p>{{ __('Organizations can decide which criteria they would like the participants for a project to have. They then have a choice between:') }}
                    <ul>
                        <li>{{ __('creating an open project, where anyone who matches their criteria can sign up. ') }}
                        </li>
                        @if (request()->localizedRouteIs('about.regulated-organization-get-input'))
                            <li>{{ __('using the matching service to match the regulated organization with a group of people who meet the criteria.') }}
                            </li>
                        @endif
                        <li>{{ __('connecting to a Community Connector to help recruit Consultation Participants.') }}
                        </li>
                    </ul>
                    </p>
                </div>

                <div class="stack border--turquoise border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('3. Work directly with people on your accessibility project') }}</h3>
                    <x-interpretation
                        name="{{ __('3. Work directly with people on your accessibility project', [], 'en') }}"
                        namespace="get_input_for_projects" />
                    <p>{{ __('Once individuals agree to work on your project as Consultation Participants, you can reach out to them directly to coordinate how and when to work together.') }}</a>
                    </p>
                </div>
            </div>
        </x-section>

        <x-section class="accent--color text-center">
            @include('partials.have-more-questions')
        </x-section>

        @include('partials.join')
    </div>

</x-app-layout>
