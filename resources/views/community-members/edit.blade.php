
<x-app-layout>
    <x-slot name="title">{{ __('Edit your community member page') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Edit your community member page') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('community-members.update', $communityMember) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <x-privacy-indicator level="public" :value="__('This information will be on your public page. It is visible to anyone with an account on this website.')" />

        @include('community-members.edit.1')

        <x-hearth-button>{{ __('Save changes') }}</x-hearth-button>
    </form>

    <h2>
        {{ __('Delete my community member page') }}
    </h2>

    <p>{{ __('Your community member page will be deleted and cannot be recovered. If you still want to delete your community member page, please enter your current password to proceed.') }}</p>

    <form action="{{ localized_route('community-members.destroy', $communityMember) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field @error('current_password', 'destroyCommunityMember') field--error @enderror">
            <x-hearth-label for="current_password" :value="__('hearth::auth.label_current_password')" />
            <x-hearth-input type="password" name="current_password" required />
            <x-hearth-error for="current_password" bag="destroyCommunityMembers" />
        </div>

        <x-hearth-button>
            {{ __('Delete my page') }}
        </x-hearth-button>
    </form>
</x-app-layout>
