<x-app-wide-layout>
    <x-slot name="title">{{ __('Notifications') }}</x-slot>
    <x-slot name="header">
        <div class="full bg-white -mt-12 py-12 border-b-grey-3 border-solid border-b border-x-0 border-t-0">
            <div class="center center:wide">
                <ol class="breadcrumbs" role="list">
                    <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
                </ol>
                <h1 id="notifications">
                    {{ __('Notifications') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <nav aria-labelledby="notifications" class="full bg-white">
        <div class="center center:wide">
            <ul role="list" class="flex gap-6 -mt-4">
                <li class="w-1/2">
                    <x-nav-link class="inline-flex items-center justify-center w-full" :href="localized_route('settings.edit-notification-preferences')" :active="request()->localizedRouteIs('settings.edit-notification-preferences')">{{ __('Manage notifications') }}</x-nav-link>
                </li>
                <li class="w-1/2">
                    <x-nav-link class="inline-flex items-center justify-center w-full" :href="localized_route('notification-list.show')" :active="request()->localizedRouteIs('notification-list.show')">{{ __('Notification list') }}</x-nav-link>
                </li>
            </ul>
        </div>
    </nav>

    <h2>{{ __('Manage my notifications') }}</h2>
    <p>{{ __('The Accessibility Exchange will occasionally send you notifications. This is different from how organizations you work with will reach out to you. To change how organizations will reach out to you, go to communication and meeting preferences.') }}</p>
    <div class="with-sidebar">
        <nav class="stack" aria-labelledby="skip-to">
            <h2 id="skip-to">{{ __('Skip to:') }}</h2>
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

            <h3 id="{{ Str::slug(__('Participating in engagements')) }}">{{ __('Participating in engagements') }}</h3>

            <h4>{{ __('Being invited to engagements') }}</h4>
            <p>
                {{ __('We will notify you about being invited to engagements by contacting :contact_person.', ['contact_person' => $individual->user->support_person_name ? __('your support person, :name', ['name' => $individual->user->support_person_name]) : __('you')]) }}
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

            <p>{!! __('If you need to change your contact person or contact details, you can do so on your :communication_and_consultation_preferences_page.', [
                'communication_and_consultation_preferences_page' => '<a href="'.localized_route('settings.edit-communication-and-consultation-preferences').'">'.__('communication and consultation preferences page').'</a>'
            ]) !!}</p>

            <div class="field @error('preferred_notification_method') field--error @enderror">
                <x-hearth-label for="preferred_notification_method">{{ __('Preferred notification method (required)') }}</x-hearth-label>
                <x-hearth-select name="preferred_notification_method" :options="$notificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" :disabled="is_null($individual->user->alternate_contact_point)" />
                @if(is_null($individual->user->alternate_contact_point))
                    <x-hearth-input name="preferred_notification_method" id="hidden-preferred_notification_method" type="hidden" :value="$user->preferred_notification_method" />
                    <x-hearth-hint for="preferred_notification_method">{{ __('You’ve only specified one contact method, so you can’t change your notification method. If you want to use a different contact method for notifications, you can add one in your [communication and consultation preferences](:url), then come back and change your notification method.', ['url' => localized_route('settings.edit-communication-and-consultation-preferences')]) }}</x-hearth-hint>
                @endif
            </div>

            <fieldset x-data="{notifyOfReports: {{ empty($user->notification_settings->get('reports.channels', [])) ? 0 : 1 }}}">
                <legend><h4>{{ __('New reports uploaded') }}</h4></legend>
                <p>{{ __('When a project you have worked on uploads a new report, a notification will show up on your website dashboard.') }}</p>
                <div class="field">
                    <x-hearth-radio-button name="notify_of_reports" value="1" :checked="old('notify_of_reports', empty($user->notification_settings->get('reports.channels', [])) ? 0 : 1) == 1" x-model.number="notifyOfReports" /> <x-hearth-label for="notify_of_reports-1">{{ __('Notify me') }}</x-hearth-label>
                </div>
                <div class="field__subfield stack" x-show="notifyOfReports == 1">
                    <x-hearth-checkboxes name="report_notifications" :options="$notificationChannels" :checked="old('report_notifications', $user->notification_settings->get('reports.channels', []))" />
                </div>
                <div class="field">
                    <x-hearth-radio-button name="notify_of_reports" value="0" :checked="old('notify_of_reports', empty($user->notification_settings->get('reports.channels', [])) ? 0 : 1) == 0" x-model.number="notifyOfReports" /> <x-hearth-label for="notify_of_reports-0">{{ __('Don’t notify me') }}</x-hearth-label>
                </div>
            </fieldset>

            <h3 id="{{ Str::slug(__('Finding out about new projects')) }}">{{ __('Finding out about new projects') }}</h3>

            <div x-data="{notifyOfProjects: {{ empty($user->notification_settings->get('projects.channels', [])) ? 0 : 1 }}}">
                <fieldset>
                    <legend><h4>{{ __('Please indicate if you would like to be notified of new projects.') }}</h4></legend>
                    <div class="field">
                        <x-hearth-radio-button name="notify_of_projects" value="1" :checked="old('notify_of_projects', empty($user->notification_settings->get('projects.channels', [])) ? 0 : 1) == 1" x-model.number="notifyOfProjects" /> <x-hearth-label for="notify_of_projects-1">{{ __('Notify me') }}</x-hearth-label>
                    </div>
                    <div class="field__subfield stack" x-show="notifyOfProjects == 1">
                        <x-hearth-checkboxes name="project_notifications" :options="$notificationChannels" :checked="old('project_notifications', $user->notification_settings->get('projects.channels', []))" />
                    </div>
                    <div class="field">
                        <x-hearth-radio-button name="notify_of_projects" value="0" :checked="old('notify_of_projects', empty($user->notification_settings->get('projects.channels', [])) ? 0 : 1) == 0" x-model.number="notifyOfProjects" /> <x-hearth-label for="notify_of_projects-0">{{ __('Don’t notify me') }}</x-hearth-label>
                    </div>
                </fieldset>

                <fieldset x-show="notifyOfProjects == 1">
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

                <fieldset x-show="notifyOfProjects == 1">
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

                <fieldset x-show="notifyOfProjects == 1">
                    <legend><h4>{{ __('Please indicate the type of new engagements for which you would like to receive notifications.') }}</h4></legend>
                    <x-hearth-hint for="engagements">{{ __('Please check all that apply.') }}</x-hearth-hint>
                    <x-hearth-checkboxes name="project_engagements" :options="[
                        [
                            'value' => 'lived-experience',
                            'label' => __('Projects that are looking for someone with my lived experience'),
                        ],
                        [
                            'value' => 'of-interest',
                            'label' => __('Engagements by organizations that I have saved on my notification list'),
                        ],
                    ]" :checked="old('project_engagements', $user->notification_settings->get('projects.engagements', []))" />
                </fieldset>
            </div>

            <h3 id="{{ Str::slug(__('Keeping my information up to date')) }}">{{ __('Keeping my information up to date') }}</h3>

            <fieldset x-data="{notifyOfUpdates: {{ empty($user->notification_settings->get('updates.channels', [])) ? 0 : 1 }}}">
                <legend><h4>{{ __('Out of date information') }}</h4></legend>
                <p>{{ __('Information such as your matching information, your communication preferences, and your meeting preferences might be out of date if it has not been updated for over a year.') }}</p>
                <div class="field">
                    <x-hearth-radio-button name="notify_of_updates" value="1" :checked="old('notify_of_updates', empty($user->notification_settings->get('updates.channels', [])) ? 0 : 1) == 1" x-model.number="notifyOfUpdates" /> <x-hearth-label for="notify_of_updates-1">{{ __('Notify me') }}</x-hearth-label>
                </div>
                <div class="field__subfield stack" x-show="notifyOfUpdates == 1">
                    <x-hearth-checkboxes name="update_notifications" :options="$notificationChannels" :checked="old('updates_notifications', $user->notification_settings->get('updates.channels', []))" />
                </div>
                <div class="field">
                    <x-hearth-radio-button name="notify_of_updates" value="0" :checked="old('notify_of_updates', empty($user->notification_settings->get('updates.channels', [])) ? 0 : 1) == 0" x-model.number="notifyOfUpdates" /> <x-hearth-label for="notify_of_updates-0">{{ __('Don’t notify me') }}</x-hearth-label>
                </div>
            </fieldset>

            <p>
                <button>{{ __('Save') }}</button>
            </p>
        </form>
    </div>
</x-app-wide-layout>
