
<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('organization.edit_title') }}
        </h1>
    </x-slot>

    <!-- Form Validation Errors -->
    @include('partials.validation-errors')

    <form action="{{ localized_route('organizations.update', $organization) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="field">
            <x-label for="name" :value="__('organization.label_name')" />
            <x-input id="name" type="name" name="name" :value="old('name', $organization->name)" required />
            </div>
        <div class="field">
            <x-label for="locality" :value="__('forms.label_locality')" />
            <x-input id="locality" type="locality" name="locality" :value="old('locality', $organization->locality)" required />
        </div>
        <div class="field">
            <x-label for="region" :value="__('forms.label_region')" />
            <x-region-select :selected="old('region', $organization->region)" required />
        </div>

        <x-button>{{ __('forms.save_changes') }}</x-button>
    </form>

    <h2 id="members">
        {{ __('organization.members_title') }}
    </h2>

    <div role="region" aria-labelledby="members" tabindex="0">
        <table>
            <thead>
                <tr>
                  <th>{{ __('organization.member_name') }}</th>
                  <th>{{ __('organization.member_status') }}</th>
                  <th>{{ __('organization.member_role') }}</th>
                </tr>
            </thead>
            @foreach ($organization->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ __('organization.member_active') }}</td>
                <td>
                    <form action="{{ localized_route('organization-user.update', $organization) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        <x-input name="userId" type="hidden" :value="$user->id" />
                        <x-select name="role" :aria-label="__('organization.member_role')" :options="['member' => __('roles.member'), 'admin' => __('roles.admin')]" :selected="old('role', $user->membership->role)" required />
                        <x-button>{{ __('Update Role') }}</x-button>
                    </form
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <h2>
        {{ __('organization.delete_title') }}
    </h2>

    <p>{{ __('organization.delete_intro') }}</p>

    <form action="{{ localized_route('organizations.destroy', $organization) }}" method="POST" novalidate>
        @csrf
        @method('DELETE')

        <div class="field">
            <x-label for="current_password" :value="__('auth.label_current_password')" />
            <x-input id="current_password" type="password" name="current_password" required />
            @error('current_password', 'destroyOrganization')
            <x-validation-error>{{ $message }}</x-validation-error>
            @enderror
        </div>

        <x-button>
            {{ __('organization.action_delete') }}
        </x-button>
    </form>
</x-app-layout>
