<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('How matching works') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('My dashboard') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
            <li><a href="{{ localized_route('settings.show-matching') }}">{{ __('Matching') }}</a></li>
        </ol>
        <h1>
            {{ __('How matching works') }}
        </h1>
    </x-slot>

    <h2>{{ __('The matching process') }}</h2>

    <x-placeholder />

    <div>
        <x-section
            class="accent--color border--turquoise border-x-0 border-b-0 border-t-[1rem] border-solid xl:px-12 py-8">
            <h2>{{ __('Get started') }}</h2>

            <div class="switcher">
                <div class="stack">
                    <h3>{{ __('Are you ready to get started?') }}</h3>
                    <a class="cta" href="{{ localized_route('settings.edit-matching') }}">
                        {{ __('Add your information') }}
                    </a>
                </div>

                <div class="stack">
                    <h3>{{ __('Do you have more questions?') }}</h3>

                    <div>
                        <h4>{{ __('Email us') }}</h4>
                        <p><a
                                href="mailto:{{ settings()->get('email', 'support@accessibilityexchange.ca') }}">{{ settings()->get('email', 'support@accessibilityexchange.ca') }}</a>
                        </p>
                    </div>

                    <div class="stack">
                        <h4>{{ __('Call or text us') }}</h4>
                        <em>{{ __('Includes VRS') }}</em>

                        <p>{{ phone(settings()->get('phone', '+1-888-867-0053'), 'CA')->formatForCountry('CA') }}</p>
                    </div>

                    <div>
                        <a
                            href="{{ localized_route('about.privacy-policy') }}">{{ __('Access our full privacy policy') }}</a>
                    </div>
                </div>
            </div>
        </x-section>
    </div>

    <div>
        <a class="cta secondary" href="{{ localized_route('settings.show-matching') }}">
            {{ __('Back to matching') }}
        </a>
    </div>
</x-app-layout>
