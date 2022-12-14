<x-app-layout page-width="wide">
    <x-slot name="title">
        @if ($organization->checkStatus('published'))
            {{ __('Edit your organization page') }}
        @else
            {{ __('Create your organization page') }}
        @endif
    </x-slot>
    <x-slot name="header">
        <div class="repel">
            <h1>
                {{ $organization->name }}
            </h1>
            @if ($organization->checkStatus('draft'))
                <span class="badge">{{ __('Draft mode') }}</span>
            @endif
        </div>
        @if ($organization->checkStatus('published'))
            <p>
                <a href="{{ localized_route('organizations.show', $organization) }}">{{ __('View page') }}</a>
            </p>
        @endif
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <x-translation-manager :model="$organization" />

    @if (request()->get('step'))
        @include('organizations.edit.' . request()->get('step'))
    @else
        @include('organizations.edit.1')
    @endif

</x-app-layout>
