<x-app-wide-layout>
    <x-slot name="title">{{ __('What information do we ask for?') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for individuals') }}</a></li>
            <li><a href="{{ localized_route('about.individual-consultation-participants') }}">{{ __('Consultation Participants') }}</a></li>
        </ol>
        <div class="w-full lg:w-2/3">
            <h1 id="what-we-ask-for">
                {{ __('What information do we ask for?') }}
            </h1>
            <p class="h4">{{ __('Information that we ask Consultation Participants, Accessibility Consultants, and Community Connectors to share.') }}</p>
        </div>
    </x-slot>

    <div class="stack stack:xl mt-16 -mb-8">
        <div class="with-sidebar">
            @include('about.partials.what-we-ask-for-navigation')
            <div class="stack">
                <h2>{{ __('Consultation Participants — Individual') }}</h2>
                <p>{{ __('We ask Consultation Participants for the following information:') }}</p>
                <x-expander :summary="__('Lived or living experience')" level="3">
                    <p>{{ __('Consultation Participants are required to share their province/territory, their city/town, and whether or not they identify as someone with a disability, Deaf or a supporter. All of the remaining questions are optional. For multiple choice questions, there is an option to select “prefer not to answer”.') }}</p>
                    <ul>
                        <li>{{ __('The type of disability they experience') }}</li>
                        <li>{{ __('Year of birth') }}</li>
                        <li>{{ __('Gender identity') }}</li>
                        <li>{{ __('Whether they identify with one or more of the 2SLGBTQIA+ identities') }}</li>
                        <li>{{ __('Whether they are Indigenous') }}</li>
                        <li>{{ __('Ethnoracial identity') }}</li>
                        <li>{{ __('Whether they are an immigrant') }}</li>
                        <li>{{ __('First language') }}</li>
                        <li>{{ __('Whether they are from an urban, rural, or remote area') }}</li>
                        <li>{{ __('Whether they are a single parent or not') }}</li>
                        <li>{{ __('Employment status') }}</li>
                        <li>{{ __('Whether they consider themselves to be living in poverty or financially precarious') }}</li>
                    </ul>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('When Federally Regulated Organizations use the matching service to find a group of Consultation Participants, The Accessibility Exchange will create a diverse group of participants in terms of being disabled, Deaf, and other identities. This diversity can maximize the number of perspectives which can be represented.') }}</p>
                    <p>{{ __('Sometimes, governments and businesses also want to talk to people with specific experiences. For example, people from a certain location. Or, people who speak a certain first language.') }}</p>
                    <p>{{ __('In order to create a successful exchange, we ask Consultation Participants to provide this information so that The Accessibility Exchange can match Consultation Participants with an engagement that is looking for someone with your experiences.') }}</p>
                </x-expander>
                <x-expander :summary="__('Access needs')" level="3">
                    <p>{{ __('We will ask you about what your access needs are to participate in either an in-person meeting or virtual meeting. We also ask whether you have a preference for either in-person or virtual meetings.') }}</p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('Once you confirm your participation for an engagement, we share your access needs with the government or business that you are working with. This will help them seek out the appropriate service providers to meet your access need(s).') }}</p>
                    <p>{{ __('Your preferences for in-person or virtual engagements will determine what projects and engagements you are matched to.') }}</p>
                </x-expander>
                <x-expander :summary="__('Communication and meeting preferences')" level="3">
                    <p>{{ __('We will ask you about what is the best way to contact you, and your contact information. We will also ask you about whether you have a preference for either in-person or virtual meetings.') }}</p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('Once you confirm your participation for an engagement, we will share your preferred contact method and your contact information with the government or business. This information enables them to contact you to discuss  the details of your participation.') }}</p>
                    <p>{{ __('Your preferences for in-person or virtual engagements will determine what projects and engagements you are matched to.')}}</p>
                </x-expander>
                <x-expander :summary="__('Language preferences')" level="3">
                    <p>{{ __('We will ask you to indicate:')}}
                        <ul>
                            <li>{{ __('your preferred language for using this website')}}</li>
                            <li>{{ __('your first language') }}</li>
                        </ul>
                    </p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('The language you select for using this website sets the website into that language.')}}
                    <p>{{ __('Your first language will help us match you to a project that may be looking for someone who uses that first language.')}}
                </x-expander>
                <x-expander :summary="__('Payment information')" level="3">
                    <p>{{ __('We will ask you for your preferred method of payment.') }}</p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('This will help you get paid in the way that you want.') }}</p>
                </x-expander>
                <x-expander :summary="__('Areas of interest (optional)')" level="3">
                    <p>{{ __('The Accessible Canada Act has outlined seven main areas that governments and businesses have to make accessible. If you would like, you may indicate which areas are of interest to you. You do not have to answer this question as it is optional. ') }}</p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('Providing this information will help us match you to projects that are working on areas of interest to you.') }}</p>
                </x-expander>
            </div>
        </div>

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
