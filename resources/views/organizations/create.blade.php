
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.create_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('organizations.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />
        <div class="field">
            <x-hearth-label for="name" :value="__('organization.label_name')" />
            <x-hearth-input id="name" type="text" name="name" required />
            </div>
        <div class="field">
            <x-hearth-label for="locality" :value="__('forms.label_locality')" />
            <x-hearth-input id="locality" type="text" name="locality" required />
        </div>
        <div class="field">
            <x-hearth-label for="region" :value="__('forms.label_region')" />
            <x-hearth-select id="region" name="region" required :options="$regions"/>
        </div>

        <x-hearth-button>{{ __('organization.action_create') }}</x-hearth-button>
    </form>
</x-app-layout>
