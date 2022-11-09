<x-app-wide-layout>
    <x-slot name="title">{{ __('Accessibility Consultants') }}</x-slot>
    <x-slot name="header">
        <div class="full header--consultants -mt-12 py-12">
            <div class="center center:wide">
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
            </div>
        </div>
    </x-slot>

    <div class="-mb-8 space-y-16">
        <x-section class="stack:lg" aria-labelledby="experiences">
            <h2 class="text-center" id="experiences">{!! __('What experiences should I have to be an :role?', ['role' => __('Accessibility Consultant')]) !!}</h2>
            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex h-full flex-col justify-center">
                    <p>{{ __('Ideally, an Accessibility Consultant has:') }}
                    <ul>
                        <li>{{ __('lived experience of disability or of being Deaf, or of both') }}</li>
                        <li>{{ __('experience working with organizations to create inclusive consultations, identify barriers, and create accessibility plans.') }}
                        </li>
                    </ul>
                    </p>
                </div>
            </x-media-text>
        </x-section>

        <x-section class="stack:lg" aria-labelledby="how">
            <div class="text-center">
                <h2 id="how">{!! __('How does being an :role work?', ['role' => __('Accessibility Consultant')]) !!}</h2>
            </div>
            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex h-full flex-col justify-center">
                    <h3>{{ __('Sign up for the website and build your Accessibility Consultant profile') }}</h3>
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
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex h-full flex-col justify-center">
                    <h3>{{ __('Find projects that are looking for an Accessibility Consultant') }}</h3>
                    <p>{{ __('Access governments and businesses who are looking for an accessibility consultant to help with a project.') }}
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex h-full flex-col justify-center">
                    <h3>{{ __('Work directly with governments and businesses') }}</h3>
                    <p>{{ __('Coordinate directly with governments and businesses on what they would like you to help with.') }}</a>
                    </p>
                </div>
            </x-media-text>
        </x-section>

        {{-- <x-section aria-labelledby="faq">
            <h2 class="text-center" id="faq">{{ __('Frequently asked questions') }}</h2>

            <p>TODO.</p>
        </x-section> --}}

        <x-section class="accent--color text-center">
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
