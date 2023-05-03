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
        @if ($user->individual)
            <p>
                <strong>{{ __('Roles:') }}</strong> {{ implode(', ', $user->individual->display_roles) }}
                <a class="cta secondary ml-2" href="{{ localized_route('individuals.show-role-edit') }}">
                    @svg('heroicon-o-pencil', 'mr-1')
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
                    @svg('heroicon-o-pencil', 'mr-1')
                    {{ __('Edit roles') }}
                </a>
            </p>
        @endif
    </x-slot>

    {{--

        When to show Getting started:

        Individual (CP): No collaboration preferences entered (only payment is required) || not approved
        Individual (CC): Public page not publishable || not approved || public page not published
        Individual (AC): same as CC
        Community Org (CP): org admin && (Org page not publishable || not approved || org page not published)
        Community Org (CC): org admin && (Org page not publishable || not approved || org page not published)
        Community Org (AC): same as CC
        Community Org (CP, CC, AC): org member && not approved
        FRO: org admin && (Org page not publishable || not approved || org page not published || no projects created)
        FRO: org member && not approved
        Admin: Never
        Training: Never

     --}}

    @if (!$user->checkStatus('suspended') &&
        ($user->individual?->needsGettingStarted() ||
            $user->orgnaization?->needsGettingStarted() ||
            $user->regulated_organization?->needsGettingStarted()))
        @include('partials.getting-started')
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
