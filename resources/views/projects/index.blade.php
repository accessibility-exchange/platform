<x-app-layout>
    <x-slot name="title">{{ __('Projects') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Projects') }}
        </h1>
    </x-slot>

    <div class="projects stack">
        @forelse($projects as $project)
            <x-card.project :model="$project" :level="2" />
        @empty
            <p>{{ __('No projects found.') }}</p>
        @endforelse
    </div>
</x-app-layout>
