<x-app-layout page-width="wide">
    <x-slot name="title">{{ __('Edit your role') }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs mt-36" role="list">
            <li><a href="{{ localized_route('dashboard') }}">{{ __('Dashboard') }}</a></li>
        </ol>
        <h1 class="mt-6">
            {{ __('Edit role') }}
        </h1>

        <h2 class="mt-16">{{ __('Learn about these roles') }}</h2>
        @if (locale() === 'en')
            <div class="stack width:full" x-data="vimeoPlayer({
                url: @js('https://vimeo.com/850308866/22cf4718fc'),
                byline: false,
                dnt: true,
                pip: true,
                portrait: false,
                responsive: true,
                speed: true,
                title: false
            })" @ended="player().setCurrentTime(0)">
            </div>
        @elseif (locale() === 'fr')
            <div class="stack width:full" x-data="vimeoPlayer({
                url: @js('https://vimeo.com/850319076/4d973fc4ee'),
                byline: false,
                dnt: true,
                pip: true,
                portrait: false,
                responsive: true,
                speed: true,
                title: false
            })" @ended="player().setCurrentTime(0)">
            </div>
        @elseif (is_signed_language(locale()))
            <x-interpretation
                name="{{ __('How this works for individuals with disabilities, Deaf people, and supporters', [], 'en') }}" />
        @endif
    </x-slot>

    <h4>{{ __('Please tell us what you would like to do on this website.') }}</h4>

    <form class="stack mb-16" action="{{ localized_route('individuals.save-roles') }}" method="post" novalidate
        x-data="{ initialRoles: {{ json_encode($individual->roles) }}, roles: {{ json_encode(old('roles', $individual->roles ?? [])) }} }">
        <fieldset class="field @error('roles') field--error @enderror">
            <x-hearth-checkboxes name="roles" :options="$roles" :checked="old('roles', $individual->roles ?? [])" x-model="roles" />
            <x-hearth-error for="roles" />
        </fieldset>

        <div role="alert">
            <x-hearth-alert type="warning" x-cloak
                x-show="(initialRoles.includes('{{ App\Enums\IndividualRole::AccessibilityConsultant->value }}') || initialRoles.includes('{{ App\Enums\IndividualRole::CommunityConnector->value }}')) && !roles.includes('{{ App\Enums\IndividualRole::AccessibilityConsultant->value }}') && !roles.includes('{{ App\Enums\IndividualRole::CommunityConnector->value }}')">
                <x-interpretation
                    name="{{ __('By selecting Consultation Participant as your only role, your role no longer will include the Accessibility Consultant or Community Connector roles.', [], 'en') }}" />
                {{ __('By selecting Consultation Participant as your only role, your role no longer will include the Accessibility Consultant or Community Connector roles. You do not need a profile to be a Consultation Participant, so your profile will be unpublished and saved, and will no longer be visible by other members of The Accessibility Exchange. However, if you edit your role to add the Accessibility Consultant or Community Connector roles again, you will be able to publish your profile again all your saved information will be restored.') }}
            </x-hearth-alert>
        </div>

        <hr class="mb-14 mt-28">

        <p class="repel">
            <button>{{ __('Save') }}</button>
        </p>

        @method('put')
        @csrf
    </form>
</x-app-layout>
