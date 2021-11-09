
<x-app-layout>
    <x-slot name="title">{{ __('Edit regulated entity “:name”', ['name' => $entity->name]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit regulated entity “:name”', ['name' => $entity->name]) }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('entities.update', $entity) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <x-hearth-label for="name" :value="__('Regulated entity name')" />
            <x-hearth-input id="name" type="text" name="name" :value="old('name', $entity->name)" required />
            </div>
        <div class="field">
            <x-hearth-label for="locality" :value="__('forms.label_locality')" />
            <x-hearth-input id="locality" type="text" name="locality" :value="old('locality', $entity->locality)" required />
        </div>
        <div class="field">
            <x-hearth-label for="region" :value="__('forms.label_region')" />
            <x-hearth-select id="region" name="region" :selected="old('region', $entity->region)" required :options="$regions"/>
            </div>

        <x-hearth-button>{{ __('Save changes') }}</x-hearth-button>
    </form>

    <h2>{{ __('Regulated entity members') }}</h2>

    <div role="region" aria-label="{{ __('Regulated entity members') }}" tabindex="0">
        <table>
            <thead>
                <tr>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Status') }}</th>
                  <th>{{ __('Role') }}</th>
                  <th></th>
                  <th></th>
                </tr>
            </thead>
            @foreach ($entity->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ __('Active') }}</td>
                <td>{{ __('roles.' . $user->membership->role) }}</td>
                <td>
                    <a aria-label="{{ __('Edit :user’s role', ['user' => $user->name]) }}" href="{{ localized_route('memberships.edit', $user->membership->id) }}">{{ __('Edit') }}</a>
                </td>
                <td>
                    <form action="{{ route('memberships.destroy', $user->membership->id) }}" method="POST">
                        @csrf
                        @method('delete')
                        <x-hearth-button class="link" :aria-label="__('Remove :user from :entity', ['user' => $user->name, 'entity' => $entity->name])">
                            {{ __('Remove') }}
                        </x-hearth-button>
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
                        <x-hearth-button class="link" :aria-label="__('invitation.cancel_member_invitation_link_with_email', ['email' => $invitation->email])">
                            {{ __('invitation.cancel_member_invitation_link') }}
                        </x-hearth-button>
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
        <x-hearth-input type="hidden" name="inviteable_id" :value="$entity->id"></x-hearth-input>
        <x-hearth-input type="hidden" name="inviteable_type" :value="get_class($entity)"></x-hearth-input>
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

        <x-hearth-button>
            {{ __('invitation.action_send_invitation') }}
        </x-hearth-button>
    </form>

    <h2>
        {{ __('Delete regulated entity') }}
    </h2>

    <p>{{ __('Your regulated entity will be deleted and cannot be recovered. If you still want to delete your regulated entity, please enter your current password to proceed.') }}</p>

    <form action="{{ localized_route('entities.destroy', $entity) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input id="current_password" type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyOrganization" />
        </div>

        <x-hearth-button>
            {{ __('Delete regulated entity') }}
        </x-hearth-button>
    </form>
</x-app-layout>
