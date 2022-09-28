<x-app-layout>
    <x-slot name="title">{{ __('Create regulated organization') }}</x-slot>
    <x-slot name="header">
        @switch(request()->get('step'))
            @case(2)
                <p class="h2">{{ __('Create regulated organization') }}</p>
                <h1>
                    {{ __('Languages available') }}
                </h1>
            @break

            @default
                <h1>
                    {{ __('Create regulated organization') }}
                </h1>
        @endswitch

    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    @if (request()->get('step'))
        @include('regulated-organizations.create.steps.' . request()->get('step'))
    @else
        @include('regulated-organizations.create.steps.1')
    @endif
</x-app-layout>
