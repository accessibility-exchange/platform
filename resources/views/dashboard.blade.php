<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('My dashboard') }}</x-slot>
    <x-slot name="header">
        @if ($teamInvitation)
            <x-invitation>
                <p>{{ __('You have been invited to join :invitationable’s team.', ['invitationable' => $teamInvitationable->name]) }}
                </p>
                <div class="actions flex gap-3">
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
        @if (!empty($user->introduction()))
            <a class="with-icon" href="{{ localized_route('users.show-introduction') }}">
                @svg('heroicon-o-play')
                {{ __('Watch introduction video again') }}
            </a>
        @endif
        @if ($user->isAdministrator())
            <x-interpretation name="{{ __('My dashboard', [], 'en') }}" namespace="dashboard-administrator" />
        @endif
        @if ($user->individual)
            <x-interpretation name="{{ __('My dashboard', [], 'en') }}" namespace="dashboard-individual" />
            <p>
                <strong>{{ __('Roles:') }}</strong> {{ implode(', ', $user->individual->display_roles) }}
                <a class="cta secondary ml-2" href="{{ localized_route('individuals.show-role-edit') }}">
                    @svg('heroicon-o-pencil', 'mr-1')
                    {{ __('Edit roles') }}
                </a>
            </p>
        @endif

        @if ($user->regulatedOrganization)
            <x-interpretation name="{{ __('My dashboard', [], 'en') }}" namespace="dashboard-regulated_organization" />
        @endif

        @if ($user->organization)
            <x-interpretation name="{{ __('My dashboard', [], 'en') }}" namespace="dashboard-organization" />
            <p>
                <strong>{{ __('Roles:') }}</strong>
                {{ empty($user->organization->display_roles) ? __('None selected') : implode(', ', $user->organization->display_roles) }}
                <a class="cta secondary ml-2"
                    href="{{ localized_route('organizations.show-role-edit', $user->organization) }}">
                    @svg('heroicon-o-pencil', 'mr-1')
                    {{ __('Edit roles') }}
                </a>
            </p>
        @endif
    </x-slot>

    @if ($user->hasTasksToComplete())
        @include('dashboard.getting-started')
    @endif

    @if ($user->context === \App\Enums\UserContext::Administrator->value)
        @include('dashboard.administrator')
    @elseif ($user->context === \App\Enums\UserContext::Individual->value)
        @include('dashboard.individual')
    @elseif($user->context === \App\Enums\UserContext::Organization->value)
        @include('dashboard.organization')
    @elseif ($user->context === \App\Enums\UserContext::RegulatedOrganization->value)
        @include('dashboard.regulated-organization')
    @elseif ($user->context === \App\Enums\UserContext::TrainingParticipant->value)
        @include('dashboard.training-participant')
    @endif
</x-app-layout>
