<x-app-wide-layout>
    <x-slot name="title">{{ __('How this works for Community Organizations') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
        </ol>
        <h1>
            <span class="font-medium">{{ __('How this works for') }}</span><br />
            {{ __('Community Organizations') }}
        </h1>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-placeholder class="text-blue-6" />

        <x-section class="stack:xl" aria-labelledby="definitions">
            <h2 class="text-center" id="definitions">{{ __('What do we mean when we say “Community organizations”?') }}
            </h2>
            <div class="grid">
                <div class="stack">
                    <h3>{{ __('Disability and Deaf representative organizations') }}</h3>
                    <p>{{ __('These organizations, coalitions, cross-disability or umbrella groups are made up of, and controlled by, persons with disabilities, Deaf persons, and/or their family members. These organizations were created to advance and defend the rights of persons with disabilities.') }}
                    </p>
                </div>
                <div class="stack">
                    <h3>{{ __('Disability and Deaf support organizations') }}</h3>
                    <p>{{ __('These organizations provide services to people with disabilities and/or Deaf persons, advocate on their behalf, undertake research, provide training and awareness building, and/or deliver accessibility services. ') }}
                    </p>
                </div>
                <div class="stack">
                    <h3>{{ __('Other civil society organizations relevant to people with disabilities, Deaf people, and supporters') }}
                    </h3>
                    <p>{{ __('These organizations have constituencies which include persons with disabilities, Deaf persons, and family members. Disability and Deaf services are not the primary mandate of these organizations. ') }}
                    </p>
                </div>
            </div>
        </x-section>

        <x-section class="stack:lg" aria-labelledby="what">
            <div class="align:center">
                <h2 id="what">{{ __('What you can do on this website') }}</h2>
                <p>{{ __('You can choose how you would like to take part:') }}</p>
            </div>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-magenta-2" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-magenta-2 pt-8">
                    <h3>{{ __('Be an Accessibility Consultant') }}</h3>
                    <p>{{ __('Help organizations and businesses design their consultations, and potentially help facilitate these consultations.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-accessibility-consultants') }}">{{ __('Learn more about being an Accessibility Consultant') }}</a>
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-yellow-3" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-yellow-3 pt-8">
                    <h3>{{ __('Be a Community Connector') }}</h3>
                    <p>{{ __('Connect members of your community with governments and businesses who are looking for Consultation Participants. Help them learn how to best work with your community.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-community-connectors') }}">{{ __('Learn more about being a Community Connector') }}</a>
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-turquoise-5" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-turquoise-5 pt-8">
                    <h3>{{ __('Get input for your projects') }}</h3>
                    <p>{{ __('Recruit individuals who are Deaf or have disabilities to give input on your own projects.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-get-input') }}">{{ __('Learn more about getting input for your projects') }}</a>
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-lavender-3" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-lavender-3 pt-8">
                    <h3>{{ __('Be a Consultation Participant') }}</h3>
                    <p>{{ __('Participate in consultations for organizations and businesses who are working on accessibility projects, and get paid for your participation.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-consultation-participants') }}">{{ __('Learn more about being a Consultation Participant') }}</a>
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
