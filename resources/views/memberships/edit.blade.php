<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('membership.edit_user_role_title', ['user' => $user->name]) }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('membership.edit_user_role_intro', ['user' => $user->name, 'membershipable' => $membershipable->name]) }}</p>

    <form action="{{ localized_route('memberships.update', $membership) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <fieldset @error('membership') class="field--error" @enderror>
            <legend>{{ __('organization.label_user_role') }}</legend>
            <x-hearth-radio-buttons name="role" :options="$roles" :checked="old('role', $membership->role)" />
            <x-hearth-error for="role" field="membership" />
        </fieldset>
        <a class="button" href="{{ localized_route($membershipable->getRoutePrefix() . '.edit', $membershipable) }}">{{ __('organization.action_cancel_user_role_update') }}</a>
        <button>{{ __('organization.action_update_user_role') }}</button>
    </form>
</x-app-layout>
