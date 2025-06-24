<x-app-layout body-class="page tool" page-width="wide" header-class="full header--tool">
    <x-slot name="title">{{ $tool->title }}</x-slot>
    <x-slot name="header">
        <div class="center center:wide welcome pb-32">
            <ol class="breadcrumbs" role="list">
                <li><a href="{{ localized_route('tools.index') }}">{{ __('Tools') }}</a></li>
            </ol>
            <h1>
                {{ $tool->title }}
            </h1>
            <x-interpretation name="{{ __('Tool', [], 'en') }}" />

            @if ($tool->description)
                {!! Str::markdown($tool->description, config('markdown')) !!}
            @endif
        </div>
    </x-slot>

    <div class="center center:wide pb-28">
        <div class="stack md:w-2/3">
            @if (empty($content) || $content->isEmpty())
                <p>{{ __('Coming soon') }}</p>
            @else
                {{ $content }}
            @endif
        </div>

        @can('update', $tool)
            <p class="mt-12"><a class="cta secondary"
                    href="{{ route('filament.admin.resources.tools.edit', $tool) }}">@svg('heroicon-o-pencil', 'mr-1')
                    {{ __('Edit tool') }}</a></p>
        @endcan
    </div>
    @if ($tool->documents->count())
        <x-section class="full dark -mb-8" aria-labelledby="download-tool">
            <div class="center stack stack:xl py-20">
                <h2 id="download-tool">{{ __('Download tool') }}</h2>
                <x-interpretation name="{{ __('Download tool', [], 'en') }}" />
                {{-- TODO: Show email form for guests. --}}
                {{-- TODO: Show download links once email has been submitted or if a user is logged in. --}}
            </div>
        </x-section>
    @endif
</x-app-layout>
