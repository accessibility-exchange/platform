
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('consultant-profile.create_title') }}
        </h1>
    </x-slot>

    <form action="{{ localized_route('consultant-profiles.store') }}" method="POST" novalidate>
        @csrf
        <x-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />
        <div class="field">
            <x-label for="name" :value="__('consultant-profile.label_name')" />
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

        <x-button>{{ __('consultant-profile.create_profile') }}</x-button>
    </form>
</x-app-layout>
