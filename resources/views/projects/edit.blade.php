
<x-app-layout>
    <x-slot name="title">{{ __('Create new project') }}</x-slot>
    <x-slot name="header">
        <h1>
            @switch(request()->get('step'))
            @case(1)
            {{ __('Create new project') }}
            @break
            @default
            {{ __('Create new project') }}
        @endswitch
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    @if(request()->get('step'))
        @include('projects.edit.steps.' . request()->get('step'))
    @else
        @include('projects.edit.steps.1')
    @endif

</x-app-layout>
