<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ localized_route('welcome') }}">
                {{ config('app.name', 'The Accessibility Exchange') }}
            </a>
        </x-slot>

        <x-slot name="title">
            {{ __('Create an account') }}
        </x-slot>

        <p>{{ __('To help create the right account for you, tell us why you’re here.') }}</p>

        <p><a href="{{ localized_route('register', ['context'=> 'community-member']) }}">{{ __('I am here to share my knowledge about disability') }} <x-heroicon-s-arrow-right aria-hidden="true" style="display: inline-block; margin-right: 0.25em; margin-bottom: -0.125em; width: 1em; height: 1em;" /></a></p>
        <p><a href="{{ localized_route('register', ['context'=> 'entity']) }}">{{ __('I am here to learn from the disability community') }} <x-heroicon-s-arrow-right aria-hidden="true" style="display: inline-block; margin-right: 0.25em; margin-bottom: -0.125em; width: 1em; height: 1em;" /></a></p>
    </x-auth-card>
</x-guest-layout>
