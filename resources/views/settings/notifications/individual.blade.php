<div class="with-sidebar">
    <nav class="stack" aria-labelledby="skip-to">
        <h3 id="skip-to">{{ __('Skip to:') }}</h3>
        <ul role="list" class="stack">
            <li>
                <x-nav-link :href="'#'.Str::slug(__('Participating in engagements'))">{{ __('Participating in engagements') }}</x-nav-link>
            </li>
            <li>
                <x-nav-link :href="'#'.Str::slug(__('Finding out about new projects'))">{{ __('Finding out about new projects') }}</x-nav-link>
            </li>
            <li>
                <x-nav-link :href="'#'.Str::slug(__('Keeping my information up to date'))">{{ __('Keeping my information up to date') }}</x-nav-link>
            </li>
        </ul>
    </nav>
    <form class="stack" action="{{ localized_route('settings.update-notification-preferences') }}" method="post" novalidate>
        @csrf
        @method('put')

        <p>
            {{ __('Notifications will be sent through the website or by contacting :contact_person.', ['contact_person' => $individual->user->support_person_name ? __('your support person, :name', ['name' => $individual->user->support_person_name]) : __('you')]) }}
            @if($individual->user->support_person_name)
                {{ __('You’ve provided the following contact information for them:') }}
            @else
                {{ __('You’ve provided the following contact information:') }}
            @endif
        </p>
        <ul>
            <li>{!! Str::inlineMarkdown($individual->user->primary_contact_point) !!}</li>
            @if($individual->user->alternate_contact_point)
                <li>{!! Str::inlineMarkdown($individual->user->alternate_contact_point) !!}</li>
            @endif
        </ul>
        <p>{!! __('If you need to change your contact person or contact information, you can do so on your :communication_and_consultation_preferences_page.', [
                'communication_and_consultation_preferences_page' => '<a href="'.localized_route('settings.edit-communication-and-consultation-preferences').'">'.__('communication and consultation preferences page').'</a>'
            ]) !!}</p>

        <div class="field @error('preferred_notification_method') field--error @enderror">
            <x-hearth-label for="preferred_notification_method">{{ __('Preferred notification method (required)') }}</x-hearth-label>
            @if(!in_array('phone', $individual->user->contact_methods))
                <x-hearth-select name="preferred_notification_method" :options="$emailNotificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
            @elseif(!in_array('email', $individual->user->contact_methods))
                <x-hearth-select name="preferred_notification_method" :options="$phoneNotificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
            @else
                <x-hearth-select name="preferred_notification_method" :options="$notificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
            @endif
        </div>

        <h3 id="{{ Str::slug(__('Participating in engagements')) }}">{{ __('Participating in engagements') }}</h3>

        @if($individual->isParticipant())
            <h4>{{ __('As a consultation participant') }}</h4>

            <p>
                {{ __('We will always notify you when you are invited to engagements by contacting you or your support person.') }}
                {{ __('You will also be notified through the website.') }}
            </p>
        @endif

        @if($individual->isConsultant())
            <fieldset>
                <legend><h4>{{ __('As an accessibility consultant') }}</h4></legend>
                <p>{{ __('How would you like to be notified when you are added as an accessibility consultant to a project?') }}</p>
                <x-hearth-checkboxes name="accessibility_consultant_notifications" :options="$notificationChannels" :checked="old('accessibility_consultant_notifications', $user->notification_settings->get('accessibility-consultants.channels', []))" />
            </fieldset>
        @endif

        @if($individual->isConnector())
            <fieldset>
                <legend><h4>{{ __('As a community connector') }}</h4></legend>
                <p>{{ __('How would you like to be notified when you are added as a community connector to an engagement?') }}</p>
                <x-hearth-checkboxes name="community_connector_notifications" :options="$notificationChannels" :checked="old('community_connector_notifications', $user->notification_settings->get('community-connectors.channels', []))" />
            </fieldset>
        @endif

        <fieldset>
            <legend><h4>{{ __('New reports uploaded') }}</h4></legend>
            <p>{{ __('How would you like to be notified when a project you have worked on uploads a new report?') }}</p>
            <x-hearth-checkboxes name="report_notifications" :options="$notificationChannels" :checked="old('report_notifications', $user->notification_settings->get('reports.channels', []))" />
        </fieldset>

        <h3 id="{{ Str::slug(__('Finding out about new projects')) }}">{{ __('Finding out about new projects') }}</h3>

        <div x-data="{notifyOfProjects: {{ json_encode(old('project_notifications', $user->notification_settings->get('projects.channels', []))) }}}">
            <fieldset>
                <legend><h4>{{ __('Please indicate how you would like to be notified of new projects. ') }}</h4></legend>
                <x-hearth-checkboxes name="project_notifications" :options="$notificationChannels" :checked="old('project_notifications', $user->notification_settings->get('projects.channels', []))" x-model="notifyOfProjects" />
            </fieldset>

            <fieldset x-show="notifyOfProjects.length > 0">
                <legend><h4>{{ __('Please indicate for which projects you would like to receive notifications.') }}</h4></legend>
                <x-hearth-hint for="project_creators">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="project_creators" :options="[
                        [
                            'value' => 'regulated-organizations',
                            'label' => __('Governments, businesses, and other public sector organizations'),
                        ],
                        [
                            'value' => 'organizations',
                            'label' => __('Community organizations'),
                        ],
                    ]" :checked="old('project_creators', $user->notification_settings->get('projects.creators', []))" />
            </fieldset>

            <fieldset x-show="notifyOfProjects.length > 0">
                <legend><h4>{{ __('Please indicate which type of projects you would like to notified about.') }}</h4></legend>
                <x-hearth-hint for="project_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="project_types" :options="[
                        [
                            'value' => 'lived-experience',
                            'label' => __('Projects that are looking for someone with my lived experience'),
                        ],
                        [
                            'value' => 'of-interest',
                            'label' => __('Projects by organizations that I have saved on my notification list'),
                        ],
                    ]" :checked="old('project_types', $user->notification_settings->get('projects.types', []))" />
            </fieldset>

            <fieldset x-show="notifyOfProjects.length > 0">
                <legend><h4>{{ __('Please indicate which type of engagements you would like to be notified about.') }}</h4></legend>
                <x-hearth-hint for="engagements">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="project_engagements" :options="[
                        [
                            'value' => 'lived-experience',
                            'label' => __('Engagements that are looking for someone with my lived experience'),
                        ],
                        [
                            'value' => 'of-interest',
                            'label' => __('Engagements by organizations that I have saved on my notification list'),
                        ],
                    ]" :checked="old('project_engagements', $user->notification_settings->get('projects.engagements', []))" />
            </fieldset>
        </div>

        <h3 id="{{ Str::slug(__('Keeping my information up to date')) }}">{{ __('Keeping my information up to date') }}</h3>

        <fieldset>
            <legend><h4>{{ __('Out of date information') }}</h4></legend>
            @if($individual->isParticipant())
                <p>{{ __('Information such as your matching information, your communication preferences, and your consultation preferences might be out of date if it has not been updated for over a year.') }}</p>
            @else
                <p>{{ __('Information such as your communication and consultation preferences might be out of date if it has not been updated for over a year.') }}</p>
            @endif
            <x-hearth-checkboxes name="update_notifications" :options="$notificationChannels" :checked="old('updates_notifications', $user->notification_settings->get('updates.channels', []))" />
        </fieldset>

        <p>
            <button>{{ __('Save') }}</button>
        </p>
    </form>
</div>
