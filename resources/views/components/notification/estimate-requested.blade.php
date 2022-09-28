<x-notification :notification="$notification">
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="body">{{ $body }}</x-slot>
    <x-slot name="actions">
        <a class="cta secondary" href="{{ localized_route('projects.show', $project) }}">{{ __('Review project') }}</a>
    </x-slot>
</x-notification>
