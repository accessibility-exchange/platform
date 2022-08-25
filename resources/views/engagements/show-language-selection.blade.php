
<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a href="{{ localized_route('projects.show', $project) }}">{{ $project->name }}</a></li>
        </ol>
        <p class="h4">{{ __('Create a new engagement') }}</p>
        <h1 class="mt-0">
            {{ __('Page translations') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Please select the languages that your organization is able to translate your engagement details to.') }}</p>

    <x-hearth-alert :title="__('Recommendation')">
        <p>{{ __('Although it is not compulsory, we highly recommend that you add French, American Sign Language (ASL) and Langue des signes du Québec (LSQ) translations to your content. These languages are listed under the Accessible Canada Act.') }}</p>
    </x-hearth-alert>

    <h2>{{ __('Translations') }}</h2>

    <form class="stack" action="{{ localized_route('engagements.store-languages', $project) }}" method="post" novalidate>
        <x-translation-picker />

        <p class="repel" x-data>
            <a class="cta secondary" href="{{ localized_route('projects.manage', $project) }}">{{ __('Cancel') }}</a>
            <button>{{ __('Next') }}</button>
        </p>
        @csrf
    </form>
</x-app-layout>
