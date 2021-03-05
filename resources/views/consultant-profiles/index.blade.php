<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('consultant-profile.index_title') }}
        </h1>
    </x-slot>

   <div class="flow">
    @if($consultantProfiles)
        @foreach($consultantProfiles as $consultantProfile)
        <article>
            <h2>
                <a href="{{ localized_route('consultant-profiles.show', $consultantProfile) }}">{{ $consultantProfile->name }}</a>
            </h2>
            <p>{{ $consultantProfile->locality }}, {{ __('geography.' . $consultantProfile->region) }}</p>
        </article>
        @endforeach
    @else
        <p>{{ __('consultant-profile.none_found') }}</p>
    @endif
    </div>
</x-app-layout>
