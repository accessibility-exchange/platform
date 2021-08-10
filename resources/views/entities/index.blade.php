<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('entity.index_title') }}
        </h1>
    </x-slot>

   <div class="flow">
    @forelse($entities as $entity)
    <article>
        <h2>
            <a href="{{ localized_route('entities.show', $entity) }}">{{ $entity->name }}</a>
        </h2>
        <p>{{ $entity->locality }}, {{ get_region_name($entity->region, ["CA"], locale()) }}</p>
    </article>
    @empty
    <p>{{ __('entity.none_found') }}</p>
    @endforelse
    </div>
</x-app-layout>
