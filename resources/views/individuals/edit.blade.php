
<x-app-wide-layout>
    <x-slot name="title">{{ __('Edit your individual page') }}</x-slot>
    <x-slot name="header">
        <h1>
            @if($individual->checkStatus('published'))
            {{ __('Edit your individual page') }}
            @else
            {{ __('Create your individual page') }}
            @endif
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <x-translation-manager :model="$individual" />

    @if(request()->get('step'))
        @include('individuals.edit.' . request()->get('step'))
    @else
        @include('individuals.edit.1')
    @endif
</x-app-wide-layout>
