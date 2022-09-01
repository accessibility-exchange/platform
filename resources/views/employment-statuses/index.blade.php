<x-app-layout>
    <x-slot name="title">{{ __('Employment Statuses') }}</x-slot>
    <x-slot name="header">
        <h1 id="employment-statuses">
            {{ __('Employment Statuses') }}
        </h1>
    </x-slot>

    <div role="region" aria-labelledby="employment-statuses" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                </tr>
            </thead>
            @forelse ($employmentStatuses as $employmentStatus)
                <tr>
                    <td>{{ $employmentStatus->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>
