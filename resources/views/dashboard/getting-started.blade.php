<div class="getting-started box">
    <div class="flex items-center gap-5">
        @svg('heroicon-o-clipboard-document-list', 'icon--2xl icon--green')
        <h2 class="mt-0">{{ __('Getting started') }}</h2>
        <x-interpretation class="interpretation--center" name="{{ __('Getting started', [], 'en') }}"
            namespace="getting_started" />
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
