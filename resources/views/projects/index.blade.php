<x-app-layout>
    <x-slot name="title">{{ __('project.index_title') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('project.index_title') }}
        </h1>
    </x-slot>

   <div class="projects flow">
        @forelse($projects as $project)
        <x-project-card :project="$project" :level="2" />
        @empty
        <p>{{ __('project.none_found') }}</p>
        @endforelse
    </div>
</x-app-layout>
