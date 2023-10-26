<x-app-layout>
    <x-slot name="title">{{ __('Create new project') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        </ol>
        <p class="h3">{{ __('Create a new project') }}</p>
        <x-interpretation name="{{ __('Create a new project', [], 'en') }}" />
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('projects.store') }}" method="post" novalidate>
        <fieldset class="stack">
            <legend class="h1">{{ __('Project name') }}</legend>
            <x-translatable-input name="name" :label="__('Project name') . ' ' . __('(required)')" :short-label="__('project name')" :model="new App\Models\Project()" />
        </fieldset>

        @if (session()->has('ancestor'))
            <x-interpretation name="{{ __('Back', [], 'en') . '_' . __('Create', [], 'en') }}"
                namespace="back_create" />
        @else
            <x-interpretation name="{{ __('Cancel', [], 'en') . '_' . __('Create', [], 'en') }}"
                namespace="cancel_create" />
        @endif
        <p class="repel">
            <a class="cta secondary"
                href="{{ localized_route('projects.show-language-selection') }}">{{ session()->has('ancestor') ? __('Back') : __('Cancel') }}</a>
            <button>{{ __('Create') }}</button>
        </p>

        <x-hearth-input name="projectable_id" type="hidden" :value="$projectable->id" />
        <x-hearth-input name="projectable_type" type="hidden" :value="get_class($projectable)" />

        <x-hearth-input name="ancestor_id" type="hidden" :value="session()->get('ancestor')" />
        @csrf
    </form>

</x-app-layout>
