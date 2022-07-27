<div class="with-sidebar">
    <nav class="stack" aria-labelledby="skip-to">
        <h3 id="skip-to">{{ __('Skip to:') }}</h3>
        <ul role="list" class="stack">
            <li>
                <x-nav-link :href="'#'.Str::slug(__('Projects and engagements by other organizations'))">{{ __('Projects and engagements by other organizations') }}</x-nav-link>
            </li>
        </ul>
    </nav>
    <form class="stack" action="{{ localized_route('settings.update-notification-preferences') }}" method="post" novalidate>
        @csrf
        @method('put')

        <div class="box stack">
            <h3>{{ __('Contacting you with notifications') }}</h3>

            <p>
                {{ __('Throughout this page, you can chose whether you would like notifications to be sent through the website or by contacting your organization’s contact person directly. You’ve provided the following contact information:') }}
            </p>

            <ul>
                <li><strong>{{ __('Name') }}:</strong> {{ $user->organization->contact_person_name }}</li>
                <li><strong>{{ $user->organization->preferred_contact_method === 'email' ? __('Email') : __('Phone') }}:</strong> {{ $user->organization->preferred_contact_method === 'email' ? $user->organization->contact_person_email : $user->organization->contact_person_phone->formatForCountry('CA') }}</li>
                @if(($user->organization->preferred_contact_method === 'email' && $user->organization->contact_person_phone) || ($user->organization->preferred_contact_method === 'phone' && $user->organization->contact_person_email))
                    <li><strong>{{ $user->organization->preferred_contact_method === 'email' ? __('Phone') : __('Email') }}:</strong> {{ $user->organization->preferred_contact_method === 'email' ? $user->organization->contact_person_phone->formatForCountry('CA') : $user->organization->contact_person_email }}</li>
                @endif
            </ul>

            <p><a href="{{ localized_route('organizations.edit', ['organization' => $user->organization, 'step' => 4]) }}">{{ __('Edit your organization’s contact information') }}</a></p>

            <div class="field @error('preferred_notification_method') field--error @enderror">
                <x-hearth-label for="preferred_notification_method">{{ __('Preferred notification method (required)') }}</x-hearth-label>
                @if(!in_array('phone', $user->organization->contact_methods))
                    <x-hearth-select name="preferred_notification_method" :options="$emailNotificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
                @elseif(!in_array('email', $user->organization->contact_methods))
                    <x-hearth-select name="preferred_notification_method" :options="$phoneNotificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
                @else
                    <x-hearth-select name="preferred_notification_method" :options="$notificationMethods" :selected="old('preferred_notification_method', $user->preferred_notification_method)" />
                @endif
            </div>
        </div>

        <h3 id="{{ Str::slug(__('Projects and engagements by other organizations')) }}">{{ __('Projects and engagements by other organizations') }}</h3>

        <div x-data="{notifyOfProjects: {{ json_encode(old('notification_settings.projects.channels', $user->organization->notification_settings->get('projects.channels', []))) }}}">
            <fieldset class="field @error('notification_settings.projects.channels') field--error @enderror">
                <legend class="h4">{{ __('Please indicate how you would like to be notified of new projects. ') }}</legend>
                <x-hearth-checkboxes name="notification_settings[projects][channels]" :options="$organizationNotificationChannels" :checked="old('notification_settings.projects.channels', $user->organization->notification_settings->get('projects.channels', []))" x-model="notifyOfProjects" />
            </fieldset>

            <fieldset class="field @error('notification_settings.projects.creators') field--error @enderror" x-show="notifyOfProjects.length > 0" x-cloak>
                <legend class="h4">{{ __('Please indicate which type of organizations’ projects you would like to notified about.') }}</legend>
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
                    ]" :checked="old('notification_settings.projects.creators', $user->organization->notification_settings->get('projects.creators', []))" hinted="project_creators-hint" />
                <x-hearth-error for="notification_settings.projects.creators" />
            </fieldset>

            <fieldset class="field @error('notification_settings.projects.types') field--error @enderror @error('notification_settings.projects.engagements') field--error @enderror" x-show="notifyOfProjects.length > 0" x-cloak>
                <legend>{{ __('Please indicate which type of projects or engagements you would like to be notified about.') }}</legend>
                <x-hearth-hint for="project_engagement_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <div class="field">
                    <x-hearth-checkbox name="notification_settings[projects][types][]" id="constituent_projects" value="constituents" :checked="old('constituent_projects', in_array('constituents', $user->organization->notification_settings->get('projects.types', [])))" hinted="project_engagement_types-hint" />
                    <x-hearth-label for="constituent_projects" :value="__('Projects that are looking for people that my organization represents or supports')" />
                </div>
                <div class="field">
                    <x-hearth-checkbox name="notification_settings[projects][engagements][]" id="constituent_engagements" value="constituents" :checked="old('constituent_engagements', in_array('constituents', $user->organization->notification_settings->get('projects.engagements', [])))" hinted="project_engagement_types-hint" />
                    <x-hearth-label for="constituent_engagements" id="constituent_engagements" :value="__('Engagements that are looking for people that my organization represents or supports')" />
                </div>
            </fieldset>
        </div>

        <p>
            <button>{{ __('Save') }}</button>
        </p>
    </form>
</div>
