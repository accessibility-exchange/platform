
<x-app-layout>
    <x-slot name="title">{{ __('Create a regulated entity') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create a regulated entity') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('entities.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />
        <div class="field">
            <x-hearth-label for="name" :value="__('Regulated entity name')" />
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

        <x-hearth-button>{{ __('Create regulated entity') }}</x-hearth-button>
    </form>
</x-app-layout>
