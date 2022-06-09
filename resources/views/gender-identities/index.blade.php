<x-app-layout>
    <x-slot name="title">{{ __('Gender Identities') }}</x-slot>
    <x-slot name="header">
        <h1 id="gender-identities">
            {{ __('Gender Identities') }}
        </h1>
    </x-slot>

    <div role="region" aria-labelledby="gender-identities" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
            </tr>
            </thead>
            @forelse ($genderIdentities as $genderIdentity)
                <tr>
                    <td>{{ $genderIdentity->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>


