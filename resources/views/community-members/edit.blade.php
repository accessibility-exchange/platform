
<x-app-layout>
    <x-slot name="title">{{ __('Edit your community member page') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit your community member page') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    @if(request()->get('step'))
        @include('community-members.edit.' . request()->get('step'))
    @else
        @include('community-members.edit.1')
    @endif
</x-app-layout>
