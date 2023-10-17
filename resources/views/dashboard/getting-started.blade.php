<div class="getting-started box">
    <div class="flex items-center gap-5">
        @svg('heroicon-o-clipboard-document-list', 'icon--2xl icon--green')
        <h2 class="mt-0">{{ __('Getting started') }}</h2>
    </div>

    <div class="stack">
        @unless (Auth::user()->checkStatus('dismissedCustomizationPrompt'))
            <livewire:prompt :model="Auth::user()" modelPath="dismissed_customize_prompt_at"
                heading="{{ __('Customize this website’s accessibility') }}"
                description="{{ __('Change colour contrast and turn on text to speech.') }}"
                actionLabel="{{ __('Customize') }}"
                actionUrl="{{ localized_route('settings.edit-website-accessibility-preferences') }}" />
        @endunless

        @if (Auth::user()->organization && !Auth::user()->organization->checkStatus('dismissedInvitePrompt'))
            <livewire:prompt :model="Auth::user()->organization" modelPath="dismissed_invite_prompt_at"
                heading="{{ __('Invite others to your organization') }}"
                description="{{ __('Please invite others so you can work on projects together.') }}"
                actionLabel="{{ __('Invite') }}"
                actionUrl="{{ localized_route('settings.invite-to-invitationable') }}" />
        @endif

        @if (Auth::user()->regulatedOrganization && !Auth::user()->regulatedOrganization->checkStatus('dismissedInvitePrompt'))
            <livewire:prompt :model="Auth::user()->regulatedOrganization" modelPath="dismissed_invite_prompt_at"
                heading="{{ __('Invite others to your organization') }}"
                description="{{ __('Please invite others so you can work on projects together.') }}"
                actionLabel="{{ __('Invite') }}"
                actionUrl="{{ localized_route('settings.invite-to-invitationable') }}" />
        @endif
    </div>

    <ol class="getting-started__list counter stack" role="list">
        @if ($user->context === App\Enums\UserContext::Individual->value)
            @include('dashboard.partials.getting-started-individual')
        @elseif ($user->context === App\Enums\UserContext::Organization->value)
            @include('dashboard.partials.getting-started-organization')
        @elseif ($user->context === App\Enums\UserContext::RegulatedOrganization->value)
            @include('dashboard.partials.getting-started-regulated-organization')
        @endif
    </ol>
</div>
