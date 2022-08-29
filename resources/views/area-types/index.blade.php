<x-app-layout>
    <x-slot name="title">{{ __('Area Types') }}</x-slot>
    <x-slot name="header">
        <h1 id="area-types">
            {{ __('Area Types') }}
        </h1>
    </x-slot>

    <div role="region" aria-labelledby="area-types" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                </tr>
            </thead>
            @forelse ($areaTypes as $areaType)
                <tr>
                    <td>{{ $areaType->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>
