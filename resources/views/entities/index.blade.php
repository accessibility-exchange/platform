<x-app-wide-layout>
    <x-slot name="title">{{ __('Regulated entities') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Regulated entities') }}
        </h1>
    </x-slot>

    <div class="grid">
        @forelse($entities as $entity)
        <x-card class="federally-regulated-organization">
            <x-slot name="title">
                <a href="{{ localized_route('entities.show', $entity) }}">{{ $entity->name }}</a>
            </x-slot>
            <p>
                <strong>{{ __('Federally regulated organization') }}</strong><br />
                <strong class="weight:semibold">{{__('Sector') }}:</strong> @foreach($entity->sectors as $sector){{ $sector->name }}@if(!$loop->last), @endif @endforeach
            </p>
        </x-card>
        @empty
        <p>{{ __('No regulated entities found.') }}</p>
        @endforelse
    </div>
</x-app-wide-layout>
