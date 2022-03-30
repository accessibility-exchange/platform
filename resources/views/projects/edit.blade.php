
<x-app-wide-layout>
    <x-slot name="title">{{ $project->name }}</x-slot>
    <x-slot name="header">
        <h1>{{ $project->name }}</h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <div class="center">
        @if(request()->get('step'))
        @include('projects.edit.steps.' . request()->get('step'))
        @else
        @include('projects.edit.steps.1')
        @endif
    </div>

</x-app-wide-layout>
