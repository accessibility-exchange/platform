<div class="with-sidebar">
    <nav class="stack" aria-labelledby="skip-to">
        <h3 id="skip-to">{{ __('Skip to:') }}</h3>
        <ul class="stack" role="list">
            <li>
                <x-nav-link :href="'#' . Str::slug(__('Participating in engagements'))">{{ __('Participating in engagements') }}</x-nav-link>
            </li>
            <li>
                <x-nav-link :href="'#' . Str::slug(__('Finding out about new projects'))">{{ __('Finding out about new projects') }}</x-nav-link>
            </li>
            <li>
                <x-nav-link :href="'#' . Str::slug(__('Keeping my information up to date'))">{{ __('Keeping my information up to date') }}</x-nav-link>
            </li>
        </ul>
    </nav>
    <form class="stack" action="{{ localized_route('settings.update-notification-preferences') }}" method="post"
        novalidate>
        @csrf
        @method('put')

        <div class="box stack">
            <h3>{{ __('Contacting you with notifications') }}</h3>

            <p>
                {{ __('Throughout this page, you can chose whether you would like notifications to be sent through the website or by contacting :contact_person directly.', ['contact_person' => $user->support_person_name ? __('your support person, :name', ['name' => $user->support_person_name]) : __('you')]) }}
                @if ($user->support_person_name)
                    {{ __('You’ve provided the following contact information for them:') }}
                @else
                    {{ __('You’ve provided the following contact information:') }}
                @endif
            </p>

            <ul>
                <li><strong>{{ $user->preferred_contact_method === 'email' ? __('Email') : __('Phone') }}:</strong>
                    {!! Str::inlineMarkdown($user->primary_contact_point) !!}</li>
                @if ($user->alternate_contact_point)
                    <li><strong>{{ $user->preferred_contact_method === 'email' ? __('Phone') : __('Email') }}:</strong>
                        {!! Str::inlineMarkdown($user->alternate_contact_point) !!}</li>
                @endif
            </ul>

            <p><a href="{{ localized_route('settings.edit-communication-and-consultation-preferences') }}">@svg('heroicon-o-pencil', 'mr-1')
                    {{ __('Edit your contact information') }}</a>
            </p>

            <div class="field @error('preferred_notification_method') field--error @enderror">
                <x-hearth-label for="preferred_notification_method">
                    {{ __('Preferred notification method') . ' ' . __('(required)') }}</x-hearth-label>
                @if (!in_array('phone', $user->contact_methods))
                    <x-hearth-select name="preferred_notification_method" :options="$emailNotificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
                @elseif(!in_array('email', $user->contact_methods))
                    <x-hearth-select name="preferred_notification_method" :options="$phoneNotificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
                @else
                    <x-hearth-select name="preferred_notification_method" :options="$notificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
                @endif
            </div>
        </div>

        <h3 id="{{ Str::slug(__('Participating in engagements')) }}">{{ __('Participating in engagements') }}</h3>

        @if ($user->individual->isParticipant())
            <h4>{{ __('As a Consultation Participant') }}</h4>

            <p>
                {{ __('We will notify you about being invited to engagements by directly contacting you or your support person.') }}
            </p>
        @endif

        @if ($user->individual->isConsultant())
            <fieldset>
                <legend>{{ __('As an accessibility consultant') }}</legend>
                <x-hearth-hint for="consultants-contact">
                    {{ __('Would you like to be notified directly when you are added as an Accessibility Consultant to a project?') }}<br />
                    {{ __('You will always get a notification on the website.') }}
                </x-hearth-hint>
                <div class="field">
                    <x-hearth-input name="notification_settings[consultants][channels][]" type="hidden"
                        value="website" />
                    <x-hearth-checkbox id="consultants-contact" name="notification_settings[consultants][channels][]"
                        value="contact" :checked="in_array(
                            'contact',
                            old(
                                'notification_settings.consultants.channels',
                                $user->notification_settings->get('consultants.channels', []),
                            ),
                        )" hinted />
                    <x-hearth-label for="consultants-contact" :value="__('Notify me or my support person directly')" />
                </div>
            </fieldset>
        @endif

        @if ($user->individual->isConnector())
            <fieldset>
                <legend>{{ __('As a Community Connector') }}</legend>
                <x-hearth-hint for="connectors-contact">
                    {{ __('Would you like to be notified directly when you are added to an engagement as a Community Connector?') }}<br />
                    {{ __('You will always get a notification on the website.') }}
                </x-hearth-hint>
                <x-hearth-input name="notification_settings[connectors][channels][]" type="hidden" value="website" />
                <x-hearth-checkbox id="connectors-contact" name="notification_settings[connectors][channels][]"
                    value="contact" :checked="in_array(
                        'contact',
                        old(
                            'notification_settings.connectors.channels',
                            $user->notification_settings->get('connectors.channels', []),
                        ),
                    )" hinted />
                <x-hearth-label for="connectors-contact" :value="__('Notify me or my support person directly')" />
            </fieldset>
        @endif

        <fieldset>
            <legend>{{ __('New reports uploaded') }}</legend>
            <x-hearth-hint for="reports-contact">
                {{ __('Would you like to be notified directly when a project you have worked on uploads a new report?') }}<br />
                {{ __('You will always get a notification on the website.') }}
            </x-hearth-hint>
            <x-hearth-input name="notification_settings[reports][channels][]" type="hidden" value="website" />
            <x-hearth-checkbox id="reports-contact" name="notification_settings[reports][channels][]" value="contact"
                :checked="in_array(
                    'contact',
                    old(
                        'notification_settings.reports.channels',
                        $user->notification_settings->get('reports.channels', []),
                    ),
                )" hinted />
            <x-hearth-label for="reports-contact" :value="__('Notify me or my support person directly')" />
        </fieldset>

        <h3 id="{{ Str::slug(__('Finding out about new projects')) }}">{{ __('Finding out about new projects') }}</h3>

        <div x-data="{ notifyOfProjects: {{ json_encode(old('notification_settings.projects.channels', $user->notification_settings->get('projects.channels', []))) }} }">
            <fieldset class="field @error('notification_settings.projects.channels') field--error @enderror">
                <legend>{{ __('Please indicate how you would like to be notified of new projects. ') }}</legend>
                <x-hearth-checkboxes name="notification_settings[projects][channels]" :options="$notificationChannels"
                    :checked="old(
                        'notification_settings.projects.channels',
                        $user->notification_settings->get('projects.channels', []),
                    )" x-model="notifyOfProjects" />
            </fieldset>

            <fieldset class="field @error('notification_settings.projects.creators') field--error @enderror"
                x-show="notifyOfProjects.length > 0" x-cloak>
                <legend>
                    {{ __('Please indicate which type of organizations’ projects you would like to notified about.') }}
                </legend>
                <x-hearth-hint for="project_creators">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="notification_settings[projects][creators]" :options="[
                    [
                        'value' => 'regulated-organizations',
                        'label' => __('Governments, businesses, and other public sector organizations'),
                    ],
                    [
                        'value' => 'organizations',
                        'label' => __('Community organizations'),
                    ],
                ]"
                    :checked="old(
                        'notification_settings.projects.creators',
                        $user->notification_settings->get('projects.creators', []),
                    )" hinted="project_creators" />
                <x-hearth-error for="notification_settings.projects.creators" />
            </fieldset>

            <fieldset class="field @error('notification_settings.projects.types') field--error @enderror"
                x-show="notifyOfProjects.length > 0" x-cloak>
                <legend>{{ __('Please indicate which type of projects you would like to notified about.') }}</legend>
                <x-hearth-hint for="project_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="notification_settings[projects][types]" :options="$projectNotificationTypes" :checked="old(
                    'notification_settings.projects.types',
                    $user->notification_settings->get('projects.types', []),
                )"
                    hinted="project_types" />
                <x-hearth-error for="notification_settings.projects.types" />
            </fieldset>

            <fieldset class="field @error('notification_settings.projects.engagements') field--error @enderror"
                x-show="notifyOfProjects.length > 0" x-cloak>
                <legend>{{ __('Please indicate which type of engagements you would like to be notified about.') }}
                </legend>
                <x-hearth-hint for="engagements">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <x-hearth-checkboxes name="notification_settings[projects][engagements]" :options="$engagementNotificationTypes"
                    :checked="old(
                        'notification_settings.projects.engagements',
                        $user->notification_settings->get('projects.engagements', []),
                    )" hinted="engagements" />
                <x-hearth-error for="notification_settings.projects.engagements" />
            </fieldset>
        </div>

        <h3 id="{{ Str::slug(__('Keeping my information up to date')) }}">
            {{ __('Keeping my information up to date') }}</h3>

        <fieldset class="field @error('notification_settings.updates.channels') field--error @enderror">
            <legend>
                {{ __('Please indicate how you would like to be notified to review and update your information.') }}
            </legend>
            @if ($user->individual->isParticipant())
                <p>{{ __('Information such as your matching information, your communication preferences, and your consultation preferences might be out of date if it has not been updated for over a year.') }}
                </p>
            @else
                <p>{{ __('Information such as your communication and consultation preferences might be out of date if it has not been updated for over a year.') }}
                </p>
            @endif
            <x-hearth-checkboxes name="notification_settings[updates][channels]" :options="$notificationChannels"
                :checked="old(
                    'notification_settings.updates.channels',
                    $user->notification_settings->get('updates.channels', []),
                )" />
        </fieldset>

        <p>
            <button>{{ __('Save') }}</button>
        </p>
    </form>
</div>
