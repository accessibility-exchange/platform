
<x-app-layout>
    <x-slot name="title">{{ __('Edit federally regulated organization “:name”', ['name' => $regulatedOrganization->name]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit federally regulated organization “:name”', ['name' => $regulatedOrganization->name]) }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('regulated-organizations.update', $regulatedOrganization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <x-hearth-label for="name" :value="__('Regulated federally regulated organization name')" />
            <x-hearth-input id="name" type="text" name="name" :value="old('name', $regulatedOrganization->name)" required />
            </div>
        <div class="field">
            <x-hearth-label for="locality" :value="__('forms.label_locality')" />
            <x-hearth-input id="locality" type="text" name="locality" :value="old('locality', $regulatedOrganization->locality)" required />
        </div>
        <div class="field">
            <x-hearth-label for="region" :value="__('forms.label_region')" />
            <x-hearth-select id="region" name="region" :selected="old('region', $regulatedOrganization->region)" required :options="$regions"/>
            </div>

        <button>{{ __('Save changes') }}</button>
    </form>

    <h2>{{ __('Regulated federally regulated organization members') }}</h2>

    <div role="region" aria-label="{{ __('Regulated federally regulated organization members') }}" tabindex="0">
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
            @foreach ($regulatedOrganization->users as $user)
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
                        <button class="link" :aria-label="__('Remove :user from :regulatedOrganization', ['user' => $user->name, 'regulatedOrganization' => $regulatedOrganization->name])">
                            {{ __('Remove') }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <h2>{{ __('invitation.invitations_title') }}</h2>

    @if($regulatedOrganization->invitations->count() > 0)
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
            @foreach ($regulatedOrganization->invitations as $invitation)
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

    <form action="{{ localized_route('invitations.create') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input type="hidden" name="inviteable_id" :value="$regulatedOrganization->id"></x-hearth-input>
        <x-hearth-input type="hidden" name="inviteable_type" :value="get_class($regulatedOrganization)"></x-hearth-input>
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

    <h2>
        {{ __('Delete federally regulated organization') }}
    </h2>

    <p>{{ __('Your federally regulated organization will be deleted and cannot be recovered. If you still want to delete your federally regulated organization, please enter your current password to proceed.') }}</p>

    <form action="{{ localized_route('regulated-organizations.destroy', $regulatedOrganization) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-password-input name="current_password" />
            <x-hearth-error for="current_password" bag="destroyOrganization" />
        </div>

        <button>
            {{ __('Delete federally regulated organization') }}
        </button>
    </form>
</x-app-layout>
