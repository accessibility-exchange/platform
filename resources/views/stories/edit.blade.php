<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('story.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('stories.update', $story) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field @error('title') field--error @enderror">
            <x-hearth-label for="title" :value="__('story.label_title')" />
            <x-hearth-input id="title" type="text" name="title" :value="old('title', $story->title)" required />
            <x-hearth-error for="title" />
            </div>
        <div class="field @error('language') field--error @enderror">
            <x-hearth-label for="language" :value="__('story.label_language')" />
            <x-hearth-locale-select name="language" :selected="old('language', $story->language)" />
            <x-hearth-error for="language" />
        </div>
        <div class="field @error('summary') field--error @enderror">
            <x-hearth-label for="summary" :value="__('story.label_summary')" />
            <x-hearth-textarea name="summary" :value="old('summary', $story->summary)" required />
            <x-hearth-error for="summary" />
        </div>

        <x-hearth-button>{{ __('forms.save_changes') }}</x-hearth-button>
    </form>

    <h2>
        {{ __('story.delete_title') }}
    </h2>

    <p>{{ __('story.delete_intro') }}</p>

    <form action="{{ localized_route('stories.destroy', $story) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyResource') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input id="current_password" type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyResource" />
        </div>

        <x-hearth-button>
            {{ __('story.action_delete') }}
        </x-hearth-button>
    </form>
</x-app-layout>
