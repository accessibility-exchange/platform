{{-- MARK: Sign up --}}
@if (Auth::user()->checkStatus('pending'))
    <a class="current-task__action with-icon" href="{{ orientation_link(Auth::user()->context) }}" rel="noopener"
        target="_blank">
        {{ __('Sign up and attend an orientation session') }}
        @svg('heroicon-o-arrow-top-right-on-square', 'ml-1 icon--lg')
    </a>
    <p>{{ __('Before you do anything else, you must attend an orientation session to learn about this website and your options.') }}
    </p>
    {{ safe_markdown('Please check your email for a confirmation of your session date. Please email <:email> if you did not get the email, or if you need to reschedule or cancel. If you’ve gone to your orientation session, it may take 2-3 business days to be updated here on the website.', ['email' => settings('email')]) }}
@else
    @push('completed-steps')
        <li>
            <p class="h4">{{ __('Sign up and attend an orientation session') }}</p>
            <p>{{ __('Before you do anything else, you must attend an orientation session to learn about this website and your options.') }}
            </p>
        </li>
    @endPush
@endif

{{-- MARK: Pick role --}}
@if (empty(Auth::user()->individual->roles))
    @if (Auth::user()->checkStatus('approved'))
        <a class="current-task__action with-icon" href="{{ localized_route('individuals.show-role-selection') }}">
            {{ __('Pick your role') }}
            @svg('heroicon-o-chevron-right', 'ml-1 icon--lg')
        </a>
        <p>{{ __('Please pick whether you’d like to be a Participant, a Consultant, or a Connector.') }}</p>
        @push('next-steps')
            <li>
                <p>{{ __('This will show up once you pick your role.') }}</p>
            </li>
        @endpush
    @else
        @push('next-steps')
            <li>
                <p class="h4">{{ __('Pick your role') }}</p>
                <p>{{ __('Please pick whether you’d like to be a Participant, a Consultant, or a Connector.') }}</p>
            </li>
        @endpush
    @endif
@else
    @push('completed-steps')
        <li>
            <p class="h4">{{ __('Pick your role') }}</p>
            <p>{{ __('Please pick whether you’d like to be a Participant, a Consultant, or a Connector.') }}</p>
        </li>
    @endPush
@endif

{{-- MARK: Collaboration Prefs --}}
@if (Auth::user()->individual->isParticipant())
    @if (Auth::user()->individual->paymentTypes->count() || !blank(Auth::user()->individual->other_payment_type))
        @push('completed-steps')
            <li>
                <p class="h4">{{ __('Fill in your collaboration preferences') }}</p>
                <p>{{ __('This will help people know what to expect when working with you.') }}</p>
            </li>
        @endpush
    @elseif (empty(Auth::user()->individual->roles))
        @push('next-steps')
            <li>
                <p class="h4">{{ __('Fill in your collaboration preferences') }}</p>
                <p>{{ __('This will help people know what to expect when working with you.') }}</p>
            </li>
        @endpush
    @else
        <a class="current-task__action with-icon" href="{{ localized_route('dashboard.collaboration-preferences') }}">
            {{ __('Fill in your collaboration preferences') }}
            @svg('heroicon-o-chevron-right', 'ml-1 icon--lg')
        </a>
        <p>{{ __('This will help people know what to expect when working with you.') }}</p>

        @if (!Auth::user()->individual->isConnector() && !Auth::user()->individual->isConsultant())
            @push('next-steps')
                <li>
                    <p>{{ __('There are no next steps. After this you’ll be able to sign up for engagements!') }}</p>
                </li>
            @endpush
        @endif
    @endif
@endif

{{-- MARK: Application --}}
{{--
    TODO: Split Application and Public page into separate steps.
    Until we have approvals for applications we won't know when the application has been completed. To workaround this
    the Application step has been combined with the Public Page step.
--}}
@if (Auth::user()->individual->isConnector() || Auth::user()->individual->isConsultant())
    @if (Auth::user()->individual->checkStatus('published'))
        @push('completed-steps')
            <li>
                <p class="h4">{{ __('Fill out and return your application') }}</p>
                <p>{{ __('You must return this and have it approved.') }}</p>
            </li>
            <li>
                <p class="h4">{{ __('Create a public page') }}</p>
                <p>{{ __('Please create your page to share more about who you are, your experiences, and your interests.') }}
                </p>
            </li>
        @endpush
    @elseif (
        (Auth::user()->individual->isParticipant() &&
            (Auth::user()->individual->paymentTypes->count() || !blank(Auth::user()->individual->other_payment_type))) ||
            (!Auth::user()->individual->isParticipant() && !empty(Auth::user()->individual->roles)))
        <div>
            <a class="current-task__action with-icon" href="{{ settings_localized('cc_application', locale()) }}"
                rel="noopener" target="_blank">
                {{ __('Fill out and return your application') }}
                @svg('heroicon-o-arrow-top-right-on-square', 'ml-1 icon--lg')
            </a>
            <p>{{ __('You must return this and have it approved.') }}</p>
        </div>
        <div>
            <a class="current-task__action with-icon"
                href="{{ localized_route('individuals.edit', Auth::user()->individual) }}">
                {{ __('Create a public page') }}
                @svg('heroicon-o-chevron-right', 'ml-1 icon--lg')
            </a>
            <p>{{ __('Please create your page to share more about who you are, your experiences, and your interests.') }}
            </p>
        </div>
        @push('next-steps')
            <li>
                <p>{{ __('There are no next steps. After this you’ll be able to sign up for engagements!') }}</p>
            </li>
        @endpush
    @else
        @push('next-steps')
            <li>
                <p class="h4">{{ __('Fill out and return your application') }}</p>
                <p>{{ __('You must return this and have it approved.') }}</p>
            </li>
            <li>
                <p class="h4">{{ __('Create a public page') }}</p>
                <p>{{ __('Please create your page to share more about who you are, your experiences, and your interests.') }}
                </p>
            </li>
        @endpush
    @endif
@endif
