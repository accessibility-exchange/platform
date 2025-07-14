<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Welcome to the Accessibility Exchange') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Welcome to') }}<br />
            {{ __('The Accessibility Exchange') }}
        </h1>
        @switch($user->context)
            @case(App\Enums\UserContext::Individual->value)
                <x-interpretation name="{{ __('Welcome to The Accessibility Exchange', [], 'en') }}"
                    namespace="introduction-individual" />
            @break

            @case(App\Enums\UserContext::Organization->value)
                <x-interpretation name="{{ __('Welcome to The Accessibility Exchange', [], 'en') }}"
                    namespace="introduction-organization" />
            @break

            @case(App\Enums\UserContext::RegulatedOrganization->value)
                <x-interpretation name="{{ __('Welcome to The Accessibility Exchange', [], 'en') }}"
                    namespace="introduction-regulated_organization" />
            @break

            @case(App\Enums\UserContext::TrainingParticipant->value)
                <x-interpretation name="{{ __('Welcome to The Accessibility Exchange', [], 'en') }}"
                    namespace="introduction-training_participant" />
            @break

            @default
        @endswitch
    </x-slot>

    <h2>
        {{ __('Introduction video') }}
    </h2>

    <!-- Video -->
    @switch($user->context)
        @case(App\Enums\UserContext::Individual->value)
            <div class="frame">
                <x-video class="w-full" name="{{ __('Individual introduction', [], 'en') }}" />
            </div>
        @break

        @case(App\Enums\UserContext::Organization->value)
            <div class="frame">
                <x-video class="w-full" name="{{ __('Community organization introduction', [], 'en') }}" />
            </div>
        @break

        @case(App\Enums\UserContext::RegulatedOrganization->value)
            <div class="frame">
                <x-video class="w-full" name="{{ __('Federally regulated organization introduction', [], 'en') }}" />
            </div>
        @break

        @default
    @endswitch

    <div class="center repel">
        @empty($user->finished_introduction)
            <a class="cta secondary" href="{{ $skipTo }}">{{ __('Skip for now') }}</a>
        @endempty
        <form class="width:full" action="{{ localized_route('users.update-introduction-status') }}" method="post">
            @method('put')
            @csrf

            <input name="finished_introduction" type="hidden" value="1" />
            <button>{{ __('Continue') }}</button>
        </form>
    </div>
</x-app-layout>
