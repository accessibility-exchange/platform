
<x-app-layout>
    <x-slot name="title">{{ __('Create new project') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        </ol>
        <p class="h3">{{ __('Create a new project') }}</p>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('projects.store') }}" method="post" novalidate>
        <fieldset class="stack">
            <legend class="h1">{{ __('Project name') }}</legend>
            <x-translatable-input name="name" :label="__('Project name')" :model="new App\Models\Project()" />
        </fieldset>

        <p class="repel" x-data>
            <a class="cta secondary" href="{{ localized_route('projects.show-language-selection') }}">{{ session()->has('ancestor') ? __('Back') : __('Cancel') }}</a>
            <button>{{ __('Create') }}</button>
        </p>

        <x-hearth-input type="hidden" name="projectable_id" :value="$projectable->id" />
        <x-hearth-input type="hidden" name="projectable_type" :value="get_class($projectable)" />

        <x-hearth-input type="hidden" name="ancestor_id" :value="session()->get('ancestor')" />
        @csrf
    </form>

</x-app-layout>
