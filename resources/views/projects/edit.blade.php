<x-app-wide-layout>
    <x-slot name="title">
        @if($project->checkStatus('published'))
            {{ __('Edit your project page') }}
        @else
            {{ __('Create your project page') }}
        @endif
    </x-slot>
    <x-slot name="header">
        <div class="repel">
            <h1>
                {{ $project->name ?? __('New project') }}
            </h1>
            @if($project->checkStatus('draft'))
                <span class="badge">{{ __('Draft mode') }}</span>
            @endif
        </div>
        @if($project->checkStatus('published'))
            <p>
                <a href="{{ localized_route('projects.show', $project) }}">{{ __('View page') }}</a>
            </p>
        @endif
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <x-translation-manager :model="$project" />

    @if(request()->get('step'))
        @include('projects.edit.' . request()->get('step'))
    @else
        @include('projects.edit.1')
    @endif
</x-app-wide-layout>
