<x-app-wide-layout>
    <x-slot name="title">{{ __('How this works for Federally Regulated Organizations') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
        </ol>
        <h1>
            <span class="font-medium">{{ __('How this works for') }}</span><br />
            {{ __('federally regulated organizations') }}
        </h1>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-placeholder class="text-blue-6" />

        <x-section class="stack:lg" aria-labelledby="what">
            <div class="align:center">
                <h2 id="what">{{ __('What you can do on this website') }}</h2>
            </div>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-turquoise-5" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-turquoise-5 pt-8">
                    <h3>{{ __('Get input on your accessibility projects') }}</h3>
                    <p>{{ __('You can work on any projects related to accessibility on this website. This can be your organization’s Accessibility Plan or Report (as required under the Accessible Canada Act), or getting feedback on your customer service or accessibility in your workplace.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.regulated-organization-get-input') }}">{{ __('Learn more about getting input for your projects') }}</a>
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-lavender-3" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-lavender-3 pt-8">
                    <h3>{{ __('Find Consultation Participants') }}</h3>
                    <p>{{ __('Find people with disabilities, Deaf people and community organizations (for example, disability or other relevant civil society organizations, like Indigenous groups), to consult with on your accessibility project.') }}
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-green-5" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-green-5 pt-8">
                    <h3>{{ __('Access resources and trainings') }}</h3>
                    <p>{{ __('We have a hub of resources and trainings. The materials can help you and your team deepen your understanding of disability and inclusion.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('resource-collections.index') }}">{{ __('Go to our Resource Hub') }}</a>
                    </p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder class="text-magenta-2" width="915" height="515" />
                </x-slot>
                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-magenta-2 pt-8">
                    <h3>{{ __('Tap into our support network') }}</h3>
                    <p>{{ __('Accessibility Consultants could help you design consultations that are inclusive and accessible.') }}
                    </p>
                    <p>{{ __('Community Connectors could help you connect with groups that may be hard to reach otherwise.') }}
                    </p>
                    <p>{{ __('Community organizations could provide research, recommendations, and also support the interpretation of your consultation results to deepen your understanding of Deaf and disability access.') }}
                    </p>
                </div>
            </x-media-text>
        </x-section>

        <x-section aria-labelledby="faq">
            <h2 class="text-center" id="faq">{{ __('Frequently asked questions') }}</h2>

            <p>TODO.</p>
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
                            <h3 class="h4">{{ __('Sign up online') }}</h3>
                            <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                        </div>
                        <div class="stack">
                            <h3 class="h4">{{ __('Request an introductory phone call') }}</h3>
                            <p><a class="cta" href="#TODO"> {{ __('Request a call') }}</a></p>
                        </div>
                        <div class="stack">
                            <h3 class="h4">{{ __('Learn about our pricing') }}</h3>
                            <p><a class="cta" href="{{ localized_route('about.pricing') }}">
                                    {{ __('Go to pricing') }}</a></p>
                        </div>
                    </div>
                </div>
            </x-section>
        @endguest
    </div>

</x-app-wide-layout>
