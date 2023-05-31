@if (Auth::user()->individual->isConnector() || Auth::user()->individual->isConsultant())
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('individuals.edit', Auth::user()->individual) }}">{{ __('Create a public page') }}</a>
        </h3>
        <p>
            {{ __('Please create your page to share more about who you are, your experiences, and your interests.') }}
        </p>
        @if (Auth::user()->individual->isPublishable())
            <span class="badge">{{ __('Completed') }}</span>
        @elseif (Auth::user()->individual->isInProgress())
            <span class="badge">{{ __('In progress') }}</span>
        @else
            <span class="badge">{{ __('Not started yet') }}</span>
        @endif
    </li>
@endif
<li class="getting-started__list-item stack">
    <h3>
        <a class="counter__item"
            href="{{ localized_route('dashboard.collaboration-preferences') }}">{{ __('Enter your collaboration preferences') }}</a>
    </h3>
    <p>
        {{ __('This will help people know what to expect when working with you.') }}
    </p>
</li>
<li class="getting-started__list-item stack">
    <h3>
        <a class="counter__item" href="{{ orientation_link(Auth::user()->context) }}">
            {{ __('Sign up and attend an orientation session') }}
            @svg('heroicon-o-external-link', 'ml-1')
        </a>
    </h3>
    <p>
        {{ __('Click the link above to sign up for an orientation session. (This will lead you to an external site, and when you’re done it will bring you back automatically.)') }}
    </p>
    @if (Auth::user()->checkStatus('approved'))
        <span class="badge">{{ __('Attended') }}</span>
    @elseif (Auth::user()->checkStatus('pending'))
        <span class="badge">{{ __('Not attended yet') }}</span>
        <x-expander type="disclosure" :level="4">
            <x-slot name="summary">{{ __('I’ve gone to orientation, why isn’t this updated?') }}</x-slot>
            {{ safe_markdown(
                'We may have not updated this status in our system yet. Please wait a few days before seeing this status update. If you have further questions, please [contact us](:url).',
                ['url' => '#footer-contact'],
            ) }}
        </x-expander>
    @endif
</li>
@if (Auth::user()->individual->isConnector() || Auth::user()->individual->isConsultant())
    <li class="getting-started__list-item stack">
        <h3 class="counter__item">{{ __('Fill out and return your application') }}</h3>
        <p>
            @php
                $roles = [];
                if (Auth::user()->individual->isConsultant()) {
                    $roles[] = __('Accessibility Consultant');
                }
                if (Auth::user()->individual->isConnector()) {
                    $roles[] = __('Community Connector');
                }
            @endphp
            {{ trans_choice('Please fill out and return your application for your :role role. You must return this and have it approved before you can attend orientation. You can find the application in the link below, or in the email we sent you.|Please fill out and return your application for your :role and :otherRole roles. You must return this and have it approved before you can attend orientation. You can find the applications in the links below, or in the email we sent you.', count($roles), ['role' => $roles[0], 'otherRole' => $roles[1] ?? '']) }}
        </p>
        <ul role="list">
            @if (Auth::user()->individual->isConsultant())
                <li>
                    <a href="{{ settings('ac_application') }}">
                        {{ __('Application for Accessibility Consultant') }}
                        @svg('heroicon-o-external-link', 'ml-1')
                    </a>
                </li>
            @endif
            @if (Auth::user()->individual->isConnector())
                <li>
                    <a href="{{ settings('cc_application') }}">
                        {{ __('Application for Community Connector') }}
                        @svg('heroicon-o-external-link', 'ml-1')
                    </a>
                </li>
            @endif
        </ul>
    </li>
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('individuals.edit', Auth::user()->individual) }}">{{ __('Review and publish your public page') }}</a>
        </h3>
        <p>
            {{ __('Once your account has been approved, you can review and publish your page. You must have completed all the previous steps.') }}
        </p>
        @if (Auth::user()->checkStatus('pending'))
            <span class="badge">{{ __('Not yet approved') }}</span>
        @elseif (Auth::user()->individual->checkStatus('published'))
            <span class="badge">{{ __('Published') }}</span>
        @else
            <span class="badge">{{ __('Ready to publish') }}</span>
        @endif
    </li>
@endif
