<x-app-layout page-width="wide">
    <x-slot name="title">{{ $page->title }}</x-slot>
    <x-slot name="header">
        <ol class="breadcrumbs" role="list">
            <li><a href="{{ localized_route('welcome') }}">{{ __('About the Accessibility Exchange') }}</a></li>
        </ol>
        <h1>
            {{ $page->title }}
        </h1>
        <x-interpretation name="{{ $page->getTranslation('title', 'en') }}" />
    </x-slot>

    <p><strong>{{ __('Last updated: :date', ['date' => $page->updated_at->isoFormat('LL')]) }}</strong></p>
    <div class="stack">
        @if (empty($content) || $content->isEmpty())
            <p>{{ __('Coming soon') }}</p>
        @else
            {{ $content }}
        @endif
    </div>

</x-app-layout>
