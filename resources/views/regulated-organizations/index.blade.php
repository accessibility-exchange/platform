<x-app-wide-layout>
    <x-slot name="title">{{ __('Federally regulated organizations') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Federally regulated organizations') }}
        </h1>
    </x-slot>

    <div class="grid">
        @forelse($regulatedOrganizations as $regulatedOrganization)
            <x-card class="regulated-organization">
                <x-slot name="title">
                    <a
                        href="{{ localized_route('regulated-organizations.show', $regulatedOrganization) }}">{{ $regulatedOrganization->name }}</a>
                </x-slot>
                <p>
                    <strong>{{ __('Federally regulated organization') }}</strong><br />
                    <strong class="font-semibold">{{ __('Sector') }}:</strong>
                    @foreach ($regulatedOrganization->sectors as $sector)
                        {{ $sector->name }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </p>
            </x-card>
            @empty
                <p>{{ __('No federally regulated organizations found.') }}</p>
            @endforelse
        </div>
    </x-app-wide-layout>
