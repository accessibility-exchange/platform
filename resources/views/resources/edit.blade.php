<x-app-layout>
    <x-slot name="title">{{ __('resource.edit_title', ['title' => $resource->title]) }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('resource.edit_title', ['title' => $resource->title]) }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('resources.update', $resource) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field @error('title') field--error @enderror">
            <x-hearth-label for="title" :value="__('resource.label_title')" />
            <x-hearth-input id="title" name="title" type="text" :value="old('title', $resource->title)" required />
            <x-hearth-error for="title" />
        </div>
        <div class="field @error('language') field--error @enderror">
            <x-hearth-label for="language" :value="__('resource.label_language')" />
            <x-hearth-locale-select name="language" :selected="old('language', $resource->language)" />
            <x-hearth-error for="language" />
        </div>
        <div class="field @error('summary') field--error @enderror">
            <x-hearth-label for="summary" :value="__('resource.label_summary')" />
            <x-hearth-textarea name="summary" :value="old('summary', $resource->summary)" required />
            <x-hearth-error for="summary" />
        </div>

        <button>{{ __('Save changes') }}</button>
    </form>

    <h2>
        {{ __('resource.delete_title') }}
    </h2>

    <p>{{ __('resource.delete_intro') }}</p>

    <form action="{{ localized_route('resources.destroy', $resource) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyResource') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-password-input name="current_password" />
            <x-hearth-error for="current_password" bag="destroyResource" />
        </div>

        <button>
            {{ __('resource.action_delete') }}
        </button>
    </form>
</x-app-layout>
