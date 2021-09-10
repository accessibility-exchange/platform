<x-app-layout>
    <x-slot name="title">{{ __('story.create_title') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('story.create_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('stories.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />

        <div class="field @error('title') field--error @enderror">
            <x-hearth-label for="title" :value="__('story.label_title')" />
            <x-hearth-input id="title" type="text" name="title" :value="old('title')" required />
            <x-hearth-error for="title" />
            </div>
        <div class="field @error('language') field--error @enderror">
            <x-hearth-label for="language" :value="__('story.label_language')" />
            <x-hearth-locale-select name="language" :selected="old('language', config('app.locale'))" />
            <x-hearth-error for="language" />
        </div>
        <div class="field @error('summary') field--error @enderror">
            <x-hearth-label for="summary" :value="__('story.label_summary')" />
            <x-hearth-textarea name="summary" :value="old('summary')" required />
            <x-hearth-error for="summary" />
        </div>

        <x-hearth-button>{{ __('story.action_create') }}</x-hearth-button>
    </form>
</x-app-layout>
