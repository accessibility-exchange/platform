
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('profile.create_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('profiles.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />
        <div class="field">
            <x-hearth-label for="name" :value="__('profile.label_name')" />
            <x-hearth-input id="name" type="text" name="name" :value="Auth::user()->name" required />
            </div>
        <div class="field">
            <x-hearth-label for="locality" :value="__('forms.label_locality')" />
            <x-hearth-input id="locality" type="text" name="locality" required />
        </div>
        <div class="field">
            <x-hearth-label for="region" :value="__('forms.label_region')" />
            <x-hearth-select id="region" name="region" required :options="$regions"/>
        </div>

        <x-hearth-button>{{ __('profile.action_create') }}</x-hearth-button>
    </form>
</x-app-layout>
