<x-app-layout page-width="wide" header-class="full header--participants stack">
    <x-slot name="title">{{ __('Consultation Participants') }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide stack pb-12 pt-4">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
                <li><a
                        href="{{ localized_route('about.for-community-organizations') }}">{{ __('How this works for Community Organizations') }}</a>
                </li>
            </ol>
            <h1 class="w-1/2">
                {{ __('Consultation Participants') }}
            </h1>
            <x-interpretation name="{{ __('Consultation Participants', [], 'en') }}"
                namespace="consultation_participants" />
        </div>
    </x-slot>

    <div class="-mb-8 space-y-16 px-0">
        <x-section class="stack:lg" aria-labelledby="experiences">
            @include('about.partials.who-can-be-a-consultation-participant')
        </x-section>

        <x-section class="stack:lg" aria-labelledby="how">
            <div class="text-center">
                <h2 id="how">
                    {{ safe_inlineMarkdown('How does being a **:role** work?', ['role' => __('Consultation Participant')]) }}
                </h2>
                <x-interpretation class="interpretation--center"
                    name="{{ __('How does being a :role work?', ['role' => __('Consultation Participant', [], 'en')], 'en') }}"
                    namespace="consultation_participants" />
            </div>
            <div class="grid">
                <div class="stack border--lavender border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('1. Sign up for the website and share some information about your organization') }}</h3>
                    <x-interpretation
                        name="{{ __('1. Sign up for the website and share some information about your organization', [], 'en') }}" />
                    <p>{{ __('Once you sign up, you can sign up for an orientation session to learn about what you can do on this website. You can also fill in information about your organization so businesses and government can learn more about what you do and who you represent or serve.') }}
                    </p>
                    @if (request()->localizedRouteIs('about.individual-consultation-participants'))
                        <p><a
                                href="{{ localized_route('about.individual-consultation-participants-what-we-ask-for') }}">{{ __('What information do we ask for?') }}</a>
                        </p>
                    @endif
                    <p><a href="{{ localized_route('about.privacy-policy') }}">{{ __('Read our privacy policy') }}</a>
                    </p>
                </div>

                <div class="stack border--lavender border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('2. Businesses and government can reach out to hire you') }}</h3>
                    <x-interpretation
                        name="{{ __('2. Businesses and government can reach out to hire you', [], 'en') }}" />
                    <p>{{ __('Businesses and government can find Community Organizations on this website, and use the contact information to directly reach out. From there, they can hire you consult with them.') }}
                    </p>
                </div>

                <div class="stack border--lavender border-x-0 border-b-0 border-t-[6px] border-solid pt-8">
                    <h3>{{ __('3. Work directly with businesses and governments') }}</h3>
                    <x-interpretation name="{{ __('3. Work directly with businesses and governments', [], 'en') }}" />
                    <p>{{ __('You can communicate directly with the business or government to figure out how to work on their accessibility project. You will be paid for your work.') }}</a>
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

        @include('partials.join')
    </div>

</x-app-layout>
