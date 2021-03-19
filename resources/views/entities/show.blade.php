<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $entity->name }}
        </h1>
    </x-slot>

    <p>{{ $entity->locality }}, {{ __('regions.' . $entity->region) }}</p>

    @can('update', $entity)
    <p><a href="{{ localized_route('entities.edit', $entity) }}">{{ __('entity.edit_entity') }}</a></p>
    @endcan
</x-app-layout>
