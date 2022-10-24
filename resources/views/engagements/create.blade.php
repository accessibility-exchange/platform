<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        </ol>
        <h1>
            {{ __('Create engagement') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('An engagement involves a group of people participating in one set way (for example, a focus group or a survey). An engagement like a focus group can have multiple meetings.') }}
    </p>

    <p>{{ __('For example: The engagement group could be a focus group for Deaf customers. There could be three times the focus group meets to discuss different topics.') }}
    </p>

    <form class="stack" action="{{ localized_route('engagements.store', $project) }}" method="post" novalidate>
        @csrf

        <x-hearth-input id="project_id" name="project_id" type="hidden" :value="$project->id" required />

        <x-translatable-input name="name" :label="__('What is the name of your engagement?') . ' ' . __('(required)')" />

        <fieldset class="field @error('who') field--error @enderror">
            <legend>{{ __('Who do you want to engage?') . ' ' . __('(required)') }}</legend>
            <div class="field">
                <x-hearth-radio-button id="who-individuals" name="who" value="individuals" :checked="old('who') === 'individuals'" />
                <x-hearth-label for="who-individuals">{!! Str::inlineMarkdown(__('**Individuals** with lived experience of being disabled or Deaf')) !!}</x-hearth-label>
            </div>
            <div class="field">
                <x-hearth-radio-button id="who-organization" name="who" value="organization" :checked="old('who') === 'organization'" />
                <x-hearth-label for="who-organization">{!! Str::inlineMarkdown(
                    __('**A community organization** who represents or supports the disability or Deaf community'),
                ) !!}</x-hearth-label>
            </div>
            <x-hearth-error for="who" />
        </fieldset>

        <div class="repel">
            <a class="cta secondary"
                href="{{ localized_route('engagements.show-language-selection', $project) }}">{{ __('Back') }}</a>
            <button>{{ __('Next') }}</button>
        </div>
    </form>
</x-app-layout>
