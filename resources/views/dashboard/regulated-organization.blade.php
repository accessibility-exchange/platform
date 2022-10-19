<div class="columns">
    <div class="column stack">
        <div class="stack">
            <h2>{{ __('Getting started') }}</h2>
            @if ($memberable && !$memberable->hasAddedDetails())
                <x-expander level="3">
                    <x-slot name="summary">{{ __('Create your federally regulated organization page') }}</x-slot>
                    <div class="stack">
                        <p>{{ __('Share more about your organization so that individuals can get to know you.') }}</p>
                        <p><a class="button"
                                href="{{ localized_route('regulated-organizations.show-language-selection', $memberable) }}">{{ __('Create your page') }}</a>
                        </p>
                    </div>
                </x-expander>
            @elseif($memberable)
                <x-expander level="3">
                    <x-slot name="summary">{{ __('Create a project page') }}</x-slot>
                    <div class="stack">
                        <p>{{ __('Create a new project page so that individuals can begin to express their interest in working with you.') }}
                        </p>
                        <p><a class="button"
                                href="{{ $memberable->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create project page') }}</a>
                        </p>
                    </div>
                </x-expander>
            @endif
            <x-expander level="3">
                <x-slot name="summary">{{ __('Learn about engaging the disability community') }}</x-slot>
                <div class="stack">
                    <p>{{ __('Browse through our resources and learn more.') }}</p>
                    <p><a class="button"
                            href="{{ localized_route('resource-collections.index') }}">{{ __('Explore the resource hub') }}</a>
                    </p>
                </div>
            </x-expander>
        </div>

        @if ($memberable?->hasAddedDetails())
            <div class="stack">
                <h2>{{ __('My federally regulated organization page') }}</h2>
                <p>
                    <a href="{{ localized_route('regulated-organizations.show', $memberable) }}"><strong>{{ __('Visit my federally regulated organization page') }}</strong><br />
                        <a
                            href="{{ localized_route('regulated-organizations.edit', $memberable) }}">{{ __('Edit my federally regulated organization page') }}</a>
                </p>
            </div>

            @if ($memberable)
                <div class="column stack">
                    @include('dashboard.partials.notifications', [
                        'notifications' => $user->allUnreadNotifications(),
                    ])
                </div>
            @endif

            <div class="stack">
                <h2>{{ __('Upcoming meetings') }} <span class="badge">0</span></h2>
            </div>
        @endif

    </div>

    <div class="column stack">
        @if ($memberable?->hasAddedDetails())
            <div class="stack">
                <h2>{{ __('My active projects') }}</h2>
                @if (count($memberable->projects) > 0)
                    @foreach ($memberable->projects as $project)
                        <x-card.project :project="$project" />
                    @endforeach
                    <p><a href="{{ localized_route('projects.my-projects') }}">{{ __('Show all my projects') }}</a>
                    </p>
                @else
                    <p>{!! __('You have no active projects right now. :action', [
                        'action' =>
                            '<strong><a href="' .
                            localized_route('projects.show-language-selection') .
                            '">' .
                            __('Create your first project.') .
                            '</a></strong>',
                    ]) !!}</p>
                @endif
            </div>
        @else
            @if ($memberable)
                <div class="column stack">
                    @include('dashboard.partials.notifications', [
                        'notifications' => $user->allUnreadNotifications(),
                    ])
                </div>
            @endif

            <div class="stack">
                <h2>{{ __('Upcoming meetings') }} <span class="badge">0</span></h2>
            </div>
        @endif
    </div>
</div>
