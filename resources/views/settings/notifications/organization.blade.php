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

        <x-interpretation name="{{ __('Save', [], 'en') }}" namespace="save" />
        <p>
            <button>{{ __('Save') }}</button>
        </p>
    </form>
</div>
