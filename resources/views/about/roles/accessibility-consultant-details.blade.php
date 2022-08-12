<x-app-wide-layout>
    <x-slot name="title">{{ __('What information do we ask for?') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for individuals') }}</a></li>
            <li><a href="{{ localized_route('about.individual-accessibility-consultants') }}">{{ __('Accessibility Consultants') }}</a></li>
        </ol>
        <div class="w-full lg:w-2/3">
            <h1 id="what-we-ask-for">
                {{ __('What information do we ask for?') }}
            </h1>
            <p class="h4">{{ __('Information that we ask Consultation Participants, Accessibility Consultants, and Community Connectors to share.') }}</p>
        </div>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <div class="with-sidebar">
            @include('about.partials.what-we-ask-for-navigation')
            <div class="stack">
                <h2>{{ __('Accessibility Consultants — Individual') }}</h2>
                <p>{{ __('We ask Accessibility Consultants for the following information:') }}</p>
                <x-expander :summary="__('Basic information about you')" level="3">
                    <ul>
                        <li>{{ __('Province or territory') }}</li>
                        <li>{{ __('City or town (optional)') }}</li>
                        <li>{{ __('Pronouns (optional)') }}</li>
                        <li>{{ __('Your bio') }}</li>
                        <li>{{ __('Social media and website links (optional)') }}</li>
                    </ul>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('This information is a part of your public profile that you can publish and share with other members of the website, including governments and businesses. This provides an overview to others about who you are.') }}</p>
                </x-expander>
                <x-expander :summary="__('Experiences (optional)')" level="3">
                    <ul>
                        <li>{{ __('Lived experience (optional)') }}</li>
                        <li>{{ __('Skills and strengths (optional)') }}</li>
                        <li>{{ __('Relevant experiences (including any volunteer or paid experience) (optional)') }}</li>
                    </ul>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('You can share your lived experience if you feel it is relevant to your work.') }}</p>
                    <p>{{ __('Not everyone has had access to paid or volunteer experiences, but there are a lot of experiences that build certain skills and strengths. You can share more about that here. If you have had paid or volunteer experiences, you can also include that.') }}</p>
                </x-expander>
                <x-expander :summary="__('Communication and meeting preferences')" level="3">
                    <p>{{ __('We will ask you about whether you have a preference for either in-person or virtual meetings. We will also ask you how you would like us to contact you, and for your contact information.') }}</p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('This will help governments and businesses communicate and work with you in a format that is accessible to you.') }}</p>

                </x-expander>
                <x-expander :summary="__('Language preferences')" level="3">
                    <p>{{ __('We will ask you to indicate:') }}
                        <ul>
                            <li>{{ __('your preferred language for navigation of this website') }}</li>
                            <li>{{ __('your first language') }}</li>
                            <li>{{ __('the other languages you are able to work in') }}</li>
                        </ul>
                    </p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('The language you select for navigating this website sets the website into that language.') }}</p>
                    <p>{{ __('Your first language and other languages will be combined into a “working languages” list - this will let governments and businesses understand what languages you can use to work with them in, and communicate to others in.') }}</p>
                </x-expander>
                <x-expander :summary="__('Areas of interest (optional)')" level="3">
                    <p>{{ __('The Accessible Canada Act has outlined seven main areas that governments and businesses have to make accessible. If you would like, you may indicate which areas are of interest to you. You do not have to answer this question as it is optional. ') }}</p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('Providing this information will help regulated organizations, like governments and businesses, understand which areas are of interest to you. ') }}</p>
                </x-expander>
                <x-expander :summary="__('Sectors of interest (optional)')" level="3">
                    <p>{{ __('The Accessible Canada Act has identified sectors that are required to comply with the Act. If you would like, you may indicate which sectors are of interest to you. You do not have to answer this question as it is optional. ') }}</p>
                    <h4>{{ __('Why do we ask for this information?') }}</h4>
                    <p>{{ __('Providing this information will help regulated organizations, like governments and businesses, understand which sectors are of interest to you.') }}</p>
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
