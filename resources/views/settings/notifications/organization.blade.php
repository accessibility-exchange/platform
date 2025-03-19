<div class="with-sidebar">
    <form class="stack" action="{{ localized_route('settings.update-notification-preferences') }}" method="post"
        novalidate>
        @csrf
        @method('put')

        <h3 id="{{ Str::slug(__('Projects and engagements by other organizations')) }}">
            {{ __('Projects and engagements by other organizations') }}</h3>
        <x-interpretation name="{{ __('Projects and engagements by other organizations', [], 'en') }}"
            namespace="notifications_settings-organization" />

        <fieldset class="field @error('notification_settings.engagements') field--error @enderror">
            <legend>{{ __('Would you like to get notifications of new engagements?') }}</legend>
            <x-interpretation name="{{ __('Would you like to get notifications of new engagements?', [], 'en') }}"
                namespace="notifications_settings-individual" />
            <x-hearth-radio-buttons name="notification_settings[engagements]" :options="$yesNoOptions" :checked="old(
                'notification_settings.engagements',
                $user->organization->notification_settings->get('engagements', 0),
            )" />
            <x-hearth-error for="notification_settings.engagements" />
        </fieldset>

        {{-- <div x-data="{ notifyOfProjects: {{ json_encode(old('notification_settings.projects.channels', $user->organization->notification_settings->get('projects.channels', []))) }} }">
            <fieldset class="field @error('notification_settings.projects.channels') field--error @enderror">
                <legend class="h4">{{ __('Please indicate how you would like to be notified of new projects.') }}
                </legend>
                <x-interpretation
                    name="{{ __('Please indicate how you would like to be notified of new projects.', [], 'en') }}"
                    namespace="notifications_settings-organization" />
                <x-hearth-checkboxes name="notification_settings[projects][channels]" :options="$organizationNotificationChannels"
                    :checked="old(
                        'notification_settings.projects.channels',
                        $user->organization->notification_settings->get('projects.channels', []),
                    )" x-model="notifyOfProjects" />
            </fieldset>

            <fieldset class="field @error('notification_settings.projects.creators') field--error @enderror"
                x-show="notifyOfProjects.length > 0" x-cloak>
                <legend class="h4">
                    {{ __('Please indicate which type of organizations’ projects you would like to notified about.') }}
                </legend>
                <x-interpretation
                    name="{{ __('Please indicate which type of organizations’ projects you would like to notified about.', [], 'en') }}"
                    namespace="notifications_settings-organization" />
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
                        $user->organization->notification_settings->get('projects.creators', []),
                    )" hinted="project_creators-hint" />
                <x-hearth-error for="notification_settings.projects.creators" />
            </fieldset>

            <fieldset
                class="field @error('notification_settings.projects.types') field--error @enderror @error('notification_settings.projects.engagements') field--error @enderror"
                x-show="notifyOfProjects.length > 0" x-cloak>
                <legend>
                    {{ __('Please indicate which type of projects or engagements you would like to be notified about.') }}
                </legend>
                <x-interpretation
                    name="{{ __('Please indicate which type of projects or engagements you would like to be notified about.', [], 'en') }}"
                    namespace="notifications_settings-organization" />
                <x-hearth-hint for="project_engagement_types">{{ __('Please check all that apply.') }}</x-hearth-hint>
                <div class="field">
                    <x-hearth-checkbox id="constituent_projects" name="notification_settings[projects][types][]"
                        value="constituents" :checked="old(
                            'constituent_projects',
                            in_array(
                                'constituents',
                                $user->organization->notification_settings->get('projects.types', []),
                            ),
                        )" hinted="project_engagement_types-hint" />
                    <x-hearth-label for="constituent_projects" :value="__('Projects that are looking for people that my organization represents or supports')" />
                </div>
                <div class="field">
                    <x-hearth-checkbox id="constituent_engagements"
                        name="notification_settings[projects][engagements][]" value="constituents" :checked="old(
                            'constituent_engagements',
                            in_array(
                                'constituents',
                                $user->organization->notification_settings->get('projects.engagements', []),
                            ),
                        )"
                        hinted="project_engagement_types-hint" />
                    <x-hearth-label id="constituent_engagements" for="constituent_engagements" :value="__(
                        'Engagements that are looking for people that my organization represents or supports',
                    )" />
                </div>
            </fieldset>
        </div> --}}

        <x-interpretation name="{{ __('Save', [], 'en') }}" namespace="save" />
        <p>
            <button>{{ __('Save') }}</button>
        </p>
    </form>
</div>
