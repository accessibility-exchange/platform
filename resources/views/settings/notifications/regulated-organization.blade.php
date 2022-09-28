<div class="with-sidebar">
    <nav class="stack" aria-labelledby="skip-to">
        <h3 id="skip-to">{{ __('Skip to:') }}</h3>
        <ul class="stack" role="list">
            <li>
                <x-nav-link :href="'#' . Str::slug(__('Your projects and engagements'))">{{ __('Your projects and engagements') }}</x-nav-link>
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
                {{ __('Throughout this page, you can choose whether you would like notifications to be sent through the website or by contacting the project team contact for that specific project directly. You can edit this in each individual project page.') }}
            </p>
        </div>

        <h3 id="{{ Str::slug(__('Your projects and engagements')) }}">{{ __('Your projects and engagements') }}</h3>

        <p>{{ __('For your projects and engagements, you can chose whether you would like notifications to be sent through the website or by contacting the contact person for that specific project directly.') }}
        </p>

        <fieldset class="field @error('notification_settings.participants.channels') field--error @enderror">
            <legend>
                {{ __('Please indicate how you would like to be notified of a new person or people being added to your engagements.') }}
            </legend>
            <x-hearth-checkboxes name="notification_settings[participants][channels]" :options="$projectNotificationChannels"
                :checked="old(
                    'notification_settings.participants.channels',
                    $user->regulatedOrganization->notification_settings->get('participants.channels', []),
                )" />
        </fieldset>

        <fieldset class="field @error('notification_settings.estimates.channels') field--error @enderror">
            <legend>
                {{ __('Please indicate how you would like to be notified of a project estimate that has been returned for you to review.') }}
            </legend>
            <x-hearth-checkboxes name="notification_settings[estimates][channels]" :options="$projectNotificationChannels"
                :checked="old(
                    'notification_settings.estimates.channels',
                    $user->regulatedOrganization->notification_settings->get('estimates.channels', []),
                )" />
        </fieldset>

        <p>
            <button>{{ __('Save') }}</button>
        </p>
    </form>
</div>
