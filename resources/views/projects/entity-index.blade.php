<x-app-layout>
    <x-slot name="title">{{ __('project.entity_index_title', ['entity' => $entity->name]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('project.entity_index_title', ['entity' => $entity->name]) }}
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
