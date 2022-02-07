
<x-app-layout>
    <x-slot name="title">{{ __('Create your page') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create your page') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    @if(request()->get('step'))
        @include('community-members.create.' . request()->get('step'))
    @else
        @include('community-members.create.0')
    @endif
</x-app-layout>
