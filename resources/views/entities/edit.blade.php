
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('entity.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('entities.update', $entity) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <x-label for="name" :value="__('entity.label_name')" />
            <x-input id="name" type="text" name="name" :value="old('name', $entity->name)" required />
            </div>
        <div class="field">
            <x-label for="locality" :value="__('forms.label_locality')" />
            <x-input id="locality" type="locality" name="locality" :value="old('locality', $entity->locality)" required />
        </div>
        <div class="field">
            <x-label for="region" :value="__('forms.label_region')" />
            <x-region-select :selected="old('region', $entity->region)" required />
        </div>

        <x-button>{{ __('forms.save_changes') }}</x-button>
    </form>

    <h2>{{ __('entity.members_title') }}</h2>

    <div role="region" aria-label="{{ __('entity.members_title') }}" tabindex="0">
        <table>
            <thead>
                <tr>
                  <th>{{ __('entity.member_name') }}</th>
                  <th>{{ __('entity.member_status') }}</th>
                  <th>{{ __('entity.member_role') }}</th>
                  <th></th>
                  <th></th>
                </tr>
            </thead>
            @foreach ($entity->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ __('entity.member_active') }}</td>
                <td>{{ __('roles.' . $user->membership->role) }}</td>
                <td>
                    <a aria-label="{{ __('entity.edit_user_role_link_with_name', ['user' => $user->name]) }}" href="{{ localized_route('memberships.edit', $user->membership->id) }}">{{ __('entity.edit_user_role_link') }}</a>
                </td>
                <td>
                    <form action="{{ route('memberships.destroy', $user->membership->id) }}" method="POST">
                        @csrf
                        @method('delete')
                        <x-button class="link" :aria-label="__('entity.action_remove_member_with_name', ['user' => $user->name, 'entity' => $entity->name])">
                            {{ __('entity.action_remove_member') }}
                        </x-button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <h2>{{ __('invitation.invitations_title') }}</h2>

    @if($entity->invitations->count() > 0)
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
            @foreach ($entity->invitations as $invitation)
            <tr>
                <td id="invitation-{{ $invitation->id }}">{{ $invitation->email }}</td>
                <td>{{ __('invitation.member_invited') }}</td>
                <td>{{ __('roles.' . $invitation->role) }}</td>
                <td>
                    <form action="{{ route('invitations.destroy', $invitation) }}" method="POST">
                        @csrf
                        @method('delete')
                        <x-button class="link" :aria-label="__('invitation.cancel_member_invitation_link_with_email', ['email' => $invitation->email])">
                            {{ __('invitation.cancel_member_invitation_link') }}
                        </x-button>
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
        <x-input type="hidden" name="inviteable_id" :value="$entity->id"></x-input>
        <x-input type="hidden" name="inviteable_type" :value="$entity->getModelClass()"></x-input>
        <div class="field">
            <x-label for="email" :value="__('forms.label_email')" />
            <x-input id="email" type="email" name="email" :value="old('email')" required />
            @error('email', 'inviteOrganizationMember')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>
        <div class="field">
            <x-label for="role" :value="__('entity.member_role')" />
            <x-select id="role" type="role" name="role" :options="$roles" :selected="old('role')" required />
            @error('role', 'inviteOrganizationMember')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-button>
            {{ __('invitation.action_send_invitation') }}
        </x-button>
    </form>

    <h2>
        {{ __('entity.delete_title') }}
    </h2>

    <p>{{ __('entity.delete_intro') }}</p>

    <form action="{{ localized_route('entities.destroy', $entity) }}" method="POST" novalidate>
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
            {{ __('entity.action_delete') }}
        </x-button>
    </form>
</x-app-layout>
