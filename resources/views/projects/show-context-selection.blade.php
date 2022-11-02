<x-app-layout>
    <x-slot name="title">{{ __('Create new project') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
        </ol>
        <h1>{{ __('Create new project') }}</h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <h2>{{ __('About your project') }}</h2>

    <form class="stack" id="create-project" action="{{ localized_route('projects.store-context') }}" method="post"
        novalidate x-data="{ context: '{{ old('context', session('context')) ?? '' }}' }">
        @csrf

        <fieldset class="field @error('context') field--error @enderror stack">
            <legend class="h3">
                {{ __('Please indicate if this is a new project or a progress report for an existing project.') }}
            </legend>
            <x-hearth-radio-buttons name="context" :options="Spatie\LaravelOptions\Options::forArray([
                'new' => __('A new project'),
                'follow-up' => __('A follow-up to a previous project (such as a progress report)'),
            ])->toArray()" :checked="old('context', session('context')) ?? ''" x-model="context" />
            <div class="field @error('ancestor') field--error @enderror stack" x-show="context == 'follow-up'" x-cloak>
                <x-hearth-label for="ancestor" :value="__('Please select the original project for which this is a follow-up:')" />
                <x-hearth-select name="ancestor" :options="$ancestors" :selected="old('ancestor_id', session('ancestor_id'))" />
                <x-hearth-error for="ancestor" />
            </div>
            <x-hearth-error for="context" />
        </fieldset>

        <p class="repel">
            <button class="secondary" type="button" x-on:click="history.back()">{{ __('Cancel') }}</button>
            <button>{{ __('Next') }}</button>
        </p>
    </form>
</x-app-layout>
