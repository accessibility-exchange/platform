<div class="columns">
    <div class="column stack">
        <div class="stack">
            <h2>{{ __('Getting started') }}</h2>
            @if($memberable && !$memberable->hasAddedDetails())
            <x-expander level="3">
                <x-slot name="summary">{{ __('Create your organization page') }}</x-slot>
                <div class="stack">
                    <p>{{ __('Share more about your organization so that individuals can get to know you.') }}</p>
                    <p><a class="button" href="{{ localized_route('organizations.show-language-selection', $memberable) }}">{{ __('Create your page') }}</a></p>
                </div>
            </x-expander>
            @else
            <x-expander level="3">
                <x-slot name="summary">{{ __('Create a project page') }}</x-slot>
                <div class="stack">
                    <p>{{ __('Create a new project page so that individuals can begin to express their interest in working with you.') }}</p>
                    <p><a class="button" href="{{ $user->projectable()->projects->count() > 0 ? localized_route('projects.show-context-selection') : localized_route('projects.show-language-selection') }}">{{ __('Create project page') }}</a></p>
                </div>
            </x-expander>
            @endif
        </div>

        @if($memberable?->hasAddedDetails())
        <div class="stack">
            <h2>{{ __('My organization page') }}</h2>
            <p>
                <a href="{{ localized_route('organizations.show', $memberable) }}"><strong>{{ __('Visit my organization page') }}</strong><br />
                <a href="{{ localized_route('organizations.edit', $memberable) }}">{{ __('Edit my organization page') }}</a>
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
        @if($memberable?->hasAddedDetails())
        <div class="stack">
            <h2>{{ __('My active projects') }}</h2>
            @if(count($memberable->projects) > 0)
                @foreach($memberable->projects as $project)
                <x-project-card :project="$project" />
                @endforeach
            <p><a href="{{ localized_route('projects.my-projects') }}">{{ __('Show all my projects') }}</a></p>
            @else
            <p>{!! __('You have no active projects right now. :action', ['action' => '<strong><a href="' . localized_route('projects.create') . '">' . __('Create your first project.') . '</a></strong>']) !!}</p>
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
