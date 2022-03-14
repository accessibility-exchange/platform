<x-app-layout>
    <x-slot name="title">{{ __('Regulated entities') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Regulated entities') }}
        </h1>
    </x-slot>

   <div class="stack">
    @forelse($entities as $entity)
    <article>
        <h2>
            <a href="{{ localized_route('entities.show', $entity) }}">{{ $entity->name }}</a>
        </h2>
        <p>{{ $entity->locality }}, {{ get_region_name($entity->region, ["CA"], locale()) }}</p>
    </article>
    @empty
    <p>{{ __('No regulated entities found.') }}</p>
    @endforelse
    </div>
</x-app-layout>
