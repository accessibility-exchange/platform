<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $entity->name }}: {{ __('project.index_title') }}
        </h1>
    </x-slot>

   <div class="projects flow">
        @forelse($projects as $project)
        <x-project-card :project="$project" :level="2" :showEntity="false" />
        @empty
        <p>{{ __('project.none_found') }}</p>
        @endforelse
    </div>
</x-app-layout>
