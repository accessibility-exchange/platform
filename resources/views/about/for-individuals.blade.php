<x-app-wide-layout>
    <x-slot name="title">{{ __('How this works for Individuals with Disabilities and Deaf Individuals') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
        </ol>
        <h1>
            <span class="weight:normal">{{ __('How this works for') }}</span><br />
            {{ __('Individuals with Disabilities and Deaf Individuals') }}
        </h1>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-placeholder class="text-blue-6" />

        <x-section aria-labelledby="what" class="stack:lg">
            <div class="align:center">
                <h2 id="what">{{ __('What you can do on this website') }}</h2>
            </div>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-lavender-3" />
                </x-slot>
                <div class="border-solid border-x-0 border-b-0 border-t-[6px] border-t-lavender-3 pt-8 stack">
                    <h3>{{ __('Be a Consultation Participant') }}</h3>
                    <p>{{ __('Participate in consultations by organizations and businesses who are working on accessibility projects and get paid for this. Access resources and training on how to do this.') }}</p>
                    <p><a href="#TODO">{{ __('Learn more about being a consultation participant') }}</a></p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-magenta-2" />
                </x-slot>
                <div class="border-solid border-x-0 border-b-0 border-t-[6px] border-t-magenta-2 pt-8 stack">
                    <h3>{{ __('Be an Accessibility Consultant') }}</h3>
                    <p>{{ __('Help organizations and businesses design their consultations, and potentially help facilitate these consultations.') }}</p>
                    <p><a href="#TODO">{{ __('Learn more about being an accessibility consultant') }}</a></p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-yellow-3" />
                </x-slot>
                <div class="border-solid border-x-0 border-b-0 border-t-[6px] border-t-yellow-3 pt-8 stack">
                    <h3>{{ __('Be a Community Connector') }}</h3>
                    <p>{{ __('Connect members of your community with governments and businesses who are looking for consultation participants. Help them learn how to best work with your community.') }}</p>
                    <p><a href="#TODO">{{ __('Learn more about being a community connector') }}</a></p>
                </div>
            </x-media-text>
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
