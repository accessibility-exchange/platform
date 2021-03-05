<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $consultantProfile->name }}
        </h1>
    </x-slot>

    <p>{{ $consultantProfile->locality }}, {{ __('geography.' . $consultantProfile->region) }}</p>

    @can('update', $consultantProfile)
    <p><a href="{{ localized_route('consultant-profiles.edit', ['consultantProfile' => $consultantProfile]) }}">{{ __('consultant-profile.edit_profile') }}</a></p>
    @endcan
</x-app-layout>
