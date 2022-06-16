
<x-app-wide-layout>
    <x-slot name="title">
        @if($organization->checkStatus('published'))
            {{ __('Edit your organization page') }}
        @else
            {{ __('Create your organization page') }}
        @endif
    </x-slot>
    <x-slot name="header">
        <h1>
            @if($organization->checkStatus('published'))
                {{ __('Edit your organization page') }}
            @else
                {{ __('Create your organization page') }}
            @endif
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <x-translation-manager :model="$organization" />

    @if(request()->get('step'))
        @include('organizations.edit.' . request()->get('step'))
    @else
        @include('organizations.edit.1')
    @endif

</x-app-wide-layout>
