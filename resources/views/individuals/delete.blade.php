<x-app-layout>
    <x-slot name="title">{{ __('Delete your individual page') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Delete your individual page') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Your individual page will be deleted and cannot be recovered. If you still want to delete your individual page, please enter your current password to proceed.') }}
    </p>

    <form action="{{ localized_route('individuals.destroy', $individual) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyIndividual') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-password-input name="current_password" />
            <x-hearth-error for="current_password" bag="destroyIndividuals" />
        </div>

        <button>
            {{ __('Delete my page') }}
        </button>
    </form>
</x-app-layout>
