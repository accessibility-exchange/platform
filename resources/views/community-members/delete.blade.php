
<x-app-layout>
    <x-slot name="title">{{ __('Delete your community member page') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Delete your community member page') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <p>{{ __('Your community member page will be deleted and cannot be recovered. If you still want to delete your community member page, please enter your current password to proceed.') }}</p>

    <form action="{{ localized_route('community-members.destroy', $communityMember) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyCommunityMember') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-password-input name="current_password" />
            <x-hearth-error for="current_password" bag="destroyCommunityMembers" />
        </div>

        <x-hearth-button>
            {{ __('Delete my page') }}
        </x-hearth-button>
    </form>
</x-app-layout>
