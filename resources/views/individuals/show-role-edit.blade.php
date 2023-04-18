<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Edit your role') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit your role') }}
        </h1>

        <h2>{{ __('Please tell us what you would like to do on this website.') }}</h2>
    </x-slot>

    <p>{{ __('You can always change this later.') }} <a
            href="{{ localized_route('about.for-individuals') }}">{{ __('Learn more about these roles') }}</a></p>

    <form class="stack" action="{{ localized_route('individuals.save-roles') }}" method="post" novalidate
        x-data="{ initialRoles: {{ json_encode($individual->roles) }}, roles: {{ json_encode(old('roles', $individual->roles ?? [])) }} }">
        <fieldset class="field @error('roles') field--error @enderror">
            <x-hearth-checkboxes name="roles" :options="$roles" :checked="old('roles', $individual->roles ?? [])" x-model="roles" />
            <x-hearth-error for="roles" />
        </fieldset>

        <div role="alert">
            <x-hearth-alert type="warning" x-cloak
                x-show="(initialRoles.includes('{{ App\Enums\IndividualRole::AccessibilityConsultant->value }}') || initialRoles.includes('{{ App\Enums\IndividualRole::CommunityConnector->value }}')) && !roles.includes('{{ App\Enums\IndividualRole::AccessibilityConsultant->value }}') && !roles.includes('{{ App\Enums\IndividualRole::CommunityConnector->value }}')">
                {{ __('By selecting Consultation Participant as your only role, your role no longer will include the Accessibility Consultant or Community Connector roles. You do not need a profile to be a Consultation Participant, so your profile will be unpublished and saved, and will no longer be visible by other members of The Accessibility Exchange. However, if you edit your role to add the Accessibility Consultant or Community Connector roles again, you will be able to publish your profile again all your saved information will be restored.') }}
            </x-hearth-alert>
        </div>

        <p class="repel">
            <button class="secondary" type="button" x-on:click="history.back()">{{ __('Cancel') }}</button>
            <button>{{ __('Update') }}</button>
        </p>

        @method('put')
        @csrf
    </form>
</x-app-layout>
