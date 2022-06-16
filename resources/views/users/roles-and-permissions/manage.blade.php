<p>
    <a class="cta" href="{{ localized_route('users.invite-to-invitationable') }}">{{ __('Invite new member') }}</a>
</p>

<h2 id="pending-invitations">{{ __('Pending invitations') }}</h2>

<div role="region" aria-labelledby="pending-invitations" tabindex="0">
    <table>
        <thead>
        <tr>
            <th>{{ __('Email address') }}</th>
            <th>{{ __('Role') }}</th>
            <th></th>
        </tr>
        </thead>
        @forelse ($membershipable->invitations as $invitation)
            <tr>
                <td id="invitation-{{ $invitation->id }}">{{ $invitation->email }}</td>
                <td>{{ __('roles.' . $invitation->role) }}</td>
                <td>
                    <form action="{{ route('invitations.destroy', $invitation) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button class="secondary" :aria-label="__('invitation.cancel_member_invitation_link_with_email', ['email' => $invitation->email])">
                            {{ __('Cancel invitation') }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td>{{ __('None found.') }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforelse
    </table>
</div>

<h2 id="your-members">{{ __('Your members') }}</h2>

<div role="region" aria-labelledby="your-members" tabindex="0">
    <table>
        <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email address') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Role') }}</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        @foreach ($membershipable->users as $member)
            <tr>
                <td>{{ $member->name }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ __('Active') }}</td>
                <td>{{ __('roles.' . $member->membership->role) }}</td>
                <td>
                    <a class="cta secondary" {{ __('Edit :user’s role', ['user' => $member->name]) }}" href="{{ localized_route('memberships.edit', $member->membership->id) }}">{{ __('Edit') }}</a>
                </td>
                <td>
                    <form action="{{ route('memberships.destroy', $member->membership->id) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button class="secondary" :aria-label="__('Remove :user from :membershipable', ['user' => $joiner->name, 'membershipable' => $membershipable->name])">
                            {{ __('Remove') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
