<div class="getting-started box">
    <div class="flex items-center gap-5">
        @svg('heroicon-o-clipboard-list', 'icon--2xl icon--green')
        <h2 class="mt-0">{{ __('Getting started') }}</h2>
    </div>

    <div class="stack">

    </div>
    {{--
        Notifications
        - Customize this website's accessibility (ind:all, com:all, fro:all)
        - Invite others to your organization (com:admin, fro:admin)
     --}}
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
