<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.edit_user_role_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('organization-user.update', ['organization' => $organization, 'user' => $user]) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        @foreach($roles as $role => $label)
        <div class="field">
            <input type="radio" id="role-{{ $role }}" name="role" value="{{ $role }}" @if ($role === $user->getRoleFor($organization)) checked @endif />
            <label for="role-{{ $role }}">{{ $label }}</label>
        </div>
        @endforeach
        <x-button>{{ __('organization.action_update_user_role') }}</x-button>
    </form>
</x-app-layout>
