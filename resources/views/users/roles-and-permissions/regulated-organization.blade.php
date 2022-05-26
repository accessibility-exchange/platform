<h2>{{ __('Your members') }}</h2>

<div role="region" aria-label="{{ __('Your members') }}" tabindex="0">
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
        @foreach ($user->regulatedOrganization->users as $user)
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

<h2>{{ __('Requests to join') }}</h2>

@if(!$user->regulatedOrganization->requestsToJoin->isEmpty())
    <div role="region" aria-label="{{ __('Requests to join') }}" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email address') }}</th>
                <th></th>
            </tr>
            </thead>
            @foreach ($user->regulatedOrganization->requestsToJoin as $user)
                <tr>
                    <td id="request-{{ $user->id }}">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action="{{ localized_route('requests.deny', $user) }}" method="POST">
                            @csrf
                            <button class="link" aria-label="{{ __('Deny :name’s request to join :regulatedOrganization', ['name' => $user->name, 'regulatedOrganization' => $regulatedOrganization->name]) }}">
                                {{ __('Deny request') }}
                            </button>
                        </form>

                        <form action="{{ localized_route('requests.approve', $user) }}" method="POST">
                            @csrf
                            <button class="link" aria-label="{{ __('Approve :name’s request to join :regulatedOrganization', ['name' => $user->name, 'regulatedOrganization' => $regulatedOrganization->name]) }}">
                                {{ __('Approve request') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endif
