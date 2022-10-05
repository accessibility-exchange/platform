<x-app-wide-layout>
    <x-slot name="title">{{ __('Individuals') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Individuals') }}
        </h1>
    </x-slot>

    <div class="grid">
        @forelse($individuals as $individual)
            <x-card class="individual">
                <x-slot name="title">
                    <a href="{{ localized_route('individuals.show', $individual) }}">{{ $individual->name }}</a>
                </x-slot>
                <p>
                    <strong>{{ __('Individual') }}</strong>
                    @if ($individual->display_roles)
                        <br />
                        <strong class="font-semibold">{{ __('Role') }}:</strong>
                        @foreach ($individual->display_roles as $role)
                            {{ $role }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @endif
                </p>
                <p>
                    <strong class="font-semibold">{{ __('Location') }}:</strong> {{ $individual->locality }},
                    {{ get_region_name($individual->region, ['CA'], locale()) }}
                </p>
            </x-card>
            @empty
                <p>{{ __('No individuals found.') }}</p>
            @endforelse
        </div>
    </x-app-wide-layout>
