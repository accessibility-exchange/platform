<x-app-wide-layout>
    <x-slot name="title">{{ __('Glossary') }}</x-slot>
    <x-slot name="header">
        <h1 id="project">
            {{ __('Glossary') }}
        </h1>
    </x-slot>
    @if(count($terms) > 0)
    <dl>
        @foreach($terms as $term)
        <dt>{{ $term->term }}</dt>
        <dd>{{ $term->definition }}</dd>
        @endforeach
    </dl>
    @else
    <p>{{ __('Nothing found.') }}</p>
    @endif
</x-app-wide-layout>
