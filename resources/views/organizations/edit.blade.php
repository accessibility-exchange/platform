
<x-app-layout>
    <x-slot name="title">{{ __('organization.edit_title', ['name' => $organization->name]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('organization.edit_title', ['name' => $organization->name]) }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('organizations.update', $organization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field @error('name') field--error @enderror">
            <x-hearth-label for="name" :value="__('organization.label_name')" />
            <x-hearth-input id="name" type="text" name="name" :value="old('name', $organization->name)" required />
            <x-hearth-error for="name" />
        </div>
        <div class="field @error('locality') field--error @enderror">
            <x-hearth-label for="locality" :value="__('forms.label_locality')" />
            <x-hearth-input id="locality" type="text" name="locality" :value="old('locality', $organization->locality)" required />
            <x-hearth-error for="locality" />
        </div>
        <div class="field @error('region') field--error @enderror">
            <x-hearth-label for="region" :value="__('forms.label_region')" />
            <x-hearth-select id="region" name="region" :selected="old('region', $organization->region)" required :options="$regions"/>
            <x-hearth-error for="region" />
        </div>

        <button>{{ __('Save changes') }}</button>
    </form>

    <h2>{{ __('organization.members_title') }}</h2>

    <div role="region" aria-label="{{ __('organization.members_title') }}" tabindex="0">
        <table>
            <thead>
                <tr>
                  <th>{{ __('organization.member_name') }}</th>
                  <th>{{ __('organization.member_status') }}</th>
                  <th>{{ __('organization.member_role') }}</th>
                  <th></th>
                  <th></th>
                </tr>
            </thead>
            @foreach ($organization->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ __('organization.member_active') }}</td>
                <td>{{ __('roles.' . $user->membership->role) }}</td>
                <td>
                    <a aria-label="{{ __('organization.edit_user_role_link_with_name', ['user' => $user->name]) }}" href="{{ localized_route('memberships.edit', $user->membership->id) }}">{{ __('organization.edit_user_role_link') }}</a>
                </td>
                <td>
                    <form action="{{ route('memberships.destroy', $user->membership->id) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button class="link" :aria-label="__('organization.action_remove_member_with_name', ['user' => $user->name, 'organization' => $organization->name])">
                            {{ __('organization.action_remove_member') }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <h2>{{ __('invitation.invitations_title') }}</h2>

    @if($organization->invitations->count() > 0)
    <div role="region" aria-label="{{ __('invitation.invitations_title') }}" tabindex="0">
        <table>
            <thead>
                <tr>
                  <th>{{ __('invitation.invitation_email') }}</th>
                  <th>{{ __('invitation.invitation_status') }}</th>
                  <th>{{ __('invitation.invitation_role') }}</th>
                  <th></th>
                </tr>
            </thead>
            @foreach ($organization->invitations as $invitation)
            <tr>
                <td id="invitation-{{ $invitation->id }}">{{ $invitation->email }}</td>
                <td>{{ __('invitation.member_invited') }}</td>
                <td>{{ __('roles.' . $invitation->role) }}</td>
                <td>
                    <form action="{{ route('invitations.destroy', $invitation) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button class="link" :aria-label="__('invitation.cancel_member_invitation_link_with_email', ['email' => $invitation->email])">
                            {{ __('invitation.cancel_member_invitation_link') }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <h2>{{ __('Requests to join') }}</h2>

    @if(!$organization->requestsToJoin->isEmpty())
    <div role="region" aria-label="{{ __('Requests to join') }}" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email address') }}</th>
                <th></th>
            </tr>
            </thead>
            @foreach ($organization->requestsToJoin as $user)
                <tr>
                    <td id="request-{{ $user->id }}">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action="{{ localized_route('requests.deny', $user) }}" method="POST">
                            @csrf
                            <button class="link" aria-label="{{ __('Deny :name’s request to join :organization', ['name' => $user->name, 'organization' => $organization->name]) }}">
                                {{ __('Deny request') }}
                            </button>
                        </form>

                        <form action="{{ localized_route('requests.approve', $user) }}" method="POST">
                            @csrf
                            <button class="link" aria-label="{{ __('Approve :name’s request to join :organization', ['name' => $user->name, 'organization' => $organization->name]) }}">
                                {{ __('Approve request') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif

    <h3>{{ __('invitation.invite_title') }}</h3>

    <p>{{ __('invitation.invite_intro') }}</p>

    <form action="{{ localized_route('invitations.create') }}" method="POST" novalidate>
        @csrf

        <x-hearth-input type="hidden" name="invitationable_id" :value="$organization->id"></x-hearth-input>

        <x-hearth-input type="hidden" name="invitationable_type" :value="get_class($organization)"></x-hearth-input>

        <div class="field @error('email', 'inviteOrganizationMember') field--error @enderror">
            <x-hearth-label for="email" :value="__('forms.label_email')" />
            <x-hearth-input id="email" type="email" name="email" :value="old('email')" required />
            <x-hearth-error for="email" bag="inviteOrganizationMember" />
        </div>
        <div class="field @error('role', 'inviteOrganizationMember') field--error @enderror">
            <x-hearth-label for="role" :value="__('organization.member_role')" />
            <x-hearth-select id="role" type="role" name="role" :options="$roles" :selected="old('role')" required />
            <x-hearth-error for="role" bag="inviteOrganizationMember" />
        </div>

        <button>
            {{ __('invitation.action_send_invitation') }}
        </button>
    </form>

    <h2>
        {{ __('organization.delete_title') }}
    </h2>

    <p>{{ __('organization.delete_intro') }}</p>

    <form action="{{ localized_route('organizations.destroy', $organization) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyOrganization') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-password-input name="current_password" />
            <x-hearth-error for="current_password" bag="destroyOrganization" />
        </div>

        <button>
            {{ __('organization.action_delete') }}
        </button>
    </form>
</x-app-layout>
