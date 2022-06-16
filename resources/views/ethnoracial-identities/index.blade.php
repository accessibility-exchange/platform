<x-app-layout>
    <x-slot name="title">{{ __('Ethnoracial Identities') }}</x-slot>
    <x-slot name="header">
        <h1 id="ethnoracial-identities">
            {{ __('Ethnoracial Identities') }}
        </h1>
    </x-slot>

    <div role="region" aria-labelledby="ethnoracial-identities" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
            </tr>
            </thead>
            @forelse ($ethnoracialIdentities as $ethnoracialIdentity)
                <tr>
                    <td>{{ $ethnoracialIdentity->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>


