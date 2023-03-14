<x-notification :notification="$notification">
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="body">
        {{ $body }}
    </x-slot>
    @if ($individual)
        <x-slot name="contact">
            <x-notification.contact-individual :individual="$individual" />
        </x-slot>
    @endif
</x-notification>
