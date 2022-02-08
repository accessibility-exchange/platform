<x-app-layout>
    <x-slot name="header">
        <h1>
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
        </h1>
    </x-slot>

    <!-- Validation Errors -->
    <x-auth-validation-errors />

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
</x-app-layout>
