<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('How this works for Federally Regulated Organizations') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
        </ol>
        <h1>
            <span class="font-medium">{{ __('How this works for') }}</span><br />
            {{ __('federally regulated organizations') }}
        </h1>
        <x-interpretation name="{{ __('How this works for federally regulated organizations', [], 'en') }}" />
    </x-slot>

    <div class="-mb-8 space-y-16 px-0">
        <div class="stack w-full" x-data="vimeoPlayer({
            url: @if (locale() === 'en') 'https://vimeo.com/789854286/55a92ff1ce'
                @elseif (locale() === 'fr')
                'https://vimeo.com/789770856/260bd461d8'
                @elseif (locale() === 'asl')
                'https://vimeo.com/788820695/f357173576'
                @elseif (locale() === 'lsq')
                'https://vimeo.com/789826246/fc705e9bae' @endif,
            byline: false,
            dnt: true,
            pip: true,
            portrait: false,
            responsive: true,
            speed: true,
            title: false
        })" @ended="player().setCurrentTime(0)">
        </div>

        <x-section class="stack:lg" aria-labelledby="what">
            <div class="text-center">
                <h2 id="what">{{ __('What you can do on this website') }}</h2>
                <x-interpretation name="{{ __('What you can do on this website', [], 'en') }}" />
            </div>

            <div class="grid">
                <div class="stack border--turquoise border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Get input on your accessibility projects') }}</h3>
                    <x-interpretation name="{{ __('Get input on your accessibility projects', [], 'en') }}" />
                    <p>{{ __('You can work on any projects related to accessibility on this website. This can be your organization’s Accessibility Plan or Report (as required under the Accessible Canada Act), or getting feedback on your customer service or accessibility in your workplace.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.regulated-organization-get-input') }}">{{ __('Learn more about getting input for your projects') }}</a>
                    </p>
                </div>

                <div class="stack border--lavender border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Find Consultation Participants') }}</h3>
                    <x-interpretation name="{{ __('Find Consultation Participants', [], 'en') }}" />
                    <p>{{ __('Find people with disabilities, Deaf people and community organizations (for example, disability or other relevant civil society organizations, like Indigenous groups), to consult with on your accessibility project.') }}
                    </p>
                </div>

                <div class="stack border-x-0 border-b-0 border-t-[6px] border-solid border-t-green-5 pt-8">
                    <h3>{{ __('Access resources and trainings') }}</h3>
                    <x-interpretation name="{{ __('Access resources and trainings', [], 'en') }}" />
                    <p>{{ __('We have a hub of resources and trainings. The materials can help you and your team deepen your understanding of disability and inclusion.') }}
                    </p>
                </div>

                <div class="stack border--magenta border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Tap into our support network') }}</h3>
                    <x-interpretation name="{{ __('Tap into our support network', [], 'en') }}" />
                    <p>{{ __('Accessibility Consultants could help you design consultations that are inclusive and accessible.') }}
                    </p>
                    <p>{{ __('Community Connectors could help you connect with groups that may be hard to reach otherwise.') }}
                    </p>
                    <p>{{ __('Community organizations could provide research, recommendations, and also support the interpretation of your consultation results to deepen your understanding of Deaf and disability access.') }}
                    </p>
                </div>
            </div>
        </x-section>

        {{-- <x-section aria-labelledby="faq">
            <h2 class="text-center" id="faq">{{ __('Frequently asked questions') }}</h2>

            <p>TODO.</p>
        </x-section> --}}

        <x-section class="accent--color text-center">
            @include('partials.have-more-questions')
        </x-section>

        @include('partials.join', ['withPricing' => true])
    </div>

</x-app-layout>
