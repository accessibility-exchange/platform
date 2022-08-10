<x-app-wide-layout>
    <x-slot name="title">{{ __('Community Connectors') }}</x-slot>
    <x-slot name="header">
        <div class="-mt-12 full bg-yellow-3 py-12">
            <div class="center center:wide">
                <ol class="breadcrumbs" role="list">
                    <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
                    @if(request()->localizedRouteIs('about.individual-community-connectors'))
                        <li><a href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for individuals') }}</a></li>
                    @elseif(request()->localizedRouteIs('about.organization-community-connectors'))
                        <li><a href="{{ localized_route('about.for-community-organizations') }}">{{ __('How this works for Community Organizations') }}</a></li>
                    @endif
                </ol>
                <h1 class="w-1/2">
                    {{ __('Community Connectors') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-section aria-labelledby="experiences" class="stack:lg">
            <h2 class="text-center" id="experiences">{!! __('What experiences should I have to be a :role?', ['role' => '<strong>' . __('Community Connector') . '</strong>']) !!}</h2>
            <p>TODO.</p>
        </x-section>

        <x-section aria-labelledby="how" class="stack:lg">
            <h2 class="text-center" id="how">{!! __('How does being a :role work?', ['role' => '<strong>' . __('Community Connector') . '</strong>']) !!}</h2>
            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Sign up for the website and build your Consultant profile') }}</h3>
                    <p>{{ __('Share some information about yourself so governments and businesses can get to know you and what you may be able to help them with.') }}</p>
                    @if(request()->localizedRouteIs('about.individual-community-connectors'))
                        <p><a href="{{ localized_route('about.individual-community-connectors-what-we-ask-for') }}">{{ __('What information do we ask for?') }}</a></p>
                    @endif
                    <p><a href="{{ localized_route('about.privacy-policy') }}">{{ __('Read our privacy policy') }}</a></p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Find projects that are looking for an Accessibility Consultant') }}</h3>
                    <p>{{ __('Access governments and businesses who are looking for an accessibility consultant to help with a project.') }}</p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Work directly with governments and businesses') }}</h3>
                    <p>{{ __('Coordinate directly with governments and businesses on what they’d like you to consult on.') }}</a></p>
                </div>
            </x-media-text>
        </x-section>

        <x-section aria-labelledby="faq">
            <h2 class="text-center" id="faq">{{ __('Frequently asked questions') }}</h2>

            <p>TODO.</p>
        </x-section>

        <x-section class="bg-turquoise-2 align:center mt-16">
            <p class="h3">
                {{ __('Have more questions?') }}<br />
                {{ __('Call our support line at :number', ['number' => phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA')]) }}
            </p>
        </x-section>

        @guest
            <x-section aria-labelledby="join" class="full bg-grey-2 mt-16">
                <div class="center center:wide stack stack:xl">
                    <h2 id="join" class="text-center">{{ __('Join our accessibility community') }}</h2>
                    <div class="grid">
                        <div class="stack">
                            <h3>{{ __('Sign up online') }}</h3>
                            <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                        </div>
                        <div class="stack">
                            <h3>{{ __('Sign up on the phone') }}</h3>
                            <p>{{ __('Call our support line at :number', ['number' => phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA')]) }}</p>
                            @if(request()->localizedRouteIs('about.individual-community-connectors'))
                                <p><a href="#TODO">{{ __('Find a local community organization to help me sign up') }}</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            </x-section>
        @endguest
    </div>

</x-app-wide-layout>
