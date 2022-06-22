<x-app-wide-layout>
    <x-slot name="title">{{ __('How this works for Federally Regulated Organizations') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
        </ol>
        <h1>
            <span class="weight:normal">{{ __('How this works for') }}</span><br />
            {{ __('Federally Regulated Organizations') }}
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
                    <x-placeholder width="915" height="515" class="text-turquoise-5" />
                </x-slot>
                <div class="border-solid border-x-0 border-b-0 border-t-[6px] border-t-turquoise-5 pt-8 stack">
                    <h3>{{ __('Get input on your accessibility projects') }}</h3>
                    <p>{{ __('You can work on any projects related to accessibility on this website. This can be your organization’s Accessibility Report (under the Accessible Canada Act), or just getting feedback on your services both in terms of customer service and creating an accessible workplace. ') }}</p>
                    <p><a href="#TODO">{{ __('Learn more about getting input for your projects') }}</a></p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-lavender-3" />
                </x-slot>
                <div class="border-solid border-x-0 border-b-0 border-t-[6px] border-t-lavender-3 pt-8 stack">
                    <h3>{{ __('Find consultation participants') }}</h3>
                    <p>{{ __('Find people with disabilities, Deaf people and community organizations (i.e., disability or other relevant civil society organizations, e.g., Indigenous services), to consult with on your accessibility project.') }}</p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-green-5" />
                </x-slot>
                <div class="border-solid border-x-0 border-b-0 border-t-[6px] border-t-green-5 pt-8 stack">
                    <h3>{{ __('Access resources and trainings') }}</h3>
                    <p>{{ __('We have a hub of resources and trainings for you and your team to deepen your understanding of accessibility and inclusion.') }}</p>
                    <p><a href="{{ localized_route('resource-collections.index') }}">{{ __('Go to our Resource Hub') }}</a></p>
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
                            <h3 class="h4">{{ __('Sign up online') }}</h3>
                            <p><a class="cta" href="{{ localized_route('register') }}"> {{ __('Sign up') }}</a></p>
                        </div>
                        <div class="stack">
                            <h3 class="h4">{{ __('Request an introductory phone call') }}</h3>
                            <p><a class="cta" href="#TODO"> {{ __('Request a call') }}</a></p>
                        </div>
                        <div class="stack">
                            <h3 class="h4">{{ __('Learn about our pricing') }}</h3>
                            <p><a class="cta" href="#TODO"> {{ __('Go to pricing') }}</a></p>
                        </div>
                    </div>
                </div>
            </x-section>
        @endguest
    </div>

</x-app-wide-layout>
