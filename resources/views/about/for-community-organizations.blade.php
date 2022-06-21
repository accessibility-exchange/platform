<x-app-wide-layout>
    <x-slot name="title">{{ __('How this works for Community Organizations') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
        </ol>
        <h1>
            <span class="weight:normal">{{ __('How this works for') }}</span><br />
            {{ __('Community Organizations') }}
        </h1>
    </x-slot>
</x-app-wide-layout>
