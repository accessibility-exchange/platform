<x-app-wide-layout>
    <x-slot name="title">{{ __('Consultation Participants') }}</x-slot>
    <x-slot name="header">
        <div class="-mt-12 full bg-lavender-3 py-12">
            <div class="center center:wide">
                <ol class="breadcrumbs" role="list">
                    <li><button x-data @click="history.back()">{{ __('Back') }}</button></li>
                </ol>
                <h1 class="w-1/2">
                    {{ __('Consultation Participants') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-section aria-labelledby="experiences" class="stack:lg">
            <h2 id="experiences" class="text-center">{!! __('What experiences should I have to be a :role?', ['role' => '<strong>' . __('Consultation Participant') . '</strong>']) !!}</h2>
            <p>{{ __('Coming soon.') }}</p>
            {{-- TODO: Add Experiences --}}
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
                    <h3>{{ __('Sign up for the website and share a bit about yourself') }}</h3>
                    <p>{{ __('If you are willing to share more about your lived experience, we can match you to governments and businesses who are eager to hear from someone like you.') }}</p>
                    <p><a href="#TODO">{{ __('What information do we ask for?') }}</a></p>
                    <p><a href="#TODO">{{ __('Read our privacy policy') }}</a></p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Wait for the website to match you with a project') }}</h3>
                    <p>{{ __('Our website will match you to a project once one becomes available, and is looking for someone with your experience. Once there is a match, you will get an email or text message asking if you would like to participate—it’s up to you to say yes or no.') }}</p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Work directly with governments and businesses') }}</h3>
                    <p>{{ __('You can communicate directly with the business or government to figure out when and how to work on their accessibility project. You will be paid for your work.') }}</a></p>
                </div>
            </x-media-text>
        </x-section>

        <x-section aria-labelledby="faq">
            <h2 class="text-center" id="faq">{{ __('Frequently asked questions') }}</h2>

            <p>{{ __('Coming soon.') }}</p>
            {{-- TODO: Add FAQs --}}
        </x-section>

        <x-section class="bg-turquoise-2 align:center mt-16">
            <p class="h3">
                {{ __('Have more questions?') }}<br />
                {{ __('Call our support line at :number', ['number' => settings()->get('phone', '1-800-123-4567')]) }}
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
                            <p>{{ __('Call our support line at :number', ['number' => settings()->get('phone', '1-800-123-4567')]) }}</p>
                            <p><a href="#TODO">{{ __('Find a local community organization to help me sign up') }}</a></p>
                        </div>
                    </div>
                </div>
            </x-section>
        @endguest
    </div>

</x-app-wide-layout>
