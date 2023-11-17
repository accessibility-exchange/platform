@can('update', $memberable)
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('organizations.edit', Auth::user()->organization) }}">{{ __('Create your organization’s page') }}</a>
        </h3>
        <x-interpretation name="{{ __('Create your organization’s page', [], 'en') }}"
            namespace="getting_started-community_org" />
        <p>
            {{ __('Please create your organization’s page so that other members of this website can find you.') }}
        </p>
        @if (Auth::user()->organization->isPublishable())
            <span class="badge">{{ __('Completed') }}</span>
        @elseif (Auth::user()->organization->isInProgress())
            <span class="badge">{{ __('In progress') }}</span>
        @else
            <span class="badge">{{ __('Not started yet') }}</span>
        @endif
    </li>
@endcan

<li class="getting-started__list-item stack">
    <h3>
        <a href="{{ orientation_link(Auth::user()->context) }}" @can('update', $memberable)class="counter__item"@endcan
            rel="noopener" target="_blank">
            {{ __('Sign up and attend an orientation session') }}
            @svg('heroicon-o-arrow-top-right-on-square', 'ml-1')
        </a>
    </h3>
    <x-interpretation name="{{ __('Sign up and attend an orientation session', [], 'en') }}"
        namespace="getting_started-community_org" />
    <p>
        {{ __('Click the link above to sign up for an orientation session. (This will lead you to an external site, and when you’re done it will bring you back automatically.)') }}
    </p>
    @if (Auth::user()->organization->checkStatus('approved'))
        <span class="badge">{{ __('Attended') }}</span>
    @elseif (Auth::user()->organization->checkStatus('pending'))
        <span class="badge">{{ __('Not attended yet') }}</span>
        <x-expander type="disclosure" :level="4">
            <x-slot name="summary">{{ __('I’ve gone to orientation, why isn’t this updated?') }}</x-slot>
            <x-interpretation name="{{ __('I’ve gone to orientation, why isn’t this updated?', [], 'en') }}"
                namespace="getting_started-community_org" />
            {{ safe_markdown(
                'We may have not updated this status in our system yet. Please wait a few days before seeing this status update. If you have further questions, please [contact us](:url).',
                ['url' => '#footer-contact'],
            ) }}
        </x-expander>
    @endif
</li>

@can('update', $memberable)
    @if (Auth::user()->organization->isConnector() || Auth::user()->organization->isConsultant())
        <li class="getting-started__list-item stack">
            <h3 class="counter__item">{{ __('Fill out and return your application') }}</h3>
            <x-interpretation name="{{ __('Fill out and return your application', [], 'en') }}"
                namespace="getting_started-community_org" />
            <p>
                @php
                    $roles = [];
                    if (Auth::user()->organization->isConsultant()) {
                        $roles[] = __('Accessibility Consultant');
                    }
                    if (Auth::user()->organization->isConnector()) {
                        $roles[] = __('Community Connector');
                    }
                @endphp

                {{ trans_choice('Please fill out and return your application for your :role role. You must return this and have it approved before you can attend orientation. You can find the application in the link below, or in the email we sent you.|Please fill out and return your application for your :role and :otherRole roles. You must return this and have it approved before you can attend orientation. You can find the applications in the links below, or in the email we sent you.', count($roles), ['role' => $roles[0], 'otherRole' => $roles[1] ?? '']) }}
            </p>
            <ul role="list">
                @if (Auth::user()->organization->isConsultant())
                    <li>
                        <a href="{{ settings('ac_application')[locale()] ?? settings('ac_application')['en'] }}"
                            rel="noopener" target="_blank">
                            {{ __('Application for Accessibility Consultant') }}
                            @svg('heroicon-o-arrow-top-right-on-square', 'ml-1')
                        </a>
                    </li>
                @endif
                @if (Auth::user()->organization->isConnector())
                    <li>
                        <a href="{{ settings('cc_application')[locale()] ?? settings('cc_application')['en'] }}"
                            rel="noopener" target="_blank">
                            {{ __('Application for Community Connector') }}
                            @svg('heroicon-o-arrow-top-right-on-square', 'ml-1')
                        </a>
                    </li>
                @endif
            </ul>
        </li>
    @endif
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('organizations.edit', Auth::user()->organization) }}">{{ __('Review and publish your organization’s public page') }}</a>
        </h3>
        <x-interpretation name="{{ __('Review and publish your organization’s public page', [], 'en') }}"
            namespace="getting_started-community_org" />
        <p>
            {{ __('Once your account has been approved, you can review and publish your organization’s page. You must have completed all the previous steps.') }}
        </p>
        @if (Auth::user()->organization->checkStatus('pending'))
            <span class="badge">{{ __('Not yet approved') }}</span>
        @elseif (Auth::user()->organization->checkStatus('published'))
            <span class="badge">{{ __('Published') }}</span>
        @else
            <span class="badge">{{ __('Ready to publish') }}</span>
        @endif
    </li>
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('projects.show-language-selection', Auth::user()->organization) }}">{{ __('Create your first project') }}</a>
        </h3>
        <x-interpretation name="{{ __('Create your first project', [], 'en') }}"
            namespace="getting_started-community_org" />
        <p>
            {{ __('Plan and share your project with others on this website.') }}
        </p>
        @if (Auth::user()->organization->publishedProjects()->count())
            <span class="badge">{{ __('Completed') }}</span>
        @elseif (Auth::user()->organization->draftProjects()->count())
            <span class="badge">{{ __('In progress') }}</span>
        @else
            <span class="badge">{{ __('Not yet started') }}</span>
        @endif
    </li>
@endcan
