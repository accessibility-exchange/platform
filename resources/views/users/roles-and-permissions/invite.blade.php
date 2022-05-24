<x-app-layout>
    <x-slot name="title">{{ __('Invite new member') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('users.settings') }}">{{ __('Settings') }}</a></li>
            <li><a href="{{ localized_route('users.edit-roles-and-permissions') }}">{{ __('Roles and permissions') }}</a></li>
        </ol>
        <h1>
            {{ __('Invite new member') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2>{{ __('invitation.invitations_title') }}</h2>

    @if($invitationable->invitations->count() > 0)
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
                @foreach ($invitationable->invitations as $invitation)
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

    <h3>{{ __('invitation.invite_title') }}</h3>

    <p>{{ __('invitation.invite_intro') }}</p>

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
