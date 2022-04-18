
<x-app-wide-layout>
    <x-slot name="title">{{ __('Edit your community member page') }}</x-slot>
    <x-slot name="header">
        <h1>
            @if($communityMember->checkStatus('published'))
            {{ __('Edit your community member page') }}
            @else
            {{ __('Create your community member page') }}
            @endif
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    @if(request()->get('step'))
        @include('community-members.edit.' . request()->get('step'))
    @else
        @include('community-members.edit.1')
    @endif
</x-app-wide-layout>
