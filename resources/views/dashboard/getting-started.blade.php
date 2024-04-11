<div class="getting-started box">
    <div class="flex items-center gap-5">
        @svg('heroicon-o-clipboard-document-list', 'icon--2xl icon--green')
        <h2 class="mt-0">{{ __('Getting started') }}</h2>
    </div>
    <x-interpretation class="interpretation--center" name="{{ __('Getting started', [], 'en') }}"
        namespace="getting_started" />

    @if ($user->context === App\Enums\UserContext::Individual->value)
        <p>{{ __('Here are all the steps you have to do before you start signing up for engagements.') }}</p>

        <div class="stack">
            <div>
            </div>
            <div class="flex items-center gap-5 pt-4">
                @svg('heroicon-o-pencil', 'icon--xl')
                <h3 class="mt-0">{{ __('Current step') }}</h3>
            </div>
            <x-interpretation name="{{ __('Current step', [], 'en') }}" namespace="dashboard" />

            <div class="getting-started__current-task stack pb-4">
                @include('dashboard.partials.getting-started-individual')
            </div>

            <div class="stack pb-4">
                <div class="flex items-center gap-5 pt-4">
                    @svg('heroicon-o-arrow-right', 'icon--xl')
                    <h3 class="mt-0">{{ __('Next steps') }}</h3>
                </div>
                <x-interpretation name="{{ __('Next steps', [], 'en') }}" namespace="dashboard" />
                <ol class="getting-started__task-list stack" role="list">
                    @stack('next-steps')
                </ol>
            </div>

            @if (Auth::user()->checkStatus('approved'))
                <x-expander :summary="__('Completed steps')" level="3">
                    <x-interpretation name="{{ __('Completed steps', [], 'en') }}" namespace="dashboard" />
                    <ol class="getting-started__task-list stack" role="list">
                        @stack('completed-steps')
                    </ol>
                </x-expander>
            @endif
        </div>
    @else
        <ol class="getting-started__list counter stack" role="list">
            @if ($user->context === App\Enums\UserContext::Organization->value)
                @include('dashboard.partials.getting-started-organization')
            @elseif ($user->context === App\Enums\UserContext::RegulatedOrganization->value)
                @include('dashboard.partials.getting-started-regulated-organization')
            @endif
        </ol>
    @endif
</div>
