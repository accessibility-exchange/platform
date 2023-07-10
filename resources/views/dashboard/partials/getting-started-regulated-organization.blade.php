@can('update', $memberable)
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('regulated-organizations.edit', Auth::user()->regulatedOrganization) }}">{{ __('Create your organization’s page') }}</a>
        </h3>
        <p>
            {{ __('Please create your organization’s page so that other members of this website can find you.') }}
        </p>
        @if (Auth::user()->regulatedOrganization->isPublishable())
            <span class="badge">{{ __('Completed') }}</span>
        @elseif (Auth::user()->regulatedOrganization->isPreviewable())
            <span class="badge">{{ __('In progress') }}</span>
        @else
            <span class="badge">{{ __('Not started yet') }}</span>
        @endif
    </li>
@endcan

<li class="getting-started__list-item stack">
    <h3>
        <a href="{{ orientation_link(Auth::user()->context) }}" @can('update', $memberable)class="counter__item"@endcan>
            {{ __('Sign up and attend an orientation session') }}
            @svg('heroicon-o-external-link', 'ml-1')
        </a>
    </h3>
    <p>
        {{ __('Click the link above to sign up for an orientation session. (This will lead you to an external site, and when you’re done it will bring you back automatically.)') }}
    </p>
    @if (Auth::user()->regulatedOrganization->checkStatus('approved'))
        <span class="badge">{{ __('Attended') }}</span>
    @elseif (Auth::user()->regulatedOrganization->checkStatus('pending'))
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

@can('update', $memberable)
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('regulated-organizations.edit', Auth::user()->regulatedOrganization) }}">{{ __('Review and publish your organization’s public page') }}</a>
        </h3>
        <p>
            {{ __('Once your account has been approved, you can review and publish your organization’s page. You must have completed all the previous steps.') }}
        </p>
        @if (Auth::user()->regulatedOrganization->checkStatus('pending'))
            <span class="badge">{{ __('Not yet approved') }}</span>
        @elseif (Auth::user()->regulatedOrganization->checkStatus('published'))
            <span class="badge">{{ __('Published') }}</span>
        @else
            <span class="badge">{{ __('Ready to publish') }}</span>
        @endif
    </li>
    <li class="getting-started__list-item stack">
        <h3>
            <a class="counter__item"
                href="{{ localized_route('projects.show-language-selection', Auth::user()->regulatedOrganization) }}">{{ __('Create your first project') }}</a>
        </h3>
        <p>
            {{ __('Plan and share your project with others on this website.') }}
        </p>
        @if (Auth::user()->regulatedOrganization->publishedProjects()->count())
            <span class="badge">{{ __('Completed') }}</span>
        @elseif (Auth::user()->regulatedOrganization->draftProjects()->count())
            <span class="badge">{{ __('In progress') }}</span>
        @else
            <span class="badge">{{ __('Not yet started') }}</span>
        @endif
    </li>
@endcan
