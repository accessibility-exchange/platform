<x-app-layout>
    <x-slot name="title">{{ __('Age Brackets') }}</x-slot>
    <x-slot name="header">
        <h1 id="age-brackets">
            {{ __('Age Brackets') }}
        </h1>
    </x-slot>

    <div role="region" aria-labelledby="age-brackets" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
            </tr>
            </thead>
            @forelse ($ageBrackets as $ageBracket)
                <tr>
                    <td>{{ $ageBracket->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('None found.') }}</td>
                </tr>
            @endforelse
        </table>
    </div>
</x-app-layout>


