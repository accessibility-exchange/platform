<x-app-wide-layout>
    <x-slot name="title">{{ __('What information do we ask for?') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for individuals') }}</a></li>
            <li><a href="{{ localized_route('about.individual-consultation-participants') }}">{{ __('Consultation participants') }}</a></li>
        </ol>
        <div class="w-full lg:w-2/3">
            <h1 id="what-we-ask-for">
                {{ __('What information do we ask for?') }}
            </h1>
            <p class="h4">{{ __('Information that we ask consultation participants, accessibility consultants, and community connectors to share.') }}</p>
        </div>
    </x-slot>

    <div class="stack stack:xl mt-16 -mb-8">
        <div class="with-sidebar">
            @include('about.partials.what-we-ask-for-navigation')
            <div class="stack">
                <h2>{{ __('Consultation Participants — Individual') }}</h2>
                <p>{{ __('We ask consultation participants for the following information:') }}</p>
                <x-expander :summary="__('Lived or living experience')" level="3">
                    <p>{{ __('All of the following questions are optional, besides the ones marked as required. For all questions that are multiple choice, there is an option to select “prefer not to answer”.') }}</p>
                    <ul>
                        <li>{{ __('Province/territory (required)') }}</li>
                        <li>{{ __('City/town (required)') }}</li>
                        <li>{{ __('Whether they identify as someone with a disability, Deaf, or a supporter (required)') }}</li>
                        <li>{{ __('Whether they are someone with a disability, what type of disability they experience') }}</li>
                        <li>{{ __('Year of birth') }}</li>
                        <li>{{ __('Gender identity') }}</li>
                        <li>{{ __('Whether they identify with one or more of the 2SLGBTQIA+ identities') }}</li>
                        <li>{{ __('Whether they are transgender') }}</li>
                        <li>{{ __('Whether they are Indigenous') }}</li>
                        <li>{{ __('Ethnoracial identity') }}</li>
                        <li>{{ __('Whether they are a temporary resident, permanent resident, refugee, or immigrant') }}</li>
                        <li>{{ __('First language') }}</li>
                        <li>{{ __('Whether they are from an urban, suburban, rural, or remote area') }}</li>
                        <li>{{ __('Whether they are a single parent or not') }}</li>
                        <li>{{ __('Employment status') }}</li>
                        <li>{{ __('Whether they consider themselves to be living in poverty or financially precarious') }}</li>
                    </ul>
                    <h4>{{ __('Why do we ask this?') }}</h4>
                    <p>{{ __('When creating a group of consultation participants, our website will allow you to get a diverse group of participants in terms of type of disability and those living with other marginalized identities, in order that  many perspectives can be represented.') }}</p>
                    <p>{{ __('Sometimes, governments and businesses also want to talk to people with specific experiences. For example, people from a certain location. Or, people who speak a certain first language.') }}</p>
                    <p>{{ __('Because of these reasons, we ask you to provide this information so we can match you with an engagement that is looking for your experience.') }}</p>
                </x-expander>
                <x-expander :summary="__('Access needs')" level="3">
                    <p>{{ __('We will ask you about what your access needs are to participate in either an in-person meeting or virtual meeting. We also ask whether you have a preference for either in-person or virtual meetings.') }}</p>
                    <h4>{{ __('Why do we ask this?') }}</h4>
                    <p>{{ __('Once you confirm your participation for an engagement, we share your access needs with the government or business that you are working with. This will help them seek out the appropriate service providers and find a way to meet your access need(s).') }}</p>
                </x-expander>
                <x-expander :summary="__('Communication and meeting preferences')" level="3">
                    <p>{{ __('We will ask you about whether you have a preference for either in-person or virtual meetings. We will also ask you what the best way is to contact you, and your contact information.') }}</p>
                    <h4>{{ __('Why do we ask this?') }}</h4>
                    <p>{{ __('Once you confirm your participation for an engagement, we will share your preferred contact method and your contact information with the government or business. This helps them contact you to talk about details of your participation.') }}</p>
                    <p>{{ __('Based on if you prefer in-person or virtual engagements, we will only match you with engagements that are in that format.') }}</p>

                </x-expander>
                <x-expander :summary="__('Language preferences')" level="3">
                    <p>{{ __('We will ask you three things:') }}</p>
                    <ul>
                        <li>{{ __('What language you want to use the website in') }}</li>
                        <li>{{ __('What your first language is') }}</li>
                        <li>{{ __('What other languages are you able to work in') }}</li>
                    </ul>
                    <h4>{{ __('Why do we ask this?') }}</h4>
                    <p>{{ __('The language you want to use the website in will help us change the website into that language.') }}</p>
                    <p>{{ __('Your first language will help us match you to a project that may be looking for someone who uses that first language.') }}</p>
                    <p>{{ __('Your other working languages will help us determine whether governments or businesses may need to provide interpretation or translation for you.') }}</p>
                </x-expander>
                <x-expander :summary="__('Payment information')" level="3">
                    <p>{{ __('We will ask you what method you prefer to be paid in.') }}</p>
                    <h4>{{ __('Why do we ask this?') }}</h4>
                    <p>{{ __('This will help governments and businesses you work with pay you in the way that you want.') }}</p>
                </x-expander>
                <x-expander :summary="__('Areas of interest (optional)')" level="3">
                    <p>{{ __('The Accessible Canada Act has outlined 7 main areas that Governments and businesses have to make accessible. If you would like, you can say which areas you’re interested in. However, this is not required.') }}</p>
                    <h4>{{ __('Why do we ask this?') }}</h4>
                    <p>{{ __('This will help us match you to projects that are working on areas you are interested in.') }}</p>
                </x-expander>
            </div>
        </div>

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
