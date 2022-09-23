<x-app-layout>
    <x-slot name="title">{{ __('Add new meeting') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.manage', $project) }}">{{ $project->name }}</a></li>
            <li><a href="{{ localized_route('engagements.manage', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <h1>
            {{ __('Add new meeting') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('meetings.store', $engagement) }}" method="post" novalidate>
        @csrf

        <x-translatable-input name="name" :label="__('What is the name of your engagement?')" />
</x-app-layout>
