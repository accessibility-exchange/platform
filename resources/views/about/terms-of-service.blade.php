<x-app-wide-layout>
    <x-slot name="title">{{ __('Terms of Service') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('Home') }}</a></li>
        </ol>
        <h1>
            {{ __('Terms of Service') }}
        </h1>
    </x-slot>

    <div class="stack stack:xl -mb-8">
        <p>TODO.</p>
    </div>

</x-app-wide-layout>
