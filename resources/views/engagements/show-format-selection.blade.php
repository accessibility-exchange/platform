<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
            </li>
        </ol>
        <p class="h4">{{ __('Create engagement') }}</p>
        <h1 class="mt-0">
            {{ __('Format') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('engagements.store-format', $engagement) }}" method="post" novalidate>
        @csrf
        @method('put')

        <fieldset class="field @error('format') field--error @enderror">
            <legend>{{ __('What format would you like to use?') . ' ' . __('(required)') }}</legend>
            <x-hearth-radio-buttons name="format" :options="$formats" :checked="old('format', '')" />
            <x-hearth-error for="format" />
        </fieldset>

        <button>{{ __('Next') }}</button>
    </form>
</x-app-layout>
