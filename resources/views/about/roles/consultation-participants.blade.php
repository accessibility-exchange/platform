<x-app-wide-layout>
    <x-slot name="title">{{ __('Consultation Participants') }}</x-slot>
    <x-slot name="header">
        <div class="-mt-12 full bg-lavender-3 py-12">
            <div class="center center:wide">
                <ol class="breadcrumbs" role="list">
                    <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
                    @if(request()->localizedRouteIs('about.individual-consultation-participants'))
                        <li><a href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for individuals') }}</a></li>
                    @elseif(request()->localizedRouteIs('about.organization-consultation-participants'))
                        <li><a href="{{ localized_route('about.for-community-organizations') }}">{{ __('How this works for Community Organizations') }}</a></li>
                    @endif
                </ol>
                <h1 class="w-1/2">
                    {{ __('Consultation Participants') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-section aria-labelledby="experiences" class="stack:lg">
            <h2 id="experiences" class="text-center">{!! __('Who can be a :role?', ['role' => __('Consultation Participant')]) !!}</h2>
            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <p>{{ __('A person who brings lived experience of disability, a member of the Deaf community and/or a supporter of persons who are Deaf, disabled or both.') }}</p>
                    <p>{{ __('Any of the following could be Consultation Participants:')}}
                        <ul>
                            <li>{{('persons with disabilities')}}</li>
                            <li>{{('Deaf persons')}}</li>
                            <li>{{('their supporters')}}</li>
                            <li>{{('persons representing Disability organizations')}}</li>
                            <li>{{('Disability support organizations')}}</li>
                            <li>{{('broader civil society organizations.')}}</li>
                        </ul>
                    </p>
                </div>
            </x-media-text>
        </x-section>

        <x-section aria-labelledby="how" class="stack:lg">
            <div class="align:center">
                <h2 id="how">{!! __('How does being a :role work?', ['role' => '<strong>' . __('Consultation Participant') . '</strong>']) !!}</h2>
            </div>
            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Sign up for the website and share a bit about yourself or your organization') }}</h3>
                    <p>{{ __('If you are willing to share more about your lived experience, we can match you to governments and businesses who are eager to hear from someone like you.') }}</p>
                    @if(request()->localizedRouteIs('about.individual-consultation-participants'))
                    <p><a href="{{ localized_route('about.individual-consultation-participants-what-we-ask-for') }}">{{ __('What information do we ask for?') }}</a></p>
                    @endif
                    <p><a href="{{ localized_route('about.privacy-policy') }}">{{ __('Read our privacy policy') }}</a></p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Wait for the website to match you with a project') }}</h3>
                    <p>{{ __('Our website will match you to a project once one looking for someone with your experience becomes available. Once there is a match, you will get an email or text message asking if you would like to participate - it’s up to you to say yes or no.') }}</p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Work directly with businesses and governments') }}</h3>
                    <p>{{ __('You can communicate directly with the business or government to figure out when and how to work on their accessibility project. You will be paid for your work.') }}</a></p>
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
                        </div>
                    </div>
                </div>
            </x-section>
        @endguest
    </div>

</x-app-wide-layout>
