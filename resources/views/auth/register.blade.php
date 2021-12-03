<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            @switch(request()->get('step'))
                @case(1)
                    {{ __('Your role') }}
                    @break
                @case(2)
                    {{ __('Your details') }}
                    @break
                @case(3)
                    {{ __('Choose a password') }}
                    @break
                @default
                {{ __('Create an account') }}
            @endswitch
        </x-slot>

        <!-- Validation Errors -->
        @if ($errors->any())
            <x-hearth-alert type="error">
                <p>{{ __('hearth::auth.error_intro') }}</p>
            </x-hearth-alert>
        @endif

        @if(request()->get('step'))
            @include('auth.register.steps.' . request()->get('step'))
        @else
            @include('auth.register.steps.0')
        @endif

        <p>
            {{ __('Already have an account?') }} <a href="{{ localized_route('login') }}">
                {{ __('Sign in.') }}
            </a>
        </p>
    </x-auth-card>
</x-guest-layout>
