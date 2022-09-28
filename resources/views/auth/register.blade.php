<x-app-layout>
    <x-slot name="header">
        @if (request()->get('step') > 1)
            <p class="h4">{{ __('Please create an account to join The Accessibility Exchange.') }}</p>
        @endif
        <h1>
            @switch(request()->get('step'))
                @case(1)
                    {{ __('Create an account') }}
                @break

                @case(2)
                    {{ __('Who you’re joining as') }}
                @break

                @case(3)
                    {{ __('Your details') }}
                @break

                @case(4)
                    {{ __('Choose a password') }}
                @break

                @default
                    {{ __('Create an account') }}
            @endswitch
        </h1>
    </x-slot>

    <!-- Validation Errors -->
    <x-auth-validation-errors />
    @if (request()->get('step'))
        @include('auth.register.steps.' . request()->get('step'))
    @else
        @include('auth.register.steps.1')
    @endif

    <p>
        {!! __('If you already have an account, please :sign_in', [
            'sign_in' => '<a href="' . localized_route('login') . '">' . __('sign in.') . '</a>',
        ]) !!}
    </p>
</x-app-layout>
