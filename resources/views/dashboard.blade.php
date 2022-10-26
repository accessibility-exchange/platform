<x-app-wide-layout>
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        @if ($teamInvitation)
            <x-invitation>
                <p class="flex items-center gap-4"><span
                        class="h-5 w-5 rounded-full bg-magenta-3"></span>{{ __('You have been invited to join :invitationable’s team.', ['invitationable' => $teamInvitationable->name]) }}
                </p>
                <div class="flex items-center gap-4">
                    <a class="cta secondary" href="{{ $teamAcceptUrl }}">{{ __('Accept') }}</a>
                    <form class="inline" action="{{ route('invitations.decline', $teamInvitation) }}" method="post">
                        @csrf
                        @method('delete')
                        <button class="secondary">{{ __('Decline') }}</button>
                    </form>
                </div>
            </x-invitation>
        @endif
        <p>
            @if ($memberable)
                {{ $memberable->name }} -
            @endif{{ $user->name }}
        </p>
        <h1 class="mt-0" itemprop="name">
            {{ __('My dashboard') }}
        </h1>
        @if ($user->individual)
            <p>
                <strong>{{ __('Roles:') }}</strong> {{ implode(', ', $user->individual->display_roles) }}
                <a class="cta secondary ml-2" href="{{ localized_route('individuals.show-role-edit') }}">
                    <x-heroicon-o-pencil class="mr-1 h-5 w-5" role="presentation" aria-hidden="true" />
                    {{ __('Edit roles') }}
                </a>
            </p>
        @endif

        @if ($user->organization)
            <p>
                <strong>{{ __('Roles:') }}</strong>
                {{ empty($user->organization->display_roles) ? __('None selected') : implode(', ', $user->organization->display_roles) }}
                <a class="cta secondary ml-2"
                    href="{{ localized_route('organizations.show-role-edit', $user->organization) }}">
                    <x-heroicon-o-pencil class="mr-1 h-5 w-5" role="presentation" aria-hidden="true" />
                    {{ __('Edit roles') }}
                </a>
            </p>
        @endif
    </x-slot>

    @if ($user->context === 'administrator')
        @include('dashboard.administrator')
    @elseif ($user->context === 'individual')
        @include('dashboard.individual')
    @elseif($user->context === 'organization')
        @include('dashboard.organization')
    @elseif ($user->context === 'regulated-organization')
        @include('dashboard.regulated-organization')
    @endif
</x-app-wide-layout>
