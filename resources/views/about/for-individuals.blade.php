<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('How this works for Individuals with Disabilities and Deaf Individuals') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
        </ol>
        <h1>
            <span class="font-medium">{{ __('How this works for') }}</span><br />
            {{ __('individuals') }}
        </h1>
        <x-interpretation name="{{ __('How this works for individuals', [], 'en') }}" />
    </x-slot>

    <div class="-mb-8 space-y-16 px-0">
        <div class="stack w-full" x-data="vimeoPlayer({
            url: @if (locale() === 'en') 'https://vimeo.com/789854664/15a18bd3f9'
                @elseif (locale() === 'fr')
                'https://vimeo.com/789823447/0f98810821'
                @elseif (locale() === 'asl')
                'https://vimeo.com/788815524/4485f30067'
                @elseif (locale() === 'lsq')
                'https://vimeo.com/789828003/ed89068fa3' @endif,
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
                <x-interpretation class="interpretation--center"
                    name="{{ __('What you can do on this website', [], 'en') }}" />
                <p>{{ __('You can choose how you would like to take part:') }}</p>
            </div>
            <div class="grid">
                <div class="stack border--lavender border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Be a Consultation Participant') }}</h3>
                    <x-interpretation name="{{ __('Be a Consultation Participant', [], 'en') }}" />
                    <p>{{ __('As an individual with a disability, Deaf person, or a supporter, you can participate in consultations by organizations and businesses who are working on accessibility projects and get paid for this. You can also gain access to resources and training on how to do this.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.individual-consultation-participants') }}">{{ __('Learn more about being a Consultation Participant') }}</a>
                    </p>
                </div>

                <div class="stack border--magenta border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Be an Accessibility Consultant') }}</h3>
                    <x-interpretation name="{{ __('Be an Accessibility Consultant', [], 'en') }}" />
                    <p>{{ __('Help organizations and businesses design their consultations, and potentially help facilitate these consultations.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.individual-accessibility-consultants') }}">{{ __('Learn more about being an Accessibility Consultant') }}</a>
                    </p>
                </div>

                <div class="stack border--yellow border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('Be a Community Connector') }}</h3>
                    <x-interpretation name="{{ __('Be a Community Connector', [], 'en') }}" />
                    <p>{{ __('Connect members of your community with governments and businesses who are looking for Consultation Participants. Help them learn how to best work with your community.') }}
                    </p>
                    <p><a
                            href="{{ localized_route('about.individual-community-connectors') }}">{{ __('Learn more about being a Community Connector') }}</a>
                    </p>
                </div>
            </div>
        </x-section>

        <x-section class="accent--color text-center">
            @include('partials.have-more-questions')
        </x-section>

        @include('partials.join')
    </div>

</x-app-layout>
