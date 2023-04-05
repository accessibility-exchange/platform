<x-app-layout page-width="wide">
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

    <div class="-mb-8 space-y-16 px-0">
        <div class="stack w-full" x-data="vimeoPlayer({
            url: @if (locale() === 'en') 'https://vimeo.com/789854538'
                @elseif (locale() === 'fr')
                'https://vimeo.com/789771460'
                @elseif (locale() === 'asl')
                'https://vimeo.com/788818374/779a5e9913'
                @elseif (locale() === 'lsq')
                'https://vimeo.com/789827141' @endif,
            byline: false,
            dnt: true,
            pip: true,
            portrait: false,
            responsive: true,
            speed: true,
            title: false
        })" @ended="player().setCurrentTime(0)">
        </div>
        <x-section class="stack:xl" aria-labelledby="definitions">
            <h2 class="text-center" id="definitions">{{ __('What do we mean when we say “Community organizations”?') }}
            </h2>
            <x-interpretation name="{{ __('What do we mean when we say “Community organizations”?', [], 'en') }}" />
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
            <div class="text-center">
                <h2 id="what">{{ __('What you can do on this website') }}</h2>
                <x-interpretation name="{{ __('What you can do on this website', [], 'en') }}" />
                <p>{{ __('You can choose how you would like to take part:') }}</p>
            </div>

            <div class="grid">
                <div class="stack border--magenta border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Be an Accessibility Consultant') }}</h3>
                    <p>{{ __('Help organizations and businesses design their consultations, and potentially help facilitate these consultations.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-accessibility-consultants') }}">{{ __('Learn more about being an Accessibility Consultant') }}</a>
                    </p>
                </div>

                <div class="stack border--yellow border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Be a Community Connector') }}</h3>
                    <p>{{ __('Connect members of your community with governments and businesses who are looking for Consultation Participants. Help them learn how to best work with your community.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-community-connectors') }}">{{ __('Learn more about being a Community Connector') }}</a>
                    </p>
                </div>

                <div class="stack border--turquoise border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Get input for your projects') }}</h3>
                    <p>{{ __('Recruit individuals who are Deaf or have disabilities to give input on your own projects.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-get-input') }}">{{ __('Learn more about getting input for your projects') }}</a>
                    </p>
                </div>

                <div class="stack border--lavender border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Be a Consultation Participant') }}</h3>
                    <p>{{ __('Participate in consultations for organizations and businesses who are working on accessibility projects, and get paid for your participation.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.organization-consultation-participants') }}">{{ __('Learn more about being a Consultation Participant') }}</a>
                    </p>
                </div>
            </div>
        </x-section>

        <x-section class="accent--color text-center">
            @include('partials.have-more-questions')
        </x-section>

        @guest
            <x-section class="full accent" aria-labelledby="join">
                <div class="center center:wide stack stack:xl">
                    <h2 class="text-center" id="join">{{ __('Join our accessibility community') }}</h2>
                    <x-interpretation name="{{ __('Join our accessibility community', [], 'en') }}" namespace="join" />
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

</x-app-layout>
