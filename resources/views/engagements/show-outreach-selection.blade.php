
<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        </ol>
        <p class="h4">{{ __('Create engagement') }}</p>
        <h1 class="mt-0">
            {{ __('Who do you want to engage?') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('engagements.store-outreach', $engagement) }}" method="post" novalidate>
        @csrf
        @method('put')

        <fieldset class="field @error('who') field--error @enderror">
            <legend class="sr-only">{{ __('Who do you want to engage?') }}</legend>
            <div class="field">
                <x-hearth-radio-button name="who" id="who-individuals" value="individuals" :checked="old('who', $engagement->who) === 'individuals'" /> <x-hearth-label for="who-individuals">{!! Str::inlineMarkdown(__('**Individuals** with lived experience of being disabled or Deaf')) !!}</x-hearth-label>
            </div>
            <div class="field">
                <x-hearth-radio-button name="who" id="who-organization" value="organization" :checked="old('who', $engagement->who) === 'organization'" /> <x-hearth-label for="who-organization">{!! Str::inlineMarkdown(__('**A community organization** who represents or supports the disability or Deaf community')) !!}</x-hearth-label>
            </div>
            <x-hearth-error for="who" />
        </fieldset>

        <button>{{ __('Next') }}</button>
    </form>
</x-app-layout>
