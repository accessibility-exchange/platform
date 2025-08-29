<div class="getting-started box" data-dismissable>
    <div class="flex items-center gap-5">
        @svg('heroicon-o-clipboard-document-list', 'icon--2xl icon--green')
        <h2 class="mt-0">{{ __('Getting started') }}</h2>
    </div>
    <div class="stack">
        @if ($user->context === App\Enums\UserContext::Individual->value)
            <x-interpretation class="interpretation--center" name="{{ __('Getting started', [], 'en') }}"
                namespace="getting_started-individual" />
            <p>{{ $user->individual->isParticipant() ? __('Here are all the steps you have to do before you start signing up for engagements.') : __('Here are all the steps you have to do before you start finding organizations to work with.') }}
            </p>

            @if ($user->hasTasksToComplete() && !$user->hasDismissedPrompts())

                <div class="flex items-center gap-5 pt-4">
                    @svg('heroicon-o-pencil', 'icon--xl')
                    <h3 class="mt-0">{{ __('Current step') }}</h3>
                </div>
                <x-interpretation name="{{ __('Current step', [], 'en') }}" namespace="getting_started" />

                <div class="getting-started__current-task stack pb-4">
                    @include('dashboard.partials.getting-started-individual')
                </div>

                <div class="stack pb-4">
                    <div class="flex items-center gap-5 pt-4">
                        @svg('heroicon-o-arrow-right', 'icon--xl')
                        <h3 class="mt-0">{{ __('Next steps') }}</h3>
                    </div>
                    <x-interpretation name="{{ __('Next steps', [], 'en') }}" namespace="getting_started" />
                    <ol class="getting-started__task-list stack" role="list">
                        @stack('next-steps')
                    </ol>
                </div>

                @if ($user->checkStatus('approved'))
                    <x-expander :summary="__('Completed steps')" level="3">
                        <x-interpretation name="{{ __('Completed steps', [], 'en') }}" namespace="getting_started" />
                        <ol class="getting-started__task-list stack" role="list">
                            @stack('completed-steps')
                        </ol>
                    </x-expander>
                @endif
            @else
                <p class="h3">{{ __('Congratulations, your role has been fully approved.') }}</p>
                @if ($user->individual->isConsultant() && $user->individual->isConnector())
                    <livewire:prompt :model="Auth::user()" prompt="dismissed_browse_organizations_prompt_at"
                        :heading="__('Find organizations to work with')" :interpretationName="__(
                            'Find organizations to work with as an Accessibility Consultant or Community Connector',
                            [],
                            'en'
                        )" interpretationNameSpace="getting_started" :description="__(
                            'As an Accessibility Consultant or Community Connector, you can find organizations that might require your services.'
                        )"
                        :actionLabel="__('Browse organizations')" :actionUrl="localized_route('regulated-organizations.index')" />
                @elseif($user->individual->isConsultant())
                    <livewire:prompt :model="Auth::user()" prompt="dismissed_browse_organizations_prompt_at"
                        :heading="__('Find organizations to work with')" :interpretationName="__('Find organizations to work with as an Accessibility Consultant', [], 'en')" interpretationNameSpace="getting_started" :description="__(
                            'As an Accessibility Consultant, you can find organizations that might require your services.'
                        )"
                        :actionLabel="__('Browse organizations')" :actionUrl="localized_route('regulated-organizations.index')" />
                @elseif($user->individual->isConnector())
                    <livewire:prompt :model="Auth::user()" prompt="dismissed_browse_organizations_prompt_at"
                        :heading="__('Find organizations to work with')" :interpretationName="__('Find organizations to work with as a Community Connector', [], 'en')" interpretationNameSpace="getting_started"
                        :description="__(
                            'As a Community Connector, you can find organizations that might require your services.'
                        )" :actionLabel="__('Browse organizations')" :actionUrl="localized_route('regulated-organizations.index')" />
                @elseif ($user->individual->isParticipant())
                    <livewire:prompt :model="Auth::user()" prompt="dismissed_browse_engagements_prompt_at"
                        :heading="__('Find engagements to join')" :interpretationName="__('Find engagements to join', [], 'en')" interpretationNameSpace="getting_started"
                        :description="__(
                            'As a participant, you can answer surveys, or join focus groups, workshops, and more.'
                        )" :actionLabel="__('Browse engagements')" :actionUrl="localized_route('engagements.index')" />
                @endif
            @endif
        @else
            @if ($user->hasTasksToComplete())
                <x-interpretation class="interpretation--center" name="{{ __('Getting started', [], 'en') }}"
                    namespace="getting_started" />

                <ol class="getting-started__list counter stack" role="list">
                    @if ($user->context === App\Enums\UserContext::Organization->value)
                        @include('dashboard.partials.getting-started-organization')
                    @elseif ($user->context === App\Enums\UserContext::RegulatedOrganization->value)
                        @include('dashboard.partials.getting-started-regulated-organization')
                    @endif
                </ol>
            @else
                <p class="h3">{{ __('Congratulations, your role has been fully approved.') }}</p>
            @endif
        @endif
    </div>
</div>
