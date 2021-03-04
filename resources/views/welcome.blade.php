<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ config('app.name', 'Accessibility in Action') }}</h1>
    </x-slot>

    <p>{{ __('welcome.intro') }}</p>
     <p>{!! __('welcome.details', ['link' => '<a href="https://accessibility-in-action.inclusivedesign.ca/" rel="external">' . __('welcome.codesign_site') . '</a>']) !!}</p>
</x-app-layout>
