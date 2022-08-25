<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>
            <small>{{ __('Project dashboard') }}</small><br />
            {{ $project->name }}
        </h1>
    </x-slot>
    <div class="manage">
        <a href="{{ localized_route('engagements.show-language-selection', $project) }}" class="cta">Create an engagement</a>
    </div>

</x-app-wide-layout>
