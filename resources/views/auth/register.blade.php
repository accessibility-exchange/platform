<x-app-layout>
    <x-slot name="header">
        @if (request()->get('step') > 1)
            <p class="h4">{{ __('Please create an account to join The Accessibility Exchange.') }}</p>
            <x-interpretation
                name="{{ __('Please create an account to join The Accessibility Exchange.', [], 'en') }}" />
        @endif
        @switch(request()->get('step'))
            @case(1)
                <h1>
                    {{ __('Create an account') }}
                </h1>
                <x-interpretation name="{{ __('Create an account', [], 'en') }}" />
            @break

            @case(2)
                <h1>
                    {{ __('Who you’re joining as') }}
                </h1>
                <x-interpretation name="{{ __('Who you’re joining as', [], 'en') }}" />
            @break

            @case(3)
                <h1>
                    {{ __('Your details') }}
                </h1>
                <x-interpretation name="{{ __('Your details', [], 'en') }}" />
            @break

            @case(4)
                <h1>
                    {{ __('Choose a password') }}
                </h1>
                <x-interpretation name="{{ __('Choose a password', [], 'en') }}" />
            @break

            @default
                <h1>
                    {{ __('Create an account') }}
                </h1>
                <x-interpretation name="{{ __('Create an account', [], 'en') }}" />
        @endswitch
    </x-slot>

    <!-- Validation Errors -->
    <x-auth-validation-errors />
    @if (request()->get('step'))
        @include('auth.register.steps.' . request()->get('step'))
    @else
        @include('auth.register.steps.1')
    @endif

    {{ safe_markdown('If you already have an account, please [sign in](:url).', ['url' => localized_route('login')]) }}

</x-app-layout>
