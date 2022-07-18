<x-app-layout>
    <x-slot name="title">{{ __('Invite new member') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
            <li><a href="{{ localized_route('users.edit-roles-and-permissions') }}">{{ __('Roles and permissions') }}</a></li>
        </ol>
        <h1>
            {{ __('Invite new member') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Invite someone to become a member of your organization. If they do not have an account on this website yet, they will be invited to create one first.') }}</p>

    <form class="stack" action="{{ localized_route('invitations.create') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input type="hidden" name="invitationable_id" :value="$invitationable->id"></x-hearth-input>
        <x-hearth-input type="hidden" name="invitationable_type" :value="get_class($invitationable)"></x-hearth-input>
        <div class="field">
            <x-hearth-label for="email" :value="__('hearth::forms.label_email')" />
            <x-hearth-input type="email" name="email" :value="old('email')" required />
            <x-hearth-error for="email" bag="inviteOrganizationMember" />
        </div>
        <div class="field">
            <x-hearth-label for="role" :value="__('Role')" />
            <x-hearth-select type="role" name="role" :options="$roles" :selected="old('role')" required />
            <x-hearth-error for="role" bag="inviteOrganizationMember" />
        </div>

        <button>
            {{ __('invitation.action_send_invitation') }}
        </button>
    </form>
</x-app-layout>
