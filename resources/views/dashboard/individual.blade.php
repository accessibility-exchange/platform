<div class="center center:wide columns">
    <div class="column stack">
        <div class="stack">
            <h2>{{ __('Getting started') }}</h2>
            @if (($user->individual->isConsultant() || $user->individual->isConnector()) &&
                !$user->individual->hasAddedDetails())
                <x-expander level="3">
                    <x-slot name="summary">{{ __('Create your individual page') }}</x-slot>
                    <div class="stack">
                        <p>{{ __('Once you create your page, organizations can find you and ask you to consult on their projects.') }}
                        </p>
                        <p>
                            <a class="cta secondary"
                                href="{{ localized_route('individuals.edit', $user->individual) }}">{{ __('Create your page') }}</a>
                        </p>
                    </div>
                </x-expander>
            @else
                <x-expander level="3">
                    <x-slot name="summary">{{ __('Find organizations to follow') }}</x-slot>
                    <div class="stack">
                        <p>{{ __('Once you follow some organizations that you’re interested in, you will be notified whenever they begin a consultation process.') }}
                        </p>
                        <p><a class="button"
                                href="{{ localized_route('regulated-organizations.index') }}">{{ __('Find entities') }}</a>
                        </p>
                    </div>
                </x-expander>
            @endif
            <x-expander level="3">
                <x-slot name="summary">{{ __('Learn about participating in consultations') }}</x-slot>
                <div class="stack">
                    <p>{{ __('Find resources about the accessibility planning process and how you can participate in it.') }}
                    </p>
                    <p><a class="button"
                            href="{{ localized_route('resource-collections.index') }}">{{ __('Explore the resource hub') }}</a>
                    </p>
                </div>
            </x-expander>
        </div>

        @if (($user->individual->isConsultant() || $user->individual->isConnector()) &&
            $user->individual->hasAddedDetails())
            <div class="stack">
                <h2>{{ __('My page') }}</h2>
                <p>
                    <a href="{{ localized_route('individuals.show', ['individual' => $user->individual]) }}"><strong>{{ __('Visit my page') }}</strong>
                        @if ($user->individual->checkStatus('draft'))
                            ({{ __('draft') }})
                        @endif
                    </a><br />
                    <a
                        href="{{ localized_route('individuals.edit', ['individual' => $user->individual]) }}">{{ __('Edit my page') }}</a>
                </p>
            </div>

            <div class="stack">
                <h2>{{ __('Notifications') }} <span class="badge">0</span></h2>
            </div>

            <div class="stack">
                <h2>{{ __('Upcoming meetings') }} <span class="badge">0</span></h2>
            </div>
        @endif
    </div>

    <div class="column stack">
        @if ($user->individual->hasAddedDetails())
            <div class="stack">
                <h2>{{ __('My active engagements') }}</h2>
                @if (count($user->individual->engagements) > 0)
                    @foreach ($user->individual->engagements as $engagement)
                        <x-engagement-card :model="$engagement" />
                    @endforeach
                @else
                    <p>{{ __('You have no active engagements right now.') }}</p>
                @endif
            </div>
        @else
            <div class="stack">
                <h2>{{ __('Notifications') }} <span class="badge">0</span></h2>
            </div>

            <div class="stack">
                <h2>{{ __('Upcoming meetings') }} <span class="badge">0</span></h2>
            </div>
        @endif
    </div>
</div>
