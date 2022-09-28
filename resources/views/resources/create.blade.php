<x-app-layout>
    <x-slot name="title">{{ __('resource.create_title') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('resource.create_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('resources.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" name="user_id" type="hidden" :value="Auth::user()->id" required />

        <div class="field @error('title') field--error @enderror">
            <x-hearth-label for="title" :value="__('resource.label_title')" />
            <x-hearth-input id="title" name="title" type="text" :value="old('title')" required />
            <x-hearth-error for="title" />
        </div>
        <div class="field @error('language') field--error @enderror">
            <x-hearth-label for="language" :value="__('resource.label_language')" />
            <x-hearth-locale-select name="language" :selected="old('language', config('app.locale'))" />
            <x-hearth-error for="language" />
        </div>
        <div class="field @error('summary') field--error @enderror">
            <x-hearth-label for="summary" :value="__('resource.label_summary')" />
            <x-hearth-textarea name="summary" :value="old('summary')" required />
            <x-hearth-error for="summary" />
        </div>

        <button>{{ __('resource.action_create') }}</button>
    </form>
</x-app-layout>
