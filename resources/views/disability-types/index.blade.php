<x-app-layout>
    <x-slot name="title">{{ __('Disability Types') }}</x-slot>
    <x-slot name="header">
        <h1 id="disability-types">
            {{ __('Disability Types') }}
        </h1>
    </x-slot>

    <div role="region" aria-labelledby="disability-types" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                </tr>
            </thead>
            @forelse ($disabilityTypes as $disabilityType)
                <tr>
                    <td>{{ $disabilityType->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>
