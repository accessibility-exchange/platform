<x-app-wide-layout>
    <x-slot name="title">{{ __('How this works for Individuals with Disabilities and Deaf Individuals') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessbility Exchange') }}</a></li>
        </ol>
        <h1>
            <span class="font-medium">{{ __('How this works for') }}</span><br />
            {{ __('individuals') }}
        </h1>
    </x-slot>

    <div class="-mb-8 space-y-16">
        <x-placeholder />

        <x-section class="stack:lg" aria-labelledby="what">
            <div class="align:center">
                <h2 id="what">{{ __('What you can do on this website') }}</h2>
                <p>{{ __('You can choose how you would like to take part:') }}</p>
            </div>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-lavender-3 pt-8">
                    <h3>{{ __('Be a Consultation Participant') }}</h3>
                    <p>{{ __('As an individual with a disability, Deaf person, or a supporter, you can participate in consultations by organizations and businesses who are working on accessibility projects and get paid for this. You can also gain access to resources and training on how to do this.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.individual-consultation-participants') }}">{{ __('Learn more about being a Consultation Participant') }}</a>
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-magenta-2 pt-8">
                    <h3>{{ __('Be an Accessibility Consultant') }}</h3>
                    <p>{{ __('Help organizations and businesses design their consultations, and potentially help facilitate these consultations.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.individual-accessibility-consultants') }}">{{ __('Learn more about being an Accessibility Consultant') }}</a>
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-yellow-3 pt-8">
                    <h3>{{ __('Be a Community Connector') }}</h3>
                    <p>{{ __('Connect members of your community with governments and businesses who are looking for Consultation Participants. Help them learn how to best work with your community.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.individual-community-connectors') }}">{{ __('Learn more about being a Community Connector') }}</a>
                    </p>
                </div>
            </x-media-text>
        </x-section>

        <x-section class="align:center accent--color">
            <p class="h3">
                {{ __('Have more questions?') }}<br />
                {{ __('Call our support line at :number', ['number' => phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA')]) }}
            </p>
        </x-section>

        @guest
            <x-section class="full accent" aria-labelledby="join">
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
