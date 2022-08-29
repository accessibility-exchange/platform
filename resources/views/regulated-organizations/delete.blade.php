<x-app-layout>
    <x-slot name="title">{{ __('Delete regulated organization') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Delete regulated organization') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Your regulated organization, :name, will be deleted and cannot be recovered. If you still want to delete your regulated organization, please enter your current password to proceed.', ['name' => $regulatedOrganization->name]) }}
    </p>

    <form action="{{ localized_route('regulated-organizations.destroy', $regulatedOrganization) }}" method="POST"
        novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-password-input name="current_password" />
            <x-hearth-error for="current_password" bag="destroyOrganization" />
        </div>

        <button>
            {{ __('Delete regulated organization') }}
        </button>
    </form>
</x-app-layout>
