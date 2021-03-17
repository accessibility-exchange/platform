
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('organizations.update', $organization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <x-label for="name" :value="__('organization.label_name')" />
            <x-input id="name" type="name" name="name" :value="old('name', $organization->name)" required />
            </div>
        <div class="field">
            <x-label for="locality" :value="__('forms.label_locality')" />
            <x-input id="locality" type="locality" name="locality" :value="old('locality', $organization->locality)" required />
        </div>
        <div class="field">
            <x-label for="region" :value="__('forms.label_region')" />
            <x-region-select :selected="old('region', $organization->region)" required />
        </div>

        <x-button>{{ __('forms.save_changes') }}</x-button>
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
                </tr>
            </thead>
            @foreach ($organization->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ __('organization.member_active') }}</td>
                <td>{{ __('roles.' . $user->membership->role) }}</td>
                <td><a aria-label="{{ __('organization.edit_user_role_link_with_name', ['user' => $user->name]) }}" href="{{ localized_route('organization-user.edit', ['organization' => $organization, 'user' => $user]) }}">{{ __('organization.edit_user_role_link') }}</a></td>
            </tr>
            @endforeach
        </table>
    </div>

    <h2>{{ __('organization.invitations_title') }}</h2>

    @if($organization->organizationInvitations->count() > 0)
    <div role="region" aria-label="{{ __('organization.invitations_title') }}" tabindex="0">
        <table>
            <thead>
                <tr>
                  <th>{{ __('organization.invitation_email') }}</th>
                  <th>{{ __('organization.invitation_status') }}</th>
                  <th>{{ __('organization.invitation_role') }}</th>
                  <th></th>
                </tr>
            </thead>
            @foreach ($organization->organizationInvitations as $invitation)
            <tr>
                <td id="invitation-{{ $invitation->id }}">{{ $invitation->email }}</td>
                <td>{{ __('organization.member_invited') }}</td>
                <td>{{ __('roles.' . $invitation->role) }}</td>
                <td>
                    <form action="{{ route('organization-invitations.destroy', ['organization' => $organization, 'invitation' => $invitation]) }}" method="POST">
                        @csrf
                        @method('delete')
                        <x-button class="link" :aria-label="__('organization.cancel_member_invitation_link_with_email', ['email' => $invitation->email])">
                            {{ __('organization.cancel_member_invitation_link') }}
                        </x-button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <h3>{{ __('organization.invite_title') }}</h3>

    <p>{{ __('organization.invite_intro') }}</p>

    <form action="{{ localized_route('organization-invitations.create', $organization) }}" method="POST" novalidate>
        @csrf
        <div class="field">
            <x-label for="email" :value="__('forms.label_email')" />
            <x-input id="email" type="email" name="email" :value="old('email')" required />
            @error('email', 'inviteOrganizationMember')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>
        <div class="field">
            <x-label for="role" :value="__('organization.member_role')" />
            <x-select id="role" type="role" name="role" :options="$roles" :selected="old('role')" required />
            @error('role', 'inviteOrganizationMember')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-button>
            {{ __('organization.action_send_invitation') }}
        </x-button>
    </form>

    <h2>
        {{ __('organization.delete_title') }}
    </h2>

    <p>{{ __('organization.delete_intro') }}</p>

    <form action="{{ localized_route('organizations.destroy', $organization) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-label for="current_password" :value="__('auth.label_current_password')" />
            <x-input id="current_password" type="password" name="current_password" required />
            @error('current_password', 'destroyOrganization')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-button>
            {{ __('organization.action_delete') }}
        </x-button>
    </form>
</x-app-layout>
