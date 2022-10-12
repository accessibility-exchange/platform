<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="@can('update', $project){{ localized_route('projects.manage', $project) }}@else{{ localized_route('projects.show', $project) }}@endcan">{{ $project->name }}</a>
            </li>
        </ol>
        <p class="h4">{{ __('Create a new engagement') }}</p>
        <h1 class="mt-0">
            {{ __('Engagement translations') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Please select the languages that your engagement information can be translated into by your organization.') }}
    </p>

    <x-hearth-alert :title="__('Translation')" x-show="true">
        <p>{{ __('Although it is not compulsory, we highly recommend that you include English, French, American Sign Language (ASL), and Langue des signes du Québec (LSQ) translations of your content.') }}
        </p>
    </x-hearth-alert>

    <h2>{{ __('Selected translations') }}</h2>

    <form class="stack" action="{{ localized_route('engagements.store-languages', $project) }}" method="post"
        novalidate>
        <x-translation-picker />

        <p class="repel" x-data>
            <a class="cta secondary" href="{{ localized_route('projects.manage', $project) }}">{{ __('Cancel') }}</a>
            <button>{{ __('Next') }}</button>
        </p>
        @csrf
    </form>
</x-app-layout>
