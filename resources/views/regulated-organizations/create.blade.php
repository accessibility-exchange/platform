
<x-app-layout>
    <x-slot name="title">{{ __('Create regulated organization') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Create regulated organization') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form class="stack" action="{{ localized_route('regulated-organizations.store') }}" method="POST" novalidate>
        @csrf
        <x-hearth-input id="user_id" type="hidden" name="user_id" :value="Auth::user()->id" required />
        <div class="field">
            <x-hearth-label for="name" :value="__('Regulated organization name')" />
            <x-hearth-input name="name" required />
        </div>

        <p class="repel">
            <a class="cta secondary">{{ __('Cancel') }}</a>
            <x-hearth-button>{{ __('Next') }}</x-hearth-button>
        </p>
    </form>
</x-app-layout>
