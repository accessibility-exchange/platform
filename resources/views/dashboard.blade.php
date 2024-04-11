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
        @if ($user->individual && !empty($user->individual->roles))
            <a class="with-icon mr-4" href="{{ localized_route('individuals.show-role-edit') }}">
                @svg('heroicon-o-pencil', 'mr-1')
                {{ __('Edit roles') }}
            </a>
        @endif
        @if ($user->organization && !empty($user->organization->roles))
            <a class="with-icon mr-4"
                href="{{ localized_route('organizations.show-role-edit', $user->organization) }}">
                @svg('heroicon-o-pencil', 'mr-1')
                {{ __('Edit roles') }}
            </a>
        @endif
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
        @endif

        @if ($user->regulatedOrganization)
            <x-interpretation name="{{ __('My dashboard', [], 'en') }}" namespace="dashboard-regulated_organization" />
        @endif

        @if ($user->organization)
            <x-interpretation name="{{ __('My dashboard', [], 'en') }}" namespace="dashboard-organization" />
        @endif
    </x-slot>

    <div class="stack">
        @unless (Auth::user()->checkStatus('dismissedCustomizationPrompt'))
            <livewire:prompt :model="Auth::user()" modelPath="dismissed_customize_prompt_at" :heading="__('Customize this website’s accessibility')" :interpretationName="__('Customize this website’s accessibility', [], 'en')"
                interpretationNameSpace="getting_started" :description="__('Change colour contrast and turn on text to speech.')" :actionLabel="__('Customize')" :actionUrl="localized_route('settings.edit-website-accessibility-preferences')" />
        @endunless

        @if (Auth::user()->organization && !Auth::user()->organization->checkStatus('dismissedInvitePrompt'))
            <livewire:prompt :model="Auth::user()->organization" modelPath="dismissed_invite_prompt_at" :heading="__('Invite others to your organization')"
                :interpretationName="__('Invite others to your organization', [], 'en')" interpretationNameSpace="getting_started-invite_to_community_org" :description="__('Please invite others so you can work on projects together.')"
                :actionLabel="__('Invite')" :actionUrl="localized_route('settings.invite-to-invitationable')" />
        @endif

        @if (Auth::user()->regulatedOrganization && !Auth::user()->regulatedOrganization->checkStatus('dismissedInvitePrompt'))
            <livewire:prompt :model="Auth::user()->regulatedOrganization" modelPath="dismissed_invite_prompt_at" :heading="__('Invite others to your organization')"
                :interpretationName="__('Invite others to your organization', [], 'en')" interpretationNameSpace="getting_started-invite_to_regulated_org" :description="__('Please invite others so you can work on projects together.')"
                :actionLabel="__('Invite')" :actionUrl="localized_route('settings.invite-to-invitationable')" />
        @endif
    </div>

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
