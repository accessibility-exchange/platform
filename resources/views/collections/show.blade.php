<x-app-wide-layout>
    <x-slot name="title">{{ $collection->title }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ $collection->title }}
        </h1>
    </x-slot>

    <x-markdown>{{ $collection->description }}</x-markdown>
</x-app-wide-layout>
