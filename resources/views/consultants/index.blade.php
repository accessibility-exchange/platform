<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ __('consultant.index_title') }}
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
        <p>{{ __('consultant.none_found') }}</p>
        @endforelse
    </div>
</x-app-layout>
