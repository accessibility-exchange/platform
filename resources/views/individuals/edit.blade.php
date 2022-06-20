
<x-app-wide-layout>
    <x-slot name="title">{{ __('Edit your individual page') }}</x-slot>
    <x-slot name="header">
        <div class="repel">
            <h1>
                {{ $individual->name }}
            </h1>
            @if($individual->checkStatus('draft'))
                <span class="badge">{{ __('Draft mode') }}</span>
            @endif
        </div>
        @if($individual->checkStatus('published'))
            <p>
                <a href="{{ localized_route('individuals.show', $individual) }}">{{ __('View page') }}</a>
            </p>
        @endif
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
