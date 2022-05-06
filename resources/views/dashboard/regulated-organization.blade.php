<div class="columns">
    <div class="column stack">
        <div class="stack">
            <h2>{{ __('Getting started') }}</h2>
            @if(!$currentUser->regulatedOrganization()->hasAddedDetails())
            <x-expander level="3">
                <x-slot name="summary">{{ __('Create your federally regulated organization page') }}</x-slot>
                <div class="stack">
                    <p>{{ __('Share more about your organization so that community members can get to know you.') }}</p>
                    <p><a class="button" href="{{ localized_route('regulated-organizations.show-language-selection', $currentUser->regulatedOrganization()) }}">{{ __('Create your page') }}</a></p>
                </div>
            </x-expander>
            @else
            <x-expander level="3">
                <x-slot name="summary">{{ __('Create a project page') }}</x-slot>
                <div class="stack">
                    <p>{{ __('Create a new project page so that community members can begin to express their interest in working with you.') }}</p>
                    <p><a class="button" href="{{ localized_route('projects.create') }}">{{ __('Create project page') }}</a></p>
                </div>
            </x-expander>
            @endif
            <x-expander level="3">
                <x-slot name="summary">{{ __('Learn about engaging the disability community') }}</x-slot>
                <div class="stack">
                    <p>{{ __('Browse through our resources and learn more.') }}</p>
                    <p><a class="button" href="{{ localized_route('resource-collections.index') }}">{{ __('Explore the resource hub') }}</a></p>
                </div>
            </x-expander>
        </div>

        @if($currentUser->regulatedOrganization()->hasAddedDetails())
        <div class="stack">
            <h2>{{ __('My federally regulated organization page') }}</h2>
            <p>
                <a href="{{ localized_route('regulated-organizations.show', $currentUser->regulatedOrganization()) }}"><strong>{{ __('Visit my federally regulated organization page') }}</strong><br />
                <a href="{{ localized_route('regulated-organizations.edit', $currentUser->regulatedOrganization()) }}">{{ __('Edit my federally regulated organization page') }}</a>
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
        @if($currentUser->regulatedOrganization()->hasAddedDetails())
        <div class="stack">
            <h2>{{ __('My active projects') }}</h2>
            @if(count($currentUser->regulatedOrganization()->projects) > 0)
                @foreach($currentUser->regulatedOrganization()->projects as $project)
                <x-project-card :project="$project" />
                @endforeach
            <p><a href="{{ localized_route('users.show_my_projects') }}">{{ __('Show all my projects') }}</a></p>
            @else
            <p>{!! __('You have no active projects right now. :action', ['action' => '<strong><a href="' . localized_route('projects.create', $currentUser->regulatedOrganization()) . '">' . __('Create your first project.') . '</a></strong>']) !!}</p>
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
