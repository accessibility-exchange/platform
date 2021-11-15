<x-app-layout>
    <x-slot name="title">{{ __('Consultants') }}</x-slot>
    <x-slot name="header">
        <h1>
            {{ __('Consultants') }}
        </h1>
    </x-slot>

   <div class="flow">
        @forelse($consultants as $consultant)
        <article>
            <h2>
                <a href="{{ localized_route('consultants.show', $consultant) }}">{{ $consultant->name }}</a>
            </h2>
            <p>{{ $consultant->locality }}, {{ get_region_name($consultant->region, ["CA"], locale()) }}</p>
        </article>
        @empty
        <p>{{ __('No consultants found.') }}</p>
        @endforelse
    </div>
</x-app-layout>
