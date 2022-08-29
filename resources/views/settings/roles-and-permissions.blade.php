<x-app-wide-layout>
    <x-slot name="title">{{ __('Roles and permissions') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ localized_route('settings.show') }}">{{ __('Settings') }}</a></li>
        </ol>
        <h1>
            {{ __('Roles and permissions') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>
        <a class="cta"
            href="{{ localized_route('settings.invite-to-invitationable') }}">{{ __('Invite new member') }}</a>
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
                            <button class="secondary"
                                :aria-label="__('Cancel invitation for :email', ['email' => $invitation - > email])">
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
                        <a class="cta secondary"
                            href="{{ localized_route('memberships.edit', $member->membership->id) }}"
                            aria-label="{{ __('Edit :user’s role', ['user' => $member->name]) }}">{{ __('Edit') }}</a>
                    </td>
                    <td>
                        <form action="{{ route('memberships.destroy', $member->membership->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="secondary"
                                aria-label="{{ $member->id === $user->id ? __('Leave :membershipable', ['membershipable' => $membershipable->name]) : __('Remove :user from :membershipable', ['user' => $member->name, 'membershipable' => $membershipable->name]) }}">
                                {{ $member->id === $user->id ? __('Leave organization') : __('Remove') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

</x-app-wide-layout>
