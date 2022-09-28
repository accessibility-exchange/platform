<x-app-wide-layout>
    <x-slot name="title">{{ __('Edit your individual page') }}</x-slot>
    <x-slot name="header">
        <div class="repel">
            <h1>
                {{ $individual->name }}
            </h1>
            @if ($individual->checkStatus('draft'))
                <span class="badge">{{ __('Draft mode') }}</span>
            @endif
        </div>
        @if ($individual->checkStatus('published'))
            <p>
                @if (request()->get('step'))
                    <a
                        href="{{ localized_route($individual->steps()[request()->get('step')]['show'], $individual) }}">{{ __('View page') }}</a>
                @else
                    <a href="{{ localized_route('individuals.show', $individual) }}">{{ __('View page') }}</a>
                @endif
            </p>
        @endif
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <x-translation-manager :model="$individual" />

    @if (request()->get('step'))
        @include('individuals.edit.' . $individual->steps()[request()->get('step')]['edit'])
    @else
        @include('individuals.edit.about-you')
    @endif
</x-app-wide-layout>
