<x-app-wide-layout>
    <x-slot name="title">{{ __('Get input for your projects') }}</x-slot>
    <x-slot name="header">
        <div class="full -mt-12 bg-turquoise-2 py-12">
            <div class="center center:wide stack">
                <ol class="breadcrumbs" role="list">
                    <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
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

                <p>{{ __('As a :organizationType, you can engage with individuals to get input for your projects.', [
                    'organizationType' => request()->localizedRouteIs('about.regulated-organization-get-input')
                        ? __('Regulated Organization')
                        : __('Community Organization'),
                ]) }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-section class="stack:lg" aria-labelledby="how">
            <div class="center text-center">
                <h2 id="how">{!! __('How does getting input for your projects work?') !!}</h2>
            </div>
            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-turquoise-2" width="915" height="515" />
                </x-slot>
                <div class="stack flex h-full flex-col justify-center">
                    <h3>{{ __('Sign up and share more about your organization') }}</h3>
                    <p>{{ __('This information will help potential Consultation Participants on the website like individuals with disabilities, and individuals who are Deaf learn about your organization.') }}
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-turquoise-2" width="915" height="515" />
                </x-slot>
                <div class="stack flex h-full flex-col justify-center">
                    <h3>{{ __('Share more about your projects and who you are looking to engage') }}</h3>
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
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-turquoise-2" width="915" height="515" />
                </x-slot>
                <div class="stack flex h-full flex-col justify-center">
                    <h3>{{ __('Work directly with people on your accessibility project') }}</h3>
                    <p>{{ __('Once individuals agree to work on your project as Consultation Participants, you can reach out to them directly to coordinate how and when to work together.') }}</a>
                    </p>
                </div>
            </x-media-text>
        </x-section>

        <x-section class="align:center mt-16 bg-turquoise-2">
            <p class="h3">
                {{ __('Have more questions?') }}<br />
                {{ __('Call our support line at :number', ['number' => phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA')]) }}
            </p>
        </x-section>

        @guest
            <x-section class="full mt-16 bg-grey-2" aria-labelledby="join">
                <div class="center center:wide stack stack:xl">
                    <h2 class="text-center" id="join">{{ __('Join our accessibility community') }}</h2>
                    <div class="grid">
                        <div class="stack">
                            <h3>{{ __('Sign up online') }}</h3>
                            <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                        </div>
                        <div class="stack">
                            <h3>{{ __('Sign up on the phone') }}</h3>
                            <p>{{ __('Call our support line at :number', ['number' => phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA')]) }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-section>
        @endguest
    </div>

</x-app-wide-layout>
