<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('project.index_title') }}
        </h1>
    </x-slot>

   <div class="flow">
        @forelse($projects as $project)
        <article>
            <h2>
                <a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a>
            </h2>
            <p>{!! __('project.initiated_by', ['entity' => '<strong>' . $project->entity->name . '</strong>']) !!}</p>
        </article>
        @empty
        <p>{{ __('project.none_found') }}</p>
        @endforelse
    </div>
</x-app-layout>
