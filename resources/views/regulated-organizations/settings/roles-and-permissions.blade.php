<p>
    <a class="cta" href="{{ localized_route('users.invite-to-invitationable') }}">{{ __('Invite new member') }}</a>
</p>

<h2 id="requests-to-join">{{ __('Requests to join') }}</h2>

<div role="region" aria-labelledby="requests-to-join" tabindex="0">
    <table>
        <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email address') }}</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        @forelse ($regulatedOrganization->requestsToJoin as $joiner)
            <tr>
                <td id="request-{{ $joiner->id }}">{{ $joiner->name }}</td>
                <td>{{ $joiner->email }}</td>
                <td>
                    <div x-data="modal(@error('role') true @enderror)">
                        <button class="secondary" @click="showModal" aria-label="{{ __('Approve :name’s request to join :regulatedOrganization', ['name' => $joiner->name, 'regulatedOrganization' => $regulatedOrganization->name]) }}">
                            {{ __('Approve') }}
                        </button>
                        <template x-teleport="body">
                            <div class="modal-wrapper" x-show="showingModal">
                                <form class="modal stack" action="{{ localized_route('requests.approve', $joiner) }}" method="POST" @click.outside="hideModal">
                                    <h3>{{ __('Approve :user’s request', ['user' => $joiner->name]) }}</h3>

                                    <p>{{ __('Please confirm the following details about :user:', ['user' => $joiner->name]) }}</p>

                                    <div class="field">
                                        <x-hearth-label for="role" :value="__('Role (required)')" />
                                        <x-hearth-radio-buttons name="role" :options="$roles" :checked="old('role', 'member')" />
                                    </div>

                                    <p class="repel">
                                        <button class="secondary" type="button" @click="hideModal">{{ __('Cancel') }}</button>
                                        <button>{{ __('Approve request') }}</button>
                                    </p>
                                    @csrf
                                </form>
                            </div>
                        </template>
                    </div>
                </td>
                <td>
                    <div x-data="modal()">
                        <button class="secondary" @click="showModal" aria-label="{{ __('Deny :name’s request to join :regulatedOrganization', ['name' => $joiner->name, 'regulatedOrganization' => $regulatedOrganization->name]) }}">
                            {{ __('Deny') }}
                        </button>
                        <template x-teleport="body">
                            <div class="modal-wrapper" x-show="showingModal" >
                                <form class="modal stack" action="{{ localized_route('requests.deny', $joiner) }}" method="POST" @click.outside="hideModal">
                                    <h3>{{ __('Deny :user’s request', ['user' => $joiner->name]) }}</h3>

                                    <p>{{ __('Are you sure you want to deny :user’s request to join :regulatedOrganization? You cannot undo this.', ['user' => $joiner->name, 'regulatedOrganization' => $regulatedOrganization->name]) }}</p>

                                    <p class="repel">
                                        <button type="button" @click="hideModal">{{ __('Cancel') }}</button>
                                        <button class="secondary">{{ __('Deny request') }}</button>
                                    </p>
                                    @csrf
                                </form>
                            </div>
                        </template>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td>{{ __('None found.') }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforelse
    </table>
</div>

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
        @forelse ($regulatedOrganization->invitations as $invitation)
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
        @foreach ($regulatedOrganization->users as $member)
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
                        <button class="secondary" :aria-label="__('Remove :user from :regulatedOrganization', ['user' => $joiner->name, 'regulatedOrganization' => $regulatedOrganization->name])">
                            {{ __('Remove') }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
