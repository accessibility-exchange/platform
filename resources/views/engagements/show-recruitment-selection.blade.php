<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        </ol>
        <p class="h4">{{ __('Create engagement') }}</p>
        <h1 class="mt-0">
            {{ __('How do you want to recruit participants?') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('engagements.store-recruitment', $engagement) }}" method="post"
        novalidate>
        @csrf
        @method('put')

        <fieldset class="field @error('recruitment') field--error @enderror">
            <legend>{{ __('Please select a recruitment method:') }}</legend>
            <x-hearth-radio-buttons name="recruitment" :options="$recruitments" :checked="old('recruitment', $engagement->recruitment)" />
            <x-hearth-error for="recruitment" />
        </fieldset>

        <button>{{ __('Next') }}</button>
    </form>
</x-app-layout>
