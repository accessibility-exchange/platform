<x-app-wide-layout>
    <x-slot name="title">{{ __('Get input for your projects') }}</x-slot>
    <x-slot name="header">
        <div class="-mt-12 full bg-turquoise-2 py-12">
            <div class="center center:wide stack">
                <ol class="breadcrumbs" role="list">
                    <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
                    @if(request()->localizedRouteIs('about.regulated-organization-get-input'))
                        <li><a href="{{ localized_route('about.for-individuals') }}">{{ __('How this works for Regulated Organizations') }}</a></li>
                    @elseif(request()->localizedRouteIs('about.organization-get-input'))
                        <li><a href="{{ localized_route('about.for-community-organizations') }}">{{ __('How this works for Community Organizations') }}</a></li>
                    @endif
                </ol>
                <h1 class="w-1/2">
                    {{ __('Get input for your projects') }}
                </h1>

                <p>{{ __('As a :organizationType, you can engage with individuals to get input for your projects.', [
                    'organizationType' => request()->localizedRouteIs('about.regulated-organization-get-input') ? __('Regulated Organization') : __('Community Organization')
                ]) }}</p>
            </div>
        </div>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <x-section aria-labelledby="projects" class="stack:lg">
            <div class="center">
                <h2 id="projects" class="text-center">{!! __('What types of projects could you get input on?') !!}</h2>
            </div>
            <p>{{ __('TODO.') }}</p>
        </x-section>

        <x-section aria-labelledby="how" class="stack:lg">
            <div class="center text-center">
                <h2 id="how">{!! __('How does :gettingInput work?', ['gettingInput' => '<strong>' . __('getting input for your projects') . '</strong>']) !!}</h2>
            </div>
            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-turquoise-2" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Sign up and share more about your organization') }}</h3>
                    <p>{{ __('This information will help other members of the website like governments, businesses, and individuals with disabilities or who are Deaf learn about your organization.') }}</p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-turquoise-2" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Share more about your projects and who you’re looking to engage') }}</h3>
                    <p>{{ __('You can create a criterion of who you’re looking to engage in your project. You can opt for an open project, where anyone who matches your criteria can sign up.') }}@if(request()->localizedRouteIs('about.regulated-organization-get-input')){{ __('You can also opt to use our matching system to automatically match you to a group of people who meet your criteria.') }}@endif</p>
                </div>
            </x-media-text>

            <x-media-text>
                <x-slot name="media">
                    <x-placeholder width="915" height="515" class="text-turquoise-2" />
                </x-slot>
                <div class="stack flex flex-col justify-center h-full">
                    <h3>{{ __('Work directly with people on your accessibility project') }}</h3>
                    <p>{{ __('Once individuals agree to work on your project, you can reach out to them directly to coordinate how and when to work together.') }}</a></p>
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
                        @if(request()->localizedRouteIs('about.regulated-organization-get-input'))
                            <div class="stack">
                                <h3 class="h4">{{ __('Request an introductory phone call') }}</h3>
                                <p><a class="cta" href="#TODO"> {{ __('Request a call') }}</a></p>
                            </div>
                            <div class="stack">
                                <h3 class="h4">{{ __('Learn about our pricing') }}</h3>
                                <p><a class="cta" href="#TODO"> {{ __('Go to pricing') }}</a></p>
                            </div>
                        @elseif(request()->localizedRouteIs('about.organization-get-input'))
                        <div class="stack">
                            <h3>{{ __('Sign up on the phone') }}</h3>
                            <p>{{ __('Call our support line at :number', ['number' => settings()->get('phone', '1-800-123-4567')]) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </x-section>
        @endguest
    </div>

</x-app-wide-layout>
