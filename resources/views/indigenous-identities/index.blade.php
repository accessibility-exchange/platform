<x-app-layout>
    <x-slot name="title">{{ __('Indigenous Identities') }}</x-slot>
    <x-slot name="header">
        <h1 id="indigenous-identities">
            {{ __('Indigenous Identities') }}
        </h1>
    </x-slot>

    <div role="region" aria-labelledby="indigenous-identities" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
            </tr>
            </thead>
            @forelse ($indigenousIdentities as $indigenousIdentity)
                <tr>
                    <td>{{ $indigenousIdentity->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>


