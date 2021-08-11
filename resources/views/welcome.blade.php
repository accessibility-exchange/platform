<x-app-layout>
    <x-slot name="header">
        <h1 itemprop="name">{{ config('app.name', 'Accessibility in Action') }}</h1>
    </x-slot>

    <p>{{ __('welcome.intro') }}</p>
    <p>{!! __('welcome.details_line_1', ['link' => '<a href="' . localized_route('register') . '">' . __('welcome.register_text') . '</a>']) !!}</p>
    <p>{!! __('welcome.details_line_2', ['link' => '<a href="' . localized_route('login') . '">' . __('welcome.sign_in_text') . '</a>']) !!}</p>
</x-app-layout>
