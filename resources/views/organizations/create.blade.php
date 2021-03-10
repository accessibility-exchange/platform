
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.create_title') }}
        </h1>
    </x-slot>

    <form action="{{ localized_route('organizations.store') }}" method="POST" novalidate>
        @csrf
        <x-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />
        <div class="field">
            <x-label for="name" :value="__('organization.label_name')" />
            <x-input id="name" type="name" name="name" required />
            </div>
        <div class="field">
            <x-label for="locality" :value="__('forms.label_locality')" />
            <x-input id="locality" type="locality" name="locality" required />
        </div>
        <div class="field">
            <x-label for="region" :value="__('forms.label_region')" />
            <x-region-select required />
        </div>

        <x-button>{{ __('organization.action_create') }}</x-button>
    </form>
</x-app-layout>
