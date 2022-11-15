<x-app-medium-layout>
    <x-slot name="title">{{ __('Leave engagement') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Leave engagement') }}
        </h1>
    </x-slot>

    <div class="stack">
        @if ($engagement->signup_by_date > now())
            @if ($engagement->recruitment === 'connector')
                <p>{{ __('To leave this engagement, please contact the Community Connector:') }}</p>
                @if ($engagement->connector)
                    <p>
                        <strong>{{ $engagement->connector->name }}</strong><br />
                        {!! Str::inlineMarkdown($engagement->connector->user->primary_contact_method) !!}
                    </p>
                @elseif($engagement->organizationalConnector)
                    <p>
                        <strong>{{ $engagement->organizationalConnector->name }}</strong><br />
                        {!! Str::inlineMarkdown($engagement->organizationalConnector->primary_contact_method) !!}
                    </p>
                @endif
                <hr class="divider--thick" />

                <p><a class="cta secondary" href="{{ localized_route('engagements.show', $engagement) }}">
                        @svg('heroicon-o-arrow-left') {{ __('No, go back') }}
                    </a></p>
            @else
                <p>{{ __('Are you sure you want to leave this engagement? You’ll still be able to sign up for this engagement again before the sign up deadline.') }}
                </p>

                <hr class="divider--thick" />

                <div class="flex flex-col gap-6 md:flex-row">
                    <form action="{{ localized_route('engagements.leave', $engagement) }}" method="post">
                        @csrf
                        <button>
                            @svg('heroicon-o-logout')
                            {{ __('Yes, leave engagement') }}
                        </button>
                    </form>
                    <a class="cta secondary" href="{{ localized_route('engagements.show', $engagement) }}">
                        @svg('heroicon-o-arrow-left') {{ __('No, go back') }}
                    </a>
                </div>
            @endif
        @else
            <p>{{ __('To leave this engagement, please contact us and we will help you to do so:') }}</p>

            <p>
                <strong>{{ __('Email') }}:</strong>
                {{ settings('email', 'support@accessibilityexchange.ca') }}<br />
                <strong>{{ __('Phone') }}:</strong>
                {{ phone(settings('phone', '+1-888-867-0053'))->formatForCountry('CA') }}<br />
                <strong>{{ __('Hours') }}:</strong> {{ '9:00am to 5:00pm Eastern Time' }}
            </p>
            <hr class="divider--thick" />
            <p>
                <a class="cta secondary" href="{{ localized_route('engagements.show', $engagement) }}">
                    @svg('heroicon-o-arrow-left') {{ __('Back') }}
                </a>
            </p>
        @endif
    </div>
</x-app-medium-layout>
