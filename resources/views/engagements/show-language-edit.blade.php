<x-app-layout>
    <x-slot name="title">{{ __('Create engagement') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('projects.my-projects') }}">{{ __('My projects') }}</a></li>
            <li><a
                    href="{{ localized_route('projects.show', $engagement->project) }}">{{ $engagement->project->name }}</a>
            </li>
            <li><a href="{{ localized_route('engagements.show', $engagement) }}">{{ $engagement->name }}</a></li>
        </ol>
        <p class="h4">{{ $engagement->name }}</p>
        <h1 class="mt-0">
            {{ __('Page translations') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Please select the languages that your engagement information can be translated into by your organization.') }}
    </p>

    <x-hearth-alert :title="__('Recommendation')" x-show="true">
        <p>{{ __('Although it is not compulsory, we highly recommend that you include English, French, American Sign Language (ASL), and Langue des signes du Québec (LSQ) translations of your content.') }}
        </p>
    </x-hearth-alert>

    <h2>{{ __('Translations') }}</h2>

    <form class="stack" action="{{ localized_route('engagements.update-languages', $engagement) }}" method="post"
        novalidate>
        <x-translation-picker :languages="$engagement->languages ?? null" />

        <button>{{ __('Save changes') }}</button>
        @csrf
        @method('put')
    </form>
</x-app-layout>
